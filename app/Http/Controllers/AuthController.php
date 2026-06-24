<?php

namespace App\Http\Controllers;

use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return redirect('/');
    }

    public function showRegisterForm()
    {
        return redirect('/');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email|max:255',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'Введите email',
            'email.email'       => 'Введите корректный email',
            'password.required' => 'Введите пароль',
            'password.min'      => 'Пароль должен содержать минимум 6 символов',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            $this->syncChatUser($user);

            if ($request->expectsJson()) {
                return response()->json([
                    'success'  => true,
                    'redirect' => $user->role === 'admin' ? '/admin' : '/',
                    'message'  => 'Вы успешно вошли!'
                ]);
            }

            return $user->role === 'admin'
                ? redirect()->intended('/admin')->with('success', 'Добро пожаловать, администратор!')
                : redirect()->intended('/')->with('success', 'Вы успешно вошли!');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'errors'  => ['general' => 'Неверный email или пароль']
            ], 422);
        }

        return back()->withErrors(['general' => 'Неверный email или пароль'])->withInput();
    }

       public function register(Request $request)
    {
        $setting = EmailVerification::where('email', '__system_settings__')->first();
        $verificationEnabled = $setting ? $setting->verification_enabled : true;

        $rules = [
            'name'       => ['required', 'string', 'min:4', 'max:100', 'regex:/^[\p{Cyrillic}\p{Latin}\s\-]+$/u'],
            'email'      => 'required|email|max:255|unique:users,email',
            'phone'      => 'required|string|min:10|max:20',
            'password'   => ['required', 'min:6', 'confirmed', Password::defaults()],
            'agree'      => 'accepted',
        ];

        $messages = [
            'name.required'       => 'Введите имя',
            'name.min'            => 'Имя должно содержать минимум 4 символа',
            'name.regex'          => 'Имя должно содержать только буквы',
            'email.required'      => 'Введите email',
            'email.email'         => 'Введите корректный email',
            'email.unique'        => 'Этот email уже зарегистрирован',
            'phone.required'      => 'Введите номер телефона',
            'phone.min'           => 'Введите корректный номер телефона',
            'password.required'   => 'Введите пароль',
            'password.min'        => 'Пароль должен содержать минимум 6 символов',
            'password.confirmed'  => 'Пароли не совпадают',
            'agree.accepted'      => 'Необходимо согласие с политикой конфиденциальности',
        ];

        if ($verificationEnabled) {
            $rules['email_code'] = 'required|string|size:6';
            $messages['email_code.required'] = 'Введите код подтверждения из письма';
            $messages['email_code.size'] = 'Код должен содержать 6 цифр';
        }

        $validated = $request->validate($rules, $messages);

        if ($verificationEnabled) {
            $verification = EmailVerification::where('email', $validated['email'])
                ->where('code', $validated['email_code'])
                ->where('expires_at', '>', now())
                ->first();

            if (!$verification) {
                $error = ['email_code' => 'Неверный или просроченный код подтверждения'];

                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'errors' => $error], 422);
                }
                return back()->withErrors($error)->withInput();
            }

            $verification->delete();
        }

        $phoneClean = preg_replace('/[^0-9]/', '', $validated['phone']);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $phoneClean,
            'password' => Hash::make($validated['password']),
            'role'     => 'user',
        ]);

        DB::table('chat_users')->insert([
            'user_id'       => 'user_' . $user->id,
            'name'          => $validated['name'],
            'phone'         => $phoneClean,
            'created_at'    => Carbon::now(),
            'last_activity' => Carbon::now(),
            'message_count' => 0
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        if ($request->expectsJson()) {
            return response()->json([
                'success'  => true,
                'redirect' => '/',
                'message'  => 'Регистрация успешна! Добро пожаловать!'
            ]);
        }

        return redirect('/')->with('success', 'Регистрация успешна! Добро пожаловать!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'success'  => true,
                'redirect' => '/',
                'message'  => 'Вы вышли из аккаунта'
            ]);
        }

        return redirect('/')->with('success', 'Вы вышли из аккаунта');
    }

    private function syncChatUser($user)
    {
        $userId = 'user_' . $user->id;
        $exists = DB::table('chat_users')->where('user_id', $userId)->exists();

        if (!$exists && $user->phone) {
            DB::table('chat_users')->insert([
                'user_id'       => $userId,
                'name'          => $user->name,
                'phone'         => $user->phone,
                'created_at'    => Carbon::now(),
                'last_activity' => Carbon::now(),
                'message_count' => 0
            ]);
        } elseif ($exists && $user->phone) {
            DB::table('chat_users')->where('user_id', $userId)->update([
                'name'          => $user->name,
                'phone'         => $user->phone,
                'last_activity' => Carbon::now()
            ]);
        }
    }

    public function checkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|max:255']);
        $exists = User::where('email', $request->email)->exists();

        return response()->json([
            'available' => !$exists,
            'message'   => $exists ? 'Этот email уже зарегистрирован' : 'Email доступен',
        ]);
    }
}