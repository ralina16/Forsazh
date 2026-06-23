<?php

namespace App\Http\Controllers;

use App\Models\CreditRequest;
use App\Models\InsuranceRequest;
use App\Models\RequestModel;
use App\Models\TradeInRequest;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RequestController extends Controller
{
    public function store(HttpRequest $request)
    {
        $validated = $request->validate([
            'request_type' => 'required|array|min:1',
            'request_type.*' => 'in:test-drive,consultation,car-selection,credit',
            'name' => 'required|string|min:2|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'agree' => 'accepted',
        ]);

        try {
            RequestModel::create([
                'request_type' => implode(',', $validated['request_type']),
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'] ?? null,
                'agree' => $request->boolean('agree'),
                'status' => 'new',
            ]);

            return redirect()->back()->with('success', 'Заявка успешно отправлена!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ошибка при отправке: '.$e->getMessage());
        }
    }

    public function adminIndex()
    {
        $tradeInRequests = TradeInRequest::orderBy('created_at', 'desc')->get();
        $creditRequests = CreditRequest::orderBy('created_at', 'desc')->get();
        $insuranceRequests = InsuranceRequest::orderBy('created_at', 'desc')->get();
        $generalRequests = RequestModel::orderBy('created_at', 'desc')->get();

        return view('admin.requests.index', compact(
            'tradeInRequests',
            'creditRequests',
            'insuranceRequests',
            'generalRequests'
        ));
    }

    public function storeTradeIn(HttpRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'dealer_center' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->handleValidationFailure($validator, $request, 'trade_in');
        }

        try {
            TradeInRequest::create($validator->validated());

            return $this->handleSuccess($request, 'Заявка Trade-in добавлена');
        } catch (\Exception $e) {
            return $this->handleException($e, $request);
        }
    }

    public function updateTradeIn(HttpRequest $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'dealer_center' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->handleValidationFailure($validator, $request, 'trade_in', $id);
        }

        try {
            $tradeIn = TradeInRequest::findOrFail($id);
            $tradeIn->update($validator->validated());

            return $this->handleSuccess($request, 'Заявка Trade-in обновлена');
        } catch (\Exception $e) {
            return $this->handleException($e, $request);
        }
    }

    public function destroyTradeIn($id)
    {
        TradeInRequest::findOrFail($id)->delete();

        return redirect()->route('admin.requests.index')->with('success', 'Заявка Trade-in удалена');
    }

    public function storeCredit(HttpRequest $request)
    {
        $request->merge([
            'credit_amount' => preg_replace('/\s+/', '', $request->credit_amount),
            'monthly_payment' => preg_replace('/\s+/', '', $request->monthly_payment),
        ]);

        $validator = Validator::make($request->all(), [
            'fio' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'car_type' => 'required|in:new,used',
            'credit_amount' => 'required|numeric|min:0',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'loan_term' => 'required|integer|min:1|max:30',
            'monthly_payment' => 'required|numeric|min:0',
            'insurance_kasko' => 'nullable|boolean',
            'insurance_as_z' => 'nullable|boolean',
            'early_repayment' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->handleValidationFailure($validator, $request, 'credit');
        }

        $validated = $validator->validated();

        try {
            $credit = CreditRequest::create([
                'fio' => $validated['fio'],
                'phone' => $validated['phone'],
                'car_type' => $validated['car_type'],
                'credit_amount' => $validated['credit_amount'],
                'interest_rate' => $validated['interest_rate'],
                'loan_term' => $validated['loan_term'],
                'monthly_payment' => $validated['monthly_payment'],
                'insurance_kasko' => $request->boolean('insurance_kasko'),
                'insurance_as_z' => $request->boolean('insurance_as_z'),
                'early_repayment' => $request->boolean('early_repayment'),
                'notes' => $validated['notes'] ?? null,
                'consent' => true,
            ]);

            return $this->handleSuccess($request, 'Заявка на автокредит добавлена');
        } catch (\Exception $e) {
            return $this->handleException($e, $request);
        }
    }

    public function updateCredit(HttpRequest $request, $id)
    {
        $request->merge([
            'credit_amount' => preg_replace('/\s+/', '', $request->credit_amount),
            'monthly_payment' => preg_replace('/\s+/', '', $request->monthly_payment),
        ]);

        $validator = Validator::make($request->all(), [
            'fio' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'car_type' => 'required|in:new,used',
            'credit_amount' => 'required|numeric|min:0',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'loan_term' => 'required|integer|min:1|max:30',
            'monthly_payment' => 'required|numeric|min:0',
            'insurance_kasko' => 'nullable|boolean',
            'insurance_as_z' => 'nullable|boolean',
            'early_repayment' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->handleValidationFailure($validator, $request, 'credit', $id);
        }

        $validated = $validator->validated();

        try {
            $credit = CreditRequest::findOrFail($id);
            $credit->update([
                'fio' => $validated['fio'],
                'phone' => $validated['phone'],
                'car_type' => $validated['car_type'],
                'credit_amount' => $validated['credit_amount'],
                'interest_rate' => $validated['interest_rate'],
                'loan_term' => $validated['loan_term'],
                'monthly_payment' => $validated['monthly_payment'],
                'insurance_kasko' => $request->boolean('insurance_kasko'),
                'insurance_as_z' => $request->boolean('insurance_as_z'),
                'early_repayment' => $request->boolean('early_repayment'),
                'notes' => $validated['notes'] ?? null,
            ]);

            return $this->handleSuccess($request, 'Заявка на автокредит обновлена');
        } catch (\Exception $e) {
            return $this->handleException($e, $request);
        }
    }

    public function destroyCredit($id)
    {
        CreditRequest::findOrFail($id)->delete();

        return redirect()->route('admin.requests.index')->with('success', 'Заявка на автокредит удалена');
    }

    public function storeInsurance(HttpRequest $request)
    {
        $request->merge([
            'car_price' => preg_replace('/\s+/', '', $request->car_price),
            'estimated_premium' => preg_replace('/\s+/', '', $request->estimated_premium),
            'monthly_payment' => preg_replace('/\s+/', '', $request->monthly_payment),
        ]);

        $validator = Validator::make($request->all(), [
            'fio' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'insurance_type' => 'required|in:osago,kasko',
            'car_price' => 'required|numeric|min:0',
            'car_age' => 'required|string',
            'estimated_premium' => 'required|numeric|min:0',
            'monthly_payment' => 'required|numeric|min:0',
            'risk_level' => 'required|in:низкий,средний,высокий',
        ]);

        if ($validator->fails()) {
            return $this->handleValidationFailure($validator, $request, 'insurance');
        }

        $validated = $validator->validated();

        try {
            $insurance = InsuranceRequest::create([
                'fio' => $validated['fio'],
                'phone' => $validated['phone'],
                'insurance_type' => $validated['insurance_type'],
                'car_price' => $validated['car_price'],
                'car_age' => $validated['car_age'],
                'estimated_premium' => $validated['estimated_premium'],
                'monthly_payment' => $validated['monthly_payment'],
                'risk_level' => $validated['risk_level'],
            ]);

            return $this->handleSuccess($request, 'Заявка на автострахование добавлена');
        } catch (\Exception $e) {
            return $this->handleException($e, $request);
        }
    }

    public function updateInsurance(HttpRequest $request, $id)
    {
        $request->merge([
            'car_price' => preg_replace('/\s+/', '', $request->car_price),
            'estimated_premium' => preg_replace('/\s+/', '', $request->estimated_premium),
            'monthly_payment' => preg_replace('/\s+/', '', $request->monthly_payment),
        ]);

        $validator = Validator::make($request->all(), [
            'fio' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'insurance_type' => 'required|in:osago,kasko',
            'car_price' => 'required|numeric|min:0',
            'car_age' => 'required|string',
            'estimated_premium' => 'required|numeric|min:0',
            'monthly_payment' => 'required|numeric|min:0',
            'risk_level' => 'required|in:низкий,средний,высокий',
        ]);

        if ($validator->fails()) {
            return $this->handleValidationFailure($validator, $request, 'insurance', $id);
        }

        $validated = $validator->validated();

        try {
            $insurance = InsuranceRequest::findOrFail($id);
            $insurance->update([
                'fio' => $validated['fio'],
                'phone' => $validated['phone'],
                'insurance_type' => $validated['insurance_type'],
                'car_price' => $validated['car_price'],
                'car_age' => $validated['car_age'],
                'estimated_premium' => $validated['estimated_premium'],
                'monthly_payment' => $validated['monthly_payment'],
                'risk_level' => $validated['risk_level'],
            ]);

            return $this->handleSuccess($request, 'Заявка на автострахование обновлена');
        } catch (\Exception $e) {
            return $this->handleException($e, $request);
        }
    }

    public function destroyInsurance($id)
    {
        InsuranceRequest::findOrFail($id)->delete();

        return redirect()->route('admin.requests.index')->with('success', 'Заявка на автострахование удалена');
    }

    public function storeGeneralAdmin(HttpRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'request_type' => 'required|array|min:1',
            'request_type.*' => 'in:test-drive,consultation,car-selection,service',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'agree' => 'accepted',
        ]);

        if ($validator->fails()) {
            return $this->handleValidationFailure($validator, $request, 'general');
        }

        $validated = $validator->validated();

        try {
            RequestModel::create([
                'request_type' => implode(',', $validated['request_type']),
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'] ?? null,
                'agree' => $request->boolean('agree'),
                'status' => 'new',
            ]);

            return $this->handleSuccess($request, 'Заявка добавлена');
        } catch (\Exception $e) {
            return $this->handleException($e, $request);
        }
    }

    public function updateGeneralAdmin(HttpRequest $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'request_type' => 'required|array|min:1',
            'request_type.*' => 'in:test-drive,consultation,car-selection,service',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'agree' => 'accepted',
            'status' => 'required|in:new,processed,completed',
        ]);

        if ($validator->fails()) {
            return $this->handleValidationFailure($validator, $request, 'general', $id);
        }

        $validated = $validator->validated();

        try {
            $general = RequestModel::findOrFail($id);
            $general->update([
                'request_type' => implode(',', $validated['request_type']),
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'] ?? null,
                'agree' => $request->boolean('agree'),
                'status' => $validated['status'],
            ]);

            return $this->handleSuccess($request, 'Заявка обновлена');
        } catch (\Exception $e) {
            return $this->handleException($e, $request);
        }
    }

    public function destroyGeneralAdmin($id)
    {
        RequestModel::findOrFail($id)->delete();

        return redirect()->route('admin.requests.index')->with('success', 'Заявка удалена');
    }

    private function handleValidationFailure($validator, $request, $bag = 'default', $editId = null)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return redirect()->back()
            ->withErrors($validator, $bag)
            ->withInput()
            ->with($editId ? ['edit_id' => $editId, 'edit_type' => $bag] : []);
    }

    private function handleSuccess($request, $message)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->route('admin.requests.index')->with('success', $message);
    }

    private function handleException(\Exception $e, $request)
    {
        Log::error($e->getMessage());
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['error' => 'Ошибка сервера: '.$e->getMessage()], 500);
        }

        return redirect()->back()->with('error', 'Ошибка: '.$e->getMessage())->withInput();
    }
}
