<?php

namespace App\Http\Controllers;

use App\Mail\VerificationCodeMail;
use App\Models\EmailVerification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class EmailVerificationController extends Controller
{
    /**
     * Проверяет, включена ли верификация email
     */
    private function isVerificationEnabled(): bool
    {
        $setting = EmailVerification::where('email', '__system_settings__')->first();
        return $setting ? $setting->verification_enabled : true;
    }

    public function send(Request $request): JsonResponse
    {
        // Если проверка отключена — сразу возвращаем success
        if (!$this->isVerificationEnabled()) {
            return response()->json([
                'success' => true,
                'message' => 'Проверка email отключена администратором',
                'disabled' => true
            ]);
        }

        $request->validate([
            'email' => ['required', 'email', 'max:255']
        ]);

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        EmailVerification::updateOrCreate(
            ['email' => $request->email],
            ['code' => $code, 'expires_at' => now()->addMinutes(15)]
        );

        Mail::to($request->email)->send(new VerificationCodeMail($code));

        return response()->json(['success' => true, 'message' => 'Код отправлен']);
    }

    public function verify(Request $request): JsonResponse
    {
        // Если проверка отключена — сразу считаем верификацию пройденной
        if (!$this->isVerificationEnabled()) {
            return response()->json([
                'success' => true,
                'message' => 'Проверка email отключена администратором'
            ]);
        }

        $request->validate([
            'email' => ['required', 'email'],
            'code'  => ['required', 'string', 'size:6']
        ]);

        $record = EmailVerification::where('email', $request->email)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->first();

        if (!$record) {
            return response()->json(['success' => false, 'message' => 'Неверный или просроченный код'], 422);
        }

        return response()->json(['success' => true]);
    }
}