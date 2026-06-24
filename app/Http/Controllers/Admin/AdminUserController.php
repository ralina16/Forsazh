<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        $currentUser = Auth::user();

        // Получаем состояние настройки верификации
        $setting = EmailVerification::where('email', '__system_settings__')->first();
        $emailVerificationEnabled = $setting ? $setting->verification_enabled : true;

        return view('admin.users.index', compact('users', 'currentUser', 'emailVerificationEnabled'));
    }

    /**
     * Переключение проверки email (AJAX)
     */
    public function toggleEmailVerification(Request $request): JsonResponse
    {
        $setting = EmailVerification::where('email', '__system_settings__')->first();

        if (!$setting) {
            $setting = EmailVerification::create([
                'email' => '__system_settings__',
                'code' => '000000',
                'expires_at' => now()->addYear(),
                'verification_enabled' => true,
            ]);
        }

        $newState = !$setting->verification_enabled;
        $setting->update(['verification_enabled' => $newState]);

        return response()->json([
            'success' => true,
            'enabled' => $newState,
            'message' => $newState 
                ? 'Проверка email включена' 
                : 'Проверка email отключена. Регистрация возможна без подтверждения почты.'
        ]);
    }

    // ... остальные методы без изменений ...
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', Password::min(6)],
            'role' => ['required', Rule::in(['user', 'admin'])],
        ]);

        try {
            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ]);

            return $request->ajax()
                ? response()->json(['success' => true, 'message' => 'Пользователь успешно добавлен'])
                : redirect()->route('admin.users.index')->with('success', 'Пользователь успешно добавлен');

        } catch (\Exception $e) {
            return $request->ajax()
                ? response()->json(['errors' => ['database' => ['Ошибка базы данных']]], 500)
                : redirect()->back()->withErrors(['Ошибка базы данных'])->withInput();
        }
    }

    public function update(Request $request, User $user)
    {
        if ($user->id === Auth::id()) {
            $request->merge(['role' => $user->role]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        try {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            return $request->ajax()
                ? response()->json(['success' => true, 'message' => 'Пользователь успешно обновлён'])
                : redirect()->route('admin.users.index')->with('success', 'Пользователь успешно обновлён');

        } catch (\Exception $e) {
            return $request->ajax()
                ? response()->json(['errors' => ['database' => ['Ошибка базы данных']]], 500)
                : redirect()->back()->withErrors(['Ошибка базы данных'])->withInput();
        }
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === Auth::id()) {
            return $request->ajax()
                ? response()->json(['errors' => ['self' => ['Нельзя удалить самого себя']]], 403)
                : redirect()->back()->withErrors(['Нельзя удалить самого себя']);
        }

        try {
            $user->delete();
            return $request->ajax()
                ? response()->json(['success' => true, 'message' => 'Пользователь успешно удалён'])
                : redirect()->route('admin.users.index')->with('success', 'Пользователь успешно удалён');
        } catch (\Exception $e) {
            return $request->ajax()
                ? response()->json(['errors' => ['database' => ['Ошибка базы данных']]], 500)
                : redirect()->back()->withErrors(['Ошибка базы данных']);
        }
    }

    public function changePassword(Request $request, User $user)
    {
        if ($user->id !== Auth::id()) {
            return $request->ajax()
                ? response()->json(['errors' => ['permission' => ['Можно менять пароль только для своего аккаунта']]], 403)
                : redirect()->back()->withErrors(['Можно менять пароль только для своего аккаунта']);
        }

        $validated = $request->validate([
            'new_password' => ['required', 'confirmed', Password::min(6)],
        ]);

        try {
            $user->update(['password' => Hash::make($validated['new_password'])]);
            return $request->ajax()
                ? response()->json(['success' => true, 'message' => 'Пароль успешно изменён'])
                : redirect()->route('admin.users.index')->with('success', 'Пароль успешно изменён');
        } catch (\Exception $e) {
            return $request->ajax()
                ? response()->json(['errors' => ['database' => ['Ошибка базы данных']]], 500)
                : redirect()->back()->withErrors(['Ошибка базы данных']);
        }
    }

    public function changeRole(Request $request, User $user)
    {
        if ($user->id === Auth::id()) {
            return $request->ajax()
                ? response()->json(['errors' => ['self' => ['Нельзя изменить роль самому себе']]], 403)
                : redirect()->back()->withErrors(['Нельзя изменить роль самому себе']);
        }

        $validated = $request->validate([
            'role' => ['required', Rule::in(['user', 'admin'])],
        ]);

        try {
            $user->update(['role' => $validated['role']]);
            return $request->ajax()
                ? response()->json(['success' => true, 'message' => 'Роль успешно изменена'])
                : redirect()->route('admin.users.index')->with('success', 'Роль успешно изменена');
        } catch (\Exception $e) {
            return $request->ajax()
                ? response()->json(['errors' => ['database' => ['Ошибка базы данных']]], 500)
                : redirect()->back()->withErrors(['Ошибка базы данных']);
        }
    }
}