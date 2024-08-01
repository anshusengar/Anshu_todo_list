<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        return Task::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'task' => 'required|string|unique:tasks,task',
        ]);

        $task = Task::create([
            'task' => $request->task,
        ]);

        return response()->json($task);
    }

    public function update(Request $request, Task $task)
    {
        $task->completed = $request->completed;
        $task->save();

        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json(['message' => 'Task deleted']);
    }
}