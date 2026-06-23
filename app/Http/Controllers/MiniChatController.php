<?php

namespace App\Http\Controllers;

use App\Models\ChatUser;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MiniChatController extends Controller
{
    public function check(Request $request)
    {
        try {
            $isLoggedIn = Auth::check();
            $user = Auth::user();

            if ($isLoggedIn) {
                $userId = 'user_'.Auth::id();
                $hasData = ! empty($user?->phone);
                $phone = $user?->phone ?? '';
                $name = $user?->name ?? '';
            } else {
                if (! $request->hasSession()) {
                    $request->session()->start();
                }
                $userId = 'guest_'.$request->session()->getId();
                $hasData = false;
                $phone = '';
                $name = '';
            }

            if ($isLoggedIn) {
                $hasMessages = Message::where('user_id', $userId)
                    ->where('chat_type', 'mini_chat')
                    ->exists();

                if (! $hasMessages) {
                    $greeting = match (true) {
                        now()->hour >= 5 && now()->hour < 12 => 'Доброе утро!',
                        now()->hour >= 12 && now()->hour < 18 => 'Добрый день!',
                        now()->hour >= 18 && now()->hour < 23 => 'Добрый вечер!',
                        default => 'Доброй ночи!',
                    };

                    $welcomeText = "$greeting Я Смарти, ваш AI-помощник автосалона 'Форсаж'. Чем могу помочь?";

                    Message::create([
                        'user_id' => $userId,
                        'message_text' => $welcomeText,
                        'message_type' => 'received',
                        'chat_type' => 'mini_chat',
                        'created_at' => now(),
                    ]);
                }

                ChatUser::updateOrInsert(
                    ['user_id' => $userId],
                    [
                        'name' => $name ?: 'Пользователь',
                        'phone' => $phone,
                        'last_activity' => now(),
                        'is_guest' => false,
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'has_data' => $hasData,
                'phone' => $phone,
                'name' => $name,
                'is_guest' => ! $isLoggedIn,
                'is_logged_in' => $isLoggedIn,
                'user_id' => $userId,
            ]);
        } catch (\Throwable $e) {
            Log::error('MiniChat check error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Server error',
            ], 500);
        }
    }

    public function register(Request $request)
    {
        try {
            if (! Auth::check()) {
                return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
            }

            $validated = $request->validate([
                'name' => 'required|string|min:2|max:100',
                'phone' => 'required|string|min:10|max:20',
            ]);

            $userId = 'user_'.Auth::id();
            $phoneClean = preg_replace('/[^0-9]/', '', $validated['phone']);

            $user = Auth::user();
            $user->phone = $phoneClean;
            $user->save();

            ChatUser::updateOrInsert(
                ['user_id' => $userId],
                [
                    'name' => $validated['name'],
                    'phone' => $phoneClean,
                    'last_activity' => now(),
                    'is_guest' => false,
                ]
            );

            $this->sendTelegramNewUser($validated['name'], $phoneClean);

            return response()->json(['success' => true]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => collect($e->errors())->first()[0] ?? 'Validation error',
            ], 422);
        } catch (\Throwable $e) {
            Log::error('MiniChat register error: '.$e->getMessage());

            return response()->json(['success' => false, 'error' => 'Server error'], 500);
        }
    }

    public function getMessages(Request $request)
    {
        try {
            $isLoggedIn = Auth::check();

            if (! $isLoggedIn) {
                return response()->json([
                    'success' => true,
                    'messages' => [[
                        'id' => 0,
                        'text' => 'Привет! Я Смарти — ваш AI помощник автосалона «Форсаж». Для сохранения истории диалога и доступа ко всем функциям, пожалуйста, авторизуйтесь.',
                        'dir' => 'received',
                        'time' => now()->format('H:i'),
                        'created_at' => now(),
                        'format' => 'text',
                        'html' => null,
                        'is_read' => true,
                    ]],
                    'is_logged_in' => false,
                ]);
            }

            $userId = 'user_'.Auth::id();
            $lastId = (int) $request->get('last_id', 0);

            $query = Message::where('user_id', $userId)
                ->where('chat_type', 'mini_chat')
                ->orderBy('created_at', 'asc');

            if ($lastId > 0) {
                $query->where('id', '>', $lastId);
            }

            $messages = $query->get()->map(function ($msg) {
                $html = null;

                if ($msg->message_format === 'price_card') {
                    $catalogUrl = route('catalog.index');
                    $html = '<div style="text-align:center;padding:12px 0;">
            <a href="'.$catalogUrl.'" target="_blank" 
               style="display:inline-block;padding:10px 20px;
                      background:linear-gradient(135deg,#4071CB 0%,#5A8DE8 100%);
                      color:white;border-radius:10px;text-decoration:none;
                      font-weight:600;font-size:13px;
                      box-shadow:0 4px 12px rgba(64,113,203,0.3);">
                Посмотреть каталог →
            </a>
        </div>';
                }

                return [
                    'id' => (int) $msg->id,
                    'text' => $msg->message_text,
                    'dir' => $msg->message_type,
                    'time' => $msg->created_at?->format('H:i') ?? now()->format('H:i'),
                    'created_at' => $msg->created_at,
                    'format' => $msg->message_format ?? 'text',
                    'html' => $html,
                    'is_read' => (bool) $msg->is_read,
                ];
            })->toArray();

            ChatUser::where('user_id', $userId)->update(['last_activity' => now()]);

            return response()->json([
                'success' => true,
                'messages' => $messages,
                'is_logged_in' => true,
            ]);
        } catch (\Throwable $e) {
            Log::error('MiniChat getMessages error: '.$e->getMessage());

            return response()->json(['success' => false, 'error' => 'Server error'], 500);
        }
    }

    public function sendMessage(Request $request)
    {
        try {
            if (! Auth::check()) {
                return response()->json([
                    'success' => true,
                    'answer' => 'Для сохранения истории диалога и доступа ко всем функциям необходима авторизация.',
                    'is_guest' => true,
                ]);
            }

            $text = trim($request->input('message_text', $request->input('message', '')));
            if (empty($text)) {
                return response()->json(['success' => false, 'error' => 'Empty message']);
            }

            $userId = 'user_'.Auth::id();

            $message = Message::create([
                'user_id' => $userId,
                'message_text' => $text,
                'message_type' => 'sent',
                'chat_type' => 'mini_chat',
                'created_at' => now(),
            ]);

            ChatUser::where('user_id', $userId)->update(['last_activity' => now()]);

            $user = Auth::user();
            $this->sendTelegramNotification($text, $user->name ?? '', $user->phone ?? '');

            return response()->json([
                'success' => true,
                'message_id' => $message->id,
            ]);
        } catch (\Throwable $e) {
            Log::error('MiniChat sendMessage error: '.$e->getMessage());

            return response()->json(['success' => false, 'error' => 'Server error'], 500);
        }
    }

    public function aiResponse(Request $request)
    {
        try {
            $text = trim($request->input('message_text', ''));
            if (empty($text)) {
                return response()->json(['success' => false, 'error' => 'Empty message']);
            }

            $isLoggedIn = Auth::check();
            $userId = $isLoggedIn ? 'user_'.Auth::id() : 'guest_'.$request->session()->getId();
            $lowerText = mb_strtolower($text);

            $templates = [
                'позвоните|позвонить|звонок|свяжитесь|перезвоните' => 'Спасибо за обращение! Мы перезвоним вам в ближайшее время. Удобное время для звонка?',
                'прайс|прайс-лист|цены|стоимость|цена|сколько стоит' => 'Конечно! Наш прайс-лист обновляется ежедневно. Актуальные цены: от 500 000 ₽ за авто с пробегом, от 1 200 000 ₽ за новые. Хотите, чтобы менеджер отправил полный прайс на WhatsApp?',
                'тест-драйв|тест драйв|записаться на тест' => 'Отлично! Запись на тест-драйв бесплатная. Оставьте номер телефона, и менеджер свяжется для подтверждения времени.',
                'кредит|авто в кредит|рассрочка' => 'Мы работаем с 10+ банками! Одобрение за 15 минут, первый взнос от 0%. Хотите, чтобы менеджер рассчитал ежемесячный платёж?',
            ];

            foreach ($templates as $keywords => $response) {
                foreach (explode('|', $keywords) as $keyword) {
                    if (str_contains($lowerText, trim($keyword))) {
                        return $this->saveAndReturnResponse($userId, $response, true, 'mini_chat');
                    }
                }
            }

            $aiResponse = $this->callOllama($text, $userId);

            return $this->saveAndReturnResponse($userId, $aiResponse, false, 'mini_chat');
        } catch (\Throwable $e) {
            Log::error('MiniChat aiResponse error: '.$e->getMessage());

            return response()->json(['success' => false, 'error' => 'Server error'], 500);
        }
    }

    public function getPriceList(Request $request)
    {
        try {
            if (! Auth::check()) {
                return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
            }

            $userId = 'user_'.Auth::id();

           $cars = \App\Models\Car::query()
    ->select('id', 'brand', 'model', 'photo', 'price')
    ->orderBy('id', 'asc')   
    ->limit(2)               
    ->get();

            if ($cars->isEmpty()) {
                $catalogUrl = route('catalog.index');
                $text = "Наш каталог доступен по ссылке:\n<a href='{$catalogUrl}' target='_blank'>Открыть каталог →</a>";

                $message = Message::create([
                    'user_id' => $userId,
                    'message_text' => $text,
                    'message_type' => 'received',
                    'message_format' => 'html',
                    'chat_type' => 'mini_chat',
                    'created_at' => now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message_id' => $message->id,
                    'text' => $text,
                    'format' => 'html',
                    'html' => null,
                ]);
            }

            $html = view('partials.chat-price-cards', [
                'cars' => $cars,
                'catalogUrl' => route('catalog.index'),
            ])->render();

            $extraData = json_encode(['html' => $html]);

            $message = Message::create([
                'user_id' => $userId,
                'message_text' => 'Актуальные предложения:',
                'message_type' => 'received',
                'message_format' => 'price_card',
                'chat_type' => 'mini_chat',
                'created_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message_id' => $message->id,
                'text' => $message->message_text,
                'format' => 'price_card',
                'html' => $html,
            ]);
        } catch (\Throwable $e) {
            Log::error('Price list error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Ошибка сервера: '.$e->getMessage(),
            ], 500);
        }
    }

    public function clearChat(Request $request)
    {
        try {
            if (! Auth::check()) {
                return response()->json(['success' => true, 'is_guest' => true]);
            }

            $userId = 'user_'.Auth::id();

            Message::where('user_id', $userId)
                ->where('chat_type', 'mini_chat')
                ->delete();

            ChatUser::where('user_id', $userId)->update(['message_count' => 0]);

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            Log::error('MiniChat clearChat error: '.$e->getMessage());

            return response()->json(['success' => false, 'error' => 'Server error'], 500);
        }
    }

    public function markAsRead(Request $request)
    {
        try {
            if (! Auth::check()) {
                return response()->json(['success' => true, 'is_guest' => true]);
            }

            $userId = 'user_'.Auth::id();

            Message::where('user_id', $userId)
                ->where('chat_type', 'mini_chat')
                ->where('message_type', 'received')
                ->where('is_read', false)
                ->update(['is_read' => true]);

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            Log::error('MiniChat markAsRead error: '.$e->getMessage());

            return response()->json(['success' => false, 'error' => 'Server error'], 500);
        }
    }

    public function sendComplaint(Request $request)
    {
        try {
            if (! Auth::check()) {
                return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
            }

            $validated = $request->validate([
                'type' => 'required|in:technical,service,content,other',
                'text' => 'required|string|min:10|max:1000',
            ], [
                'type.required' => 'Выберите тип жалобы',
                'type.in' => 'Неверный тип жалобы',
                'text.required' => 'Введите описание проблемы',
                'text.min' => 'Описание должно содержать минимум 10 символов',
                'text.max' => 'Описание не должно превышать 1000 символов',
            ]);

            Message::create([
                'user_id' => 'user_'.Auth::id(),
                'message_text' => "Жалоба: {$validated['text']}",
                'message_type' => 'sent',
                'chat_type' => 'mini_chat',
                'created_at' => now(),
            ]);

            $user = Auth::user();
            $this->sendTelegramProblemReport($validated['text'], $validated['type'], $user->name ?? '', $user->phone ?? '');

            return response()->json(['success' => true]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            $firstError = reset($errors);

            return response()->json([
                'success' => false,
                'error' => is_array($firstError) ? $firstError[0] : $firstError,
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Send complaint error: '.$e->getMessage());

            return response()->json(['success' => false, 'error' => 'Произошла ошибка при отправке жалобы'], 500);
        }
    }

    private function saveAndReturnResponse(string $userId, string $response, bool $isTemplate, string $chatType = 'mini_chat')
    {
        try {
            if (str_starts_with($userId, 'guest_')) {
                return response()->json([
                    'success' => true,
                    'response' => $response,
                    'message_id' => 0,
                    'is_template' => $isTemplate,
                    'is_guest' => true,
                ]);
            }

            $duplicate = Message::where('user_id', $userId)
                ->where('message_text', $response)
                ->where('message_type', 'received')
                ->where('chat_type', $chatType)
                ->where('created_at', '>', now()->subSeconds(30))
                ->first();

            if ($duplicate) {
                return response()->json([
                    'success' => true,
                    'response' => $response,
                    'message_id' => $duplicate->id,
                    'is_template' => $isTemplate,
                    'is_duplicate' => true,
                ]);
            }

            $message = Message::create([
                'user_id' => $userId,
                'message_text' => $response,
                'message_type' => 'received',
                'chat_type' => $chatType,
                'created_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'response' => $response,
                'message_id' => $message->id,
                'is_template' => $isTemplate,
                'is_duplicate' => false,
            ]);
        } catch (\Throwable $e) {
            Log::error('MiniChat saveAndReturnResponse error: '.$e->getMessage());

            return response()->json(['success' => false, 'error' => 'Server error'], 500);
        }
    }

    private function getConversationMessages(string $userId): array
    {
        if (str_starts_with($userId, 'guest_')) {
            return [];
        }

        $messages = Message::where('user_id', $userId)
            ->where('chat_type', 'mini_chat')
            ->orderBy('created_at', 'asc')
            ->limit(20)
            ->get();

        $result = [];
        foreach ($messages as $msg) {
            $result[] = [
                'role' => $msg->message_type === 'sent' ? 'user' : 'assistant',
                'content' => $msg->message_text,
            ];
        }

        return $result;
    }

   private function callOllama(string $userMessage, string $userId): string
{
    try {
        $ollamaUrl = config('app.ollama_url', 'http://localhost:11434');
        $model = config('app.ollama_model', 'gemma3:4b');
        $timeout = config('app.ollama_timeout', 45);

        $systemPrompt = 'Ты — Смарти, умный и дружелюбный AI-помощник автосалона «Форсаж» в Казани.

ВАЖНЫЕ ПРАВИЛА ФОРМАТИРОВАНИЯ:
- **НИКОГДА** не используй markdown: **жирный**, *курсив*, ## заголовки и т.д.
- Отвечай **только чистым текстом** без звёздочек, подчёркиваний и других спецсимволов для форматирования.
- Используй только обычный текст, эмодзи и переносы строк при необходимости.

ТВОИ ЗАДАЧИ:
1. Отвечай на вопросы про автомобили, цены, условия покупки, кредит, тест-драйв, trade-in, обслуживание.
2. Давай конкретную полезную информацию, а не общие фразы.
3. Если не знаешь точных данных — говори честно, но предлагай решение.
4. Будь краток (1-3 предложения), но содержательным.

ТЕМЫ, В КОТОРЫХ ТЫ ЭКСПЕРТ:
• Цены: новые авто от 1 200 000 ₽, с пробегом от 500 000 ₽
• Кредит: первый взнос от 0%, срок до 7 лет, одобрение за 15 минут
• Trade-in: бесплатная оценка, доплата за ваш авто
• Тест-драйв: запись онлайн или по телефону, бесплатно
• Обслуживание: ТО, запчасти, гарантия, ремонт
• Доставка: по РФ, от 15 000 ₽, 3-14 дней

КОНТАКТЫ (упоминай, если вопрос сложный):
Телефон: +7 (987) 416-10-10
Адреса: ул. Чистопольская, 9а и пр. Ямашева, 76, Казань

ВАЖНЫЕ ПРАВИЛА:
• Веди диалог естественно, опираясь на историю переписки.
• НИКОГДА не здоровайся заново и не представляйся, если уже было приветствие.
• Если вопрос не по теме — вежливо возвращай к автомобилям.';

        $history = $this->getConversationMessages($userId);

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        foreach ($history as $msg) {
            $messages[] = $msg;
        }

        $messages[] = ['role' => 'user', 'content' => $userMessage];

        $response = Http::timeout($timeout)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post("{$ollamaUrl}/api/chat", [
                'model' => $model,
                'messages' => $messages,
                'stream' => false,
                'options' => [
                    'temperature' => 0.7,
                    'top_p' => 0.9,
                    'num_predict' => 320,
                ],
            ]);

        if ($response->successful()) {
            $result = $response->json();
            $aiText = trim($result['message']['content'] ?? '');

            $aiText = preg_replace('/\*\*(.+?)\*\*/', '$1', $aiText); 
            $aiText = preg_replace('/\*(.+?)\*/', '$1', $aiText);

            return $aiText;
        }
    } catch (\Exception $e) {
        Log::error('Ollama chat error: '.$e->getMessage());
    }

    return 'Спасибо за вопрос! Я могу помочь с выбором авто, ценами, кредитом или тест-драйвом. Что вас интересует?';
}

    // ==================== TELEGRAM УВЕДОМЛЕНИЯ ====================

    private function sendTelegramNotification(string $message, string $userName, string $userPhone): void
    {
        try {
            $botToken = config('app.telegram_bot_token') ?? env('TELEGRAM_BOT_TOKEN');
            $chatId = config('app.telegram_chat_id') ?? env('TELEGRAM_CHAT_ID');

            if (empty($botToken) || empty($chatId)) {
                Log::warning('Telegram not configured');

                return;
            }

            $text = "<b>НОВОЕ СООБЩЕНИЕ В ЧАТЕ</b>\n\n";
            $text .= '<b>Имя:</b> '.htmlspecialchars($userName, ENT_QUOTES, 'UTF-8')."\n";
            $text .= '<b>Телефон:</b> '.htmlspecialchars($userPhone, ENT_QUOTES, 'UTF-8')."\n";
            $text .= '<b>Сообщение:</b> '.htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

            $response = Http::timeout(10)->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true,
            ]);

            if (! $response->successful()) {
                Log::error('Telegram API error (notification)', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Telegram notification exception: '.$e->getMessage());
        }
    }

    private function sendTelegramNewUser(string $userName, string $userPhone): void
    {
        try {
            $botToken = config('app.telegram_bot_token') ?? env('TELEGRAM_BOT_TOKEN');
            $chatId = config('app.telegram_chat_id') ?? env('TELEGRAM_CHAT_ID');

            if (empty($botToken) || empty($chatId)) {
                return;
            }

            $text = "<b>НОВЫЙ ПОЛЬЗОВАТЕЛЬ В ЧАТЕ</b>\n\n";
            $text .= '<b>Имя:</b> '.htmlspecialchars($userName, ENT_QUOTES, 'UTF-8')."\n";
            $text .= '<b>Телефон:</b> '.htmlspecialchars($userPhone, ENT_QUOTES, 'UTF-8');

            $response = Http::timeout(10)->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML',
            ]);

            if (! $response->successful()) {
                Log::error('Telegram API error (new user)', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Telegram new user error: '.$e->getMessage());
        }
    }

    private function sendTelegramProblemReport(string $text, string $type, string $userName, string $userPhone): void
    {
        try {
            $botToken = config('app.telegram_bot_token') ?? env('TELEGRAM_BOT_TOKEN');
            $chatId = config('app.telegram_chat_id') ?? env('TELEGRAM_CHAT_ID');

            if (empty($botToken) || empty($chatId)) {
                return;
            }

            $typeLabels = [
                'technical' => 'Техническая',
                'service' => 'Сервис',
                'content' => 'Контент',
                'other' => 'Другое',
            ];

            $message = "<b>СООБЩЕНИЕ О ПРОБЛЕМЕ</b>\n\n";
            $message .= '<b>Тип:</b> '.($typeLabels[$type] ?? $type)."\n";
            $message .= '<b>Имя:</b> '.htmlspecialchars($userName, ENT_QUOTES, 'UTF-8')."\n";
            $message .= '<b>Телефон:</b> '.htmlspecialchars($userPhone, ENT_QUOTES, 'UTF-8')."\n";
            $message .= '<b>Проблема:</b> '.htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

            $response = Http::timeout(10)->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);

            if (! $response->successful()) {
                Log::error('Telegram API error (problem)', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Telegram problem report error: '.$e->getMessage());
        }
    }
}
