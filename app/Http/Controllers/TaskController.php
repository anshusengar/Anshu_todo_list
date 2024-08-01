<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all(); 
        return view('tasks.index', compact('tasks')); 
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|unique:tasks'
    ]);

    Task::create([
        'name' => $request->name,
        'status' => 'Pending'
    ]);

    return redirect()->route('tasks.index')->with('success', 'Task added successfully!');
}

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->completed = !$task->completed;
        $task->status = $task->completed ? 'Completed' : 'Pending';
        $task->save();

        return redirect()->back();
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return redirect()->back()->with('success', 'Task deleted successfully!');
    }
}