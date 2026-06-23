<?php

namespace App\Http\Controllers;

use App\Models\InsuranceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class InsuranceController extends Controller
{
    public function index()
    {
        return view('pages.insurance');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fio' => 'required|string|max:255',
            'phone' => 'required|string|min:10',
            'insurance_type' => 'required|in:osago,kasko',
            'car_price' => 'required|numeric|min:100000|max:20000000',
            'car_age' => 'required|string|in:1-3 года,3-5 лет,5-9 лет,10+ лет',
            'estimated_premium' => 'required|numeric|min:0',
            'monthly_payment' => 'required|numeric|min:0',
            'risk_level' => 'required|string|in:низкий,средний,высокий',
            'consent' => 'accepted',
        ], [
            'fio.required' => 'Поле ФИО обязательно для заполнения',
            'phone.required' => 'Поле телефона обязательно для заполнения',
            'phone.min' => 'Введите корректный номер телефона',
            'insurance_type.required' => 'Не выбран тип страхования',
            'car_price.required' => 'Не указана стоимость автомобиля',
            'car_age.required' => 'Не указан возраст автомобиля',
            'consent.accepted' => 'Необходимо дать согласие на обработку персональных данных',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        InsuranceRequest::create([
            'fio' => $request->fio,
            'phone' => $request->phone,
            'insurance_type' => $request->insurance_type,
            'car_price' => $request->car_price,
            'car_price' => $request->car_price,
            'car_age' => $request->car_age,
            'estimated_premium' => $request->estimated_premium,
            'monthly_payment' => $request->monthly_payment,
            'risk_level' => $request->risk_level,
            'consent' => $request->boolean('consent'),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('insurance')->with('success', true);
    }
}