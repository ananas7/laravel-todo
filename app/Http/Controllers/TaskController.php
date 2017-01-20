<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;

class TaskController extends Controller {
    public function index() {
        $tasks = Task::all();
        return view('tasks', [
            'tasks' => $tasks
        ]);
    }
    public function save(Request $request) {
        $this->validate($request, [
            'task' => 'required|max:255',
        ]);
        $task = Task::create([
            'task' => $request->task,
            'status' => false
        ]);
        echo $task;

    }
    public function destroy(Request $request, Task $task) {
        $task->delete();
        echo $task;
    }
    public function updateTask(Request $request, Task $task) {
        $task->update(['status' => !$task->status]);
        echo $task;
    }
    public function updateText(Request $request, Task $task) {
        $task->update(['task' => $request->task]);
        echo $task;
    }
}
