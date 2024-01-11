<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(): JsonResponse
    {
        $tasks = Task::all();
        return response()->json($tasks, 200);
    }

    public function show(Request $request, $id): JsonResponse
    {
        $task = Task::find($id);

        if(!empty($task)) {
            return response()->json($task);
        }

        return response()->json([
                "message" => "Task not found"
            ],404);
    }

    public function store(Request $request): JsonResponse
    {
        $this->validate(request(), [
            'name' => 'required | max:45',
            'description' => 'required | max:255',
            'due_date' => 'required',
        ]);

        $postData = $request->post();
        if ($postData === null) {
            return response()->json("Invalid JSON data.", 400);
        }

        if ($this->areFieldsSet($request, ['name', 'description', 'due_date'])) {
        }
            // Get request data
            $task = new Task;
            $task->name = $request->name;
            $task->description = $request->description;
            $task->due_date = $request->due_date;
            $task->save();

        return response()->json([
            "message" => "Book added"
        ], 200);
    }

    public function update (Request $request, $id)
    {
        if (Task::where('task_id', $id)->exists()) {
            $task = Task::find($id);

            $task->update($request->all());

            return response()->json([
                "data" => $task,
                "message" => "Task updated successfully"
            ], 200);
        }
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        if(Task::where('task_id', $id)->exists()) {
            $task = Task::find($id);
            $task->delete();

            return response()->json([
                "message" => "Task deleted"
            ],202);
        }

        return response()->json([
            "message" => "Task not found"
        ],404);
    }

    private function areFieldsSet(Request $request, Array $fields)
    {
        foreach ($fields as $field) {
            if (!$request->has($field)) {
                return false;
            }
        }

        return true;
    }
}
