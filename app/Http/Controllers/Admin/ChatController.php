<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatUser;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function index()
    {
        return view('admin.chat.index');
    }

    public function getUsers(Request $request)
    {
        $q = $request->input('q', '');
        $chatType = $request->input('chat_type', 'mini_chat');

        $users = ChatUser::when($q, function ($query, $q) {
            $query->where('user_id', 'like', "%$q%")
                ->orWhere('name', 'like', "%$q%")
                ->orWhere('phone', 'like', "%$q%");
        })
            ->whereHas('messages', function ($query) use ($chatType) {
                $query->where('chat_type', $chatType);
            })
            ->orderBy('last_activity', 'desc')
            ->limit(500)
            ->get();

        $result = $users->map(function ($user) use ($chatType) {
            $lastMessage = $user->messages()
                ->where('chat_type', $chatType)
                ->latest()
                ->first();
            
            $unread = $user->messages()
                ->where('chat_type', $chatType)
                ->where('message_type', 'sent')
                ->where('created_at', '>', $user->last_activity ?? '1970-01-01')
                ->count();

            return [
                'user_id' => $user->user_id,
                'name' => $user->name,
                'phone' => $user->phone,
                'created_at' => $user->created_at?->toDateTimeString(),
                'last_activity' => $user->last_activity?->toDateTimeString(),
                'last_message' => $lastMessage?->message_text ?? '',
                'last_time' => $lastMessage?->created_at?->toDateTimeString(),
                'unread' => $unread,
            ];
        });

        return response()->json(['success' => true, 'users' => $result]);
    }

    public function getUserInfo(Request $request)
    {
        $userId = $request->input('user_id');
        $user = ChatUser::where('user_id', $userId)->first();

        if (! $user) {
            return response()->json(['success' => false, 'error' => 'User not found']);
        }

        return response()->json([
            'success' => true,
            'user' => [
                'user_id' => $user->user_id,
                'name' => $user->name,
                'phone' => $user->phone,
                'created_at' => $user->created_at?->toDateTimeString(),
                'last_activity' => $user->last_activity?->toDateTimeString(),
            ],
        ]);
    }

    public function getMessages(Request $request)
    {
        $userId = $request->input('user_id');
        $lastId = (int) $request->input('last_message_id', 0);
        $chatType = $request->input('chat_type', 'mini_chat');

        if (! $userId) {
            return response()->json(['success' => false, 'error' => 'user_id required']);
        }

        $query = Message::where('user_id', $userId)
            ->where('chat_type', $chatType);
            
        if ($lastId > 0) {
            $query->where('id', '>', $lastId);
        }
        $messages = $query->orderBy('created_at')->get();

        $formatted = [];
        foreach ($messages as $msg) {
            $html = null;
            if (! empty($msg->extra_data)) {
                $decoded = json_decode($msg->extra_data, true);
                if (is_array($decoded)) {
                    $html = $decoded['html'] ?? null;
                }
            }

            if ($msg->message_format === 'price_card' && empty($html)) {
                $catalogUrl = route('catalog.index');
                $html = '<div style="text-align:center;padding:12px 0;"><a href="'.$catalogUrl.'" target="_blank" style="display:inline-block;padding:10px 20px;background:linear-gradient(135deg,#4071CB 0%,#5A8DE8 100%);color:white;border-radius:10px;text-decoration:none;font-weight:600;font-size:13px;box-shadow:0 4px 12px rgba(64,113,203,0.3);">Посмотреть каталог →</a></div>';
            }

            $formatted[] = [
                'id' => $msg->id,
                'dir' => $msg->message_type === 'sent' ? 'received' : 'sent',
                'text' => $msg->message_text,
                'time' => $msg->created_at ? $msg->created_at->format('H:i') : '',
                'created_at' => $msg->created_at ? $msg->created_at->toDateTimeString() : '',
                'format' => $msg->message_format ?? 'text',
                'html' => $html,
            ];
        }

        return response()->json(['success' => true, 'messages' => $formatted]);
    }

   public function sendMessage(Request $request)
{
    $userId = $request->input('user_id');
    $text = $request->input('text');
    $chatType = $request->input('chat_type', 'mini_chat');

    if (! $userId || ! trim($text)) {
        return response()->json(['success' => false, 'error' => 'invalid data']);
    }

   $message = Message::create([
    'user_id'        => $userId,
    'message_text'   => trim($text),
    'message_type'   => 'received',
    'chat_type'      => $chatType,
    'car_config_id'  => $request->input('car_config_id'), 
]);

    $user = ChatUser::where('user_id', $userId)->first();
    if ($user) {
        $user->last_activity = now();
        $user->increment('message_count');
        $user->save();
    } else {
        ChatUser::where('user_id', $userId)->update(['last_activity' => now()]);
    }

    return response()->json([
        'success'     => true,
        'message_id'  => $message->id,
        'text'        => $message->message_text,
        'dir'         => 'sent', 
        'time'        => $message->created_at->format('H:i'),
        'created_at'  => $message->created_at->toDateTimeString(),
    ]);
}
    public function clearMessages(Request $request)
    {
        $userId = $request->input('user_id');
        $chatType = $request->input('chat_type', 'mini_chat');
        
        if (! $userId) {
            return response()->json(['success' => false, 'error' => 'no user_id']);
        }

        Message::where('user_id', $userId)
            ->where('chat_type', $chatType)
            ->delete();
            
        ChatUser::where('user_id', $userId)->update(['message_count' => 0]);

        return response()->json(['success' => true]);
    }

    public function deleteUser(Request $request)
    {
        $userId = $request->input('user_id');
        if (! $userId) {
            return response()->json(['success' => false, 'error' => 'no user_id']);
        }

        DB::transaction(function () use ($userId) {
            Message::where('user_id', $userId)->delete();
            ChatUser::where('user_id', $userId)->delete();
        });

        return response()->json(['success' => true]);
    }

    public function createUser(Request $request)
    {
        $name = trim($request->input('name'));
        $phone = trim($request->input('phone'));

        if (! $name) {
            return response()->json(['success' => false, 'error' => 'empty name']);
        }

        $userId = 'user_'.Str::random(13);
        ChatUser::create([
            'user_id' => $userId,
            'name' => $name,
            'phone' => $phone,
            'last_activity' => now(),
            'message_count' => 0,
        ]);

        return response()->json(['success' => true, 'user_id' => $userId]);
    }

    public function deleteMessage(Request $request)
    {
        $messageId = $request->input('message_id');
        if (! $messageId) {
            return response()->json(['success' => false, 'error' => 'no message_id']);
        }

        $message = Message::find($messageId);
        if (! $message) {
            return response()->json(['success' => false, 'error' => 'message not found']);
        }

        $userId = $message->user_id;
        $message->delete();

        $user = ChatUser::where('user_id', $userId)->first();
        if ($user) {
            $user->decrement('message_count');
        }

        return response()->json(['success' => true]);
    }
}