<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::orderByDesc('created_at')->get();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function toggle(Review $review)
    {
        if ($review->status === 'approved') {
            $review->status = 'pending';
        } else {
            $review->status = 'approved';
        }

        $review->save();

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Статус отзыва обновлён');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Отзыв удалён');
    }

    public function publishAll()
    {
        Review::where('status', 'pending')
            ->update(['status' => 'approved']);

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Все отзывы опубликованы');
    }
}