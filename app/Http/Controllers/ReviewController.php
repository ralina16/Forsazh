<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Для оставления отзыва необходимо войти в аккаунт');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:500',
            'agree' => 'accepted',
        ], [
            'rating.required' => 'Выберите оценку',
            'rating.min' => 'Оценка должна быть от 1 до 5',
            'comment.required' => 'Введите текст отзыва',
            'comment.min' => 'Отзыв должен содержать минимум 10 символов',
            'agree.accepted' => 'Необходимо согласие с политикой конфиденциальности',
        ]);

        Review::create([
            'user_name' => Auth::user()->name, 
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Отзыв успешно отправлен на модерацию!');
    }
}