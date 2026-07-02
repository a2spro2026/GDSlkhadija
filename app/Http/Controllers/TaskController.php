<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Task::with(['assignee', 'creator']);

        if ($user->isTechnician()) {
            $query->where('assigned_to', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('technician') && $user->canManage()) {
            $query->where('assigned_to', $request->technician);
        }

        $tasks = $query->latest()->paginate(15);
        $technicians = $user->canManage()
            ? User::where('role', 'technician')->where('is_active', true)->orderBy('name')->get()
            : collect();

        return view('tasks.index', compact('tasks', 'technicians'));
    }

    public function create()
    {
        $technicians = User::where('role', 'technician')->where('is_active', true)->orderBy('name')->get();
        $products = Product::where('quantity', '>', 0)->orderBy('name')->get();

        return view('tasks.create', compact('technicians', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:basse,normale,haute,urgente',
            'assigned_to' => 'required|exists:users,id',
            'client_name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'due_date' => 'nullable|date',
            'materials' => 'nullable|array',
            'materials.*.product_id' => 'exists:products,id',
            'materials.*.quantity' => 'integer|min:1',
        ]);

        $task = Task::create([
            ...collect($validated)->except('materials')->toArray(),
            'created_by' => auth()->id(),
            'status' => 'en_attente',
        ]);

        if (! empty($validated['materials'])) {
            foreach ($validated['materials'] as $material) {
                if (! empty($material['product_id']) && ! empty($material['quantity'])) {
                    $task->materials()->create($material);
                }
            }
        }

        return redirect()->route('tasks.show', $task)->with('success', 'Tâche créée et assignée au technicien.');
    }

    public function show(Task $task)
    {
        $this->authorizeTask($task);
        $task->load(['assignee', 'creator', 'materials.product']);

        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        if (! auth()->user()->canManage()) {
            abort(403);
        }

        $technicians = User::where('role', 'technician')->where('is_active', true)->orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $task->load('materials');

        return view('tasks.edit', compact('task', 'technicians', 'products'));
    }

    public function update(Request $request, Task $task)
    {
        if (auth()->user()->canManage()) {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'priority' => 'required|in:basse,normale,haute,urgente',
                'status' => 'required|in:en_attente,en_cours,terminee,annulee',
                'assigned_to' => 'required|exists:users,id',
                'client_name' => 'nullable|string|max:255',
                'location' => 'nullable|string|max:255',
                'due_date' => 'nullable|date',
            ]);

            if ($validated['status'] === 'terminee' && ! $task->completed_at) {
                $validated['completed_at'] = now();
            }

            $task->update($validated);
        } else {
            $this->authorizeTask($task);

            $validated = $request->validate([
                'status' => 'required|in:en_cours,terminee',
                'technician_notes' => 'nullable|string',
            ]);

            if ($validated['status'] === 'terminee') {
                $validated['completed_at'] = now();
            }

            $task->update($validated);
        }

        return redirect()->route('tasks.show', $task)->with('success', 'Tâche mise à jour.');
    }

    public function destroy(Task $task)
    {
        if (! auth()->user()->canManage()) {
            abort(403);
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Tâche supprimée.');
    }

    private function authorizeTask(Task $task): void
    {
        $user = auth()->user();

        if ($user->canManage()) {
            return;
        }

        if ($user->isTechnician() && $task->assigned_to === $user->id) {
            return;
        }

        abort(403);
    }
}
