<?php

namespace App\Http\Controllers;

use App\Models\CarConfig;
use App\Models\CarMedia;
use App\Models\UserConfig;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ConfiguratorController extends Controller
{
    protected $interiorTypes = [
        'business' => 'Business',
        'standart' => 'Standard',
        'luxury'   => 'Luxury',
        'sport'    => 'Sport'
    ];

    protected $interiorPrices = [
        'standard' => 0,
        'business' => 150000,
        'luxury'   => 300000,
        'premium'  => 450000,
        'sport'    => 200000,
    ];

    public function index()
    {
        $configurations = CarConfig::with('media')->orderBy('created_at', 'desc')->get();
        $brands = $configurations->map(fn($c) => explode(' ', $c->name)[0])->unique()->values();

        $is_logged_in = Auth::check();

        return view('configurator.index', compact('configurations', 'brands', 'is_logged_in'));
    }


    public function show(Request $request, $id)
    {
        $carConfig = CarConfig::with('media')->findOrFail($id);
        $allConfigs = CarConfig::with('media')->orderBy('created_at', 'desc')->get();

        $carsData = [];
        foreach ($allConfigs as $cfg) {
            $carsData[$cfg->car_key] = $this->buildCarData($cfg);
        }

        $selectedCar = $carsData[$carConfig->car_key] ?? null;
        if (!$selectedCar && !empty($carsData)) {
            $selectedCar = reset($carsData);
        }

        $selectedCarKey = $carConfig->car_key;

        return view('configurator.show', compact('carsData', 'selectedCar', 'selectedCarKey'));
    }

    public function save(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'errors' => ['Необходима авторизация']], 401);
        }

        try {
            $validated = $request->validate([
                'car_config_id' => 'required|exists:car_configs,id',
                'config_name'   => 'required|string|max:100',
                'total_price'   => 'required|numeric|min:0',
                'selected_engine'  => 'nullable|string',
                'selected_color'   => 'nullable|string',
                'selected_interior'=> 'nullable|string',
                'selected_wheel'   => 'nullable|string',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        }

        $exists = UserConfig::where('user_id', Auth::id())
            ->where('config_name', $validated['config_name'])
            ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'errors' => ['Конфигурация с таким названием уже существует']]);
        }

        $userConfig = UserConfig::create([
            'user_id'          => Auth::id(),
            'car_config_id'    => $validated['car_config_id'],
            'config_name'      => $validated['config_name'],
            'total_price'      => $validated['total_price'],
            'selected_engine'  => $validated['selected_engine'],
            'selected_color'   => $validated['selected_color'],
            'selected_interior'=> $validated['selected_interior'],
            'selected_wheel'   => $validated['selected_wheel'],
        ]);

        return response()->json(['success' => true, 'config_id' => $userConfig->id]);
    }

   public function aiQuestion(Request $request)
{
    if (!Auth::check()) {
        return response()->json(['success' => false, 'answer' => 'Необходима авторизация.'], 401);
    }

    $question = trim($request->input('ai_question'));
    $carModel = $request->input('car_model', '');
    $carConfigId = $request->input('car_config_id');

    if (empty($question)) {
        return response()->json(['success' => false, 'answer' => 'Пожалуйста, задайте вопрос.']);
    }

    $userId = 'user_' . Auth::id();
    $user = Auth::user();

    Message::create([
        'user_id' => $userId,
        'car_config_id' => $carConfigId,      
        'message_text' => $question,
        'message_type' => 'sent',
        'chat_type' => 'configurator',
        'created_at' => now(),
    ]);

    $this->sendTelegramNotification("Конфигуратор: $question", $user->name ?? '', $user->phone ?? '');

    try {
        $answer = $this->getOllamaResponse($question, $carModel);
    } catch (\Exception $e) {
        Log::error('AI question error: ' . $e->getMessage());
        $answer = $this->getFallbackAnswer($question, $carModel);
    }

    Message::create([
        'user_id' => $userId,
        'car_config_id' => $carConfigId,        
        'message_text' => $answer,
        'message_type' => 'received',
        'chat_type' => 'configurator',
        'created_at' => now(),
    ]);

    return response()->json([
        'success' => true,
        'answer' => $answer,
    ]);
}

    public function getHistory(Request $request)
{
    if (!Auth::check()) {
        return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
    }

    $userId = 'user_' . Auth::id();
    $carConfigId = $request->get('car_config_id');

    $query = Message::where('user_id', $userId)
        ->where('chat_type', 'configurator');

    if ($carConfigId) {
    $query->where(function ($q) use ($carConfigId) {
        $q->where('car_config_id', $carConfigId)
          ->orWhereNull('car_config_id');
    });
}

    $messages = $query->orderBy('created_at', 'asc')->get();

    $mapped = $messages->map(fn($msg) => [
        'id' => $msg->id,
        'text' => $msg->message_text,
        'dir' => $msg->message_type,
        'time' => $msg->created_at?->format('H:i') ?? now()->format('H:i'),
    ]);

    return response()->json([
        'success' => true,
        'messages' => $mapped,
    ]);
}

    private function sendTelegramNotification(string $message, string $userName, string $userPhone): void
    {
        try {
            $botToken = config('app.telegram_bot_token');
            $chatId = config('app.telegram_chat_id');

            if (!$botToken || !$chatId) {
                Log::debug('Telegram not configured');
                return;
            }

            $text = "*НОВОЕ СООБЩЕНИЕ В КОНФИГУРАТОРЕ*\n";
            $text .= "*Имя:* {$userName}\n";
            $text .= "*Телефон:* {$userPhone}\n";
            $text .= "*Сообщение:* {$message}";

            Http::timeout(10)->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'Markdown',
                'disable_web_page_preview' => true,
            ]);

            Log::info('Telegram notification sent (configurator)');
        } catch (\Exception $e) {
            Log::error('Telegram notification error: ' . $e->getMessage());
        }
    }

   protected function getOllamaResponse($question, $carModel)
{
    $ollamaUrl = config('app.ollama_url', 'http://localhost:11434');
    $model = config('app.ollama_model', 'gemma3:4b');
    $timeout = config('app.ollama_timeout', 30);

    $prompt = "Ты — дружелюбный AI-ассистент в конфигураторе автомобилей.

ВАЖНЫЕ ПРАВИЛА ФОРМАТИРОВАНИЯ:
- Отвечай ТОЛЬКО чистым текстом.
- **ЗАПРЕЩЕНО** использовать markdown: **жирный текст**, *курсив*, ## заголовки и любые звёздочки.
- Не используй символы ** в начале и конце слов.
- Будь кратким: максимум 2–3 предложения.

Текущая модель: " . $carModel . "’

Вопрос пользователя: " . $question;

    try {
        $response = Http::timeout($timeout)
            ->post($ollamaUrl . '/api/generate', [
                'model' => $model,
                'prompt' => $prompt,
                'stream' => false,
                'options' => [
                    'num_predict' => 180,
                    'temperature' => 0.7,
                ]
            ]);

        if ($response->successful()) {
            $data = $response->json();
            $answer = trim($data['response'] ?? '');

            $answer = preg_replace('/\*\*(.+?)\*\*/', '$1', $answer); 
            $answer = preg_replace('/\*(.+?)\*/', '$1', $answer);
            $answer = preg_replace('/\n{3,}/', "\n\n", $answer); 

            if (mb_strlen($answer) > 600) {
                $answer = mb_substr($answer, 0, 600) . '...';
            }

            return $answer ?: $this->getFallbackAnswer($question, $carModel);
        }
    } catch (\Exception $e) {
        Log::error('Ollama configurator error: ' . $e->getMessage());
    }

    return $this->getFallbackAnswer($question, $carModel);
}

    protected function getFallbackAnswer($question, $carModel)
    {
        $q = mb_strtolower($question);

        if (str_contains($q, 'цена') || str_contains($q, 'стоимость')) {
            return "Базовая цена от 2 954 000 ₽. Итоговая сумма зависит от комплектации, двигателя и опций.";
        }

        if (str_contains($q, 'двигатель') || str_contains($q, 'мотор')) {
            return "Доступны дизельные и бензиновые двигатели мощностью 184–530 л.с. Выберите в конфигураторе.";
        }

        if (str_contains($q, 'расход') || str_contains($q, 'топливо')) {
            return "Расход: 6.8–11.5 л/100 км. Дизель экономичнее, бензин динамичнее.";
        }

        if (str_contains($q, 'комплектация') || str_contains($q, 'оснащение')) {
            return "Комплектации: Business, Luxury, Sport. Каждая включает разные пакеты опций.";
        }

        if (str_contains($q, 'цвет') || str_contains($q, 'окраска')) {
            return "Доступны металлик и неметаллик. Некоторые цвета — платные опции.";
        }

        if (str_contains($q, 'салон') || str_contains($q, 'интерьер')) {
            return "Варианты: кожа, алькантара, дерево, алюминий. Можно выбрать цвет и отделку.";
        }

        if (str_contains($q, 'колесо') || str_contains($q, 'диск') || str_contains($q, 'шина')) {
            return "Доступны различные типоразмеры дисков и сезонные варианты шин. Цена зависит от размера.";
        }

        if (str_contains($q, 'гарантия')) {
            return "Гарантия 3 года без ограничения пробега. Доступны расширенные программы.";
        }

        if (str_contains($q, 'сервис') || str_contains($q, 'обслуживание')) {
            return "Сервис с фиксированной стоимостью на 3 года. Интервалы по системе CBS.";
        }

        return "Помощь с конфигурацией $carModel. Спросите о двигателях, комплектациях или ценах.";
    }

      protected function buildCarData(CarConfig $cfg)
    {
        $configData = $cfg->config_data ?? [];
        $colors = [];
        $colorImages = [];
        $interiorImages = [];
        $models = [];
        $mainImage = null;

        foreach ($cfg->media as $media) {
            switch ($media->type) {
                case 'color_image':
                    $colorImages[$media->color_key][] = [
                        'frame_index' => $media->sort_order,
                        'image_url'   => route('media.show', $media->id),
                        'image_name'  => $media->title ?? 'Цвет',
                    ];
                    break;
                case 'interior_image':
                    $interiorImages[$media->interior_key][] = [
                        'id'         => $media->id,
                        'image_url'  => route('media.show', $media->id),
                        'image_name' => $media->title ?? '',
                        'sort_order' => $media->sort_order,
                    ];
                    break;
                case 'model_3d':
                    $models[] = [
                        'id'        => $media->id,
                        'title'     => $media->title,
                        'file_path' => asset('storage/'.$media->file_path),
                        'file_type' => 'glb',
                    ];
                    break;
                case 'main_image':
                    $mainImage = route('media.show', $media->id);
                    break;
            }
        }

        foreach ($configData['colors'] ?? [] as $key => $color) {
            $colors[$key] = [
                'label'  => $color['label'],
                'hex'    => $color['hex'],
                'price'  => $color['price'],
                'group'  => $color['group'],
                'images' => $colorImages[$key] ?? [],
            ];
        }

        return [
            'id'              => $cfg->id,
            'name'            => $cfg->name,
            'basePrice'       => (int) $cfg->base_price,
            'variant'         => $cfg->variant,
            'description'     => $cfg->description ?? '',
            'colors'          => $colors,
            'interior'        => $interiorImages,
            'interior_prices' => $this->interiorPrices,
            'engines'         => $configData['engines'] ?? [],
            'wheels'          => array_map(function ($wheel) {
                if (!empty($wheel['image_url'])) {
                    $wheel['image_url'] = asset('storage/' . $wheel['image_url']);
                }
                return $wheel;
            }, $configData['wheels'] ?? []),
            'models'          => $models,
            'mainImage'       => $mainImage,
        ];
    }
}