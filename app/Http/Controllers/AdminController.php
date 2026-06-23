<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Message;
use App\Models\RequestModel;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'cars_count' => Car::count(),
            'users_count' => User::where('role', '!=', 'admin')->count(),
            'requests_count' => RequestModel::count(),
            'new_requests' => RequestModel::where('status', 'new')->count(),
        ];

        $chartData = $this->getRequestsChartData();

        $chatChart = $this->getChatChartData();

        return view('admin.index', compact('stats', 'chartData', 'chatChart'));
    }

    private function getRequestsChartData(): array
    {
        $months = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
        $currentYear = now()->year;

        $data = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i)->month;
            $year = now()->subMonths($i)->year;
            $labels[] = $months[$month - 1];

            $count = RequestModel::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();
            $data[] = $count;
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    private function getChatChartData(): array
    {
        $months = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];

        $labels = [];
        $userMessages = [];
        $aiResponses = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->month;
            $year = $date->year;
            $labels[] = $months[$month - 1];

            $userMessages[] = Message::where('chat_type', 'mini_chat')
                ->where('message_type', 'sent')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();

            $aiResponses[] = Message::where('chat_type', 'mini_chat')
                ->where('message_type', 'received')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();
        }

        return [
            'labels' => $labels,
            'userMessages' => $userMessages,
            'aiResponses' => $aiResponses,
        ];
    }

    public function users()
    {
        $users = User::where('role', '!=', 'admin')->get();

        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:user,admin',
        ]);

        $user->role = $request->role;
        $user->save();

        return redirect()->route('admin.users')->with('success', 'Роль пользователя обновлена.');
    }

    public function requests()
    {
        $requests = RequestModel::orderBy('created_at', 'desc')->get();

        return view('admin.requests.index', compact('requests'));
    }

    public function updateRequestStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:new,processed,completed',
        ]);

        $userRequest = RequestModel::findOrFail($id);
        $userRequest->status = $request->status;
        $userRequest->save();

        return redirect()->route('admin.requests')->with('success', 'Статус заявки обновлён.');
    }
}
