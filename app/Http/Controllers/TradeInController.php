<?php

namespace App\Http\Controllers;

use App\Models\TradeInRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class TradeInController extends Controller
{
    public function index()
    {
        return view('pages.trade-in');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[а-яА-ЯёЁa-zA-Z\s\-]+$/u'
            ],
            'phone' => [
                'required',
                'string',
                'regex:/^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/'
            ],
           'dealer_center' => [
    'required',
    'string',
    Rule::in(['г. Казань, ул. Ямашева, д. 76', 'г. Казань, ул. Чистопольская, д. 9а'])
],
        ], [
            'name.required' => 'Поле "Ваше имя" обязательно для заполнения',
            'name.min' => 'Имя должно содержать минимум 2 символа',
            'name.max' => 'Имя не должно превышать 50 символов',
            'name.regex' => 'Имя должно содержать только буквы, пробелы и дефисы',
            'phone.required' => 'Поле "Номер телефона" обязательно для заполнения',
            'phone.regex' => 'Введите корректный номер телефона в формате: +7 (XXX) XXX-XX-XX',
            'dealer_center.required' => 'Пожалуйста, выберите дилерский центр',
            'dealer_center.in' => 'Выбран недопустимый дилерский центр',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            TradeInRequest::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'dealer_center' => $request->dealer_center,
            ]);

            return redirect()->route('trade-in')->with('success', true);
        } catch (\Exception $e) {
            Log::error('Trade-in store error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Произошла ошибка при отправке заявки. Пожалуйста, попробуйте позже.')
                ->withInput();
        }
    }
}