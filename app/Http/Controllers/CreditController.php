<?php

namespace App\Http\Controllers;

use App\Models\CreditRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CreditController extends Controller
{
    public function index()
    {
        return view('pages.credit');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fio' => 'required|string|max:50',
            'phone' => 'required|string|min:10',
            'car_type' => 'required|in:new,used',
            'credit_amount' => 'required|numeric|min:100000|max:20000000',
            'interest_rate' => 'required|numeric|min:1|max:50',
            'loan_term' => 'required|integer|min:1|max:7',
            'monthly_payment' => 'required|numeric|min:0',
            'insurance_kasko' => 'nullable|boolean',
            'insurance_as_z' => 'nullable|boolean',
            'early_repayment' => 'nullable|boolean',
            'notes' => 'nullable|string|max:500',
            'consent' => 'accepted',
        ], [
            'fio.required' => 'Поле ФИО обязательно для заполнения',
            'fio.regex' => 'ФИО должно содержать только русские буквы, пробелы и дефисы',
            'phone.required' => 'Поле телефона обязательно для заполнения',
            'phone.min' => 'Введите корректный номер телефона',
            'consent.accepted' => 'Необходимо дать согласие на обработку персональных данных',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        CreditRequest::create([
            'fio' => $request->fio,
            'phone' => $request->phone,
            'car_type' => $request->car_type,
            'credit_amount' => $request->credit_amount,
            'interest_rate' => $request->interest_rate,
            'loan_term' => $request->loan_term,
            'monthly_payment' => $request->monthly_payment,
            'insurance_kasko' => $request->boolean('insurance_kasko'),
            'insurance_as_z' => $request->boolean('insurance_as_z'),
            'early_repayment' => $request->boolean('early_repayment'),
            'notes' => $request->notes,
            'consent' => true,
        ]);

        return redirect()->route('credit')->with('success', true);
    }
}