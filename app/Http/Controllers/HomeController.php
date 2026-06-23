<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $hotCars = Car::orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        $reviews = Review::where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('home.index', compact('hotCars', 'reviews'));
    }
}