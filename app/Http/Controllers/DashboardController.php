<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->canManage()) {
            return $this->adminDashboard();
        }

        return $this->technicianDashboard();
    }

    private function adminDashboard()
    {
        return view('dashboard.admin');
    }

    private function technicianDashboard()
    {
        $user = auth()->user();

        $myTasks = Task::where('assigned_to', $user->id)
            ->whereIn('status', ['en_attente', 'en_cours'])
            ->orderByRaw("FIELD(priority, 'urgente', 'haute', 'normale', 'basse')")
            ->orderBy('due_date')
            ->get();

        $completedThisMonth = Task::where('assigned_to', $user->id)
            ->where('status', 'terminee')
            ->whereMonth('completed_at', now()->month)
            ->count();

        $stats = [
            'pending' => $myTasks->where('status', 'en_attente')->count(),
            'in_progress' => $myTasks->where('status', 'en_cours')->count(),
            'completed_month' => $completedThisMonth,
        ];

        return view('dashboard.technician', compact('myTasks', 'stats'));
    }
}
