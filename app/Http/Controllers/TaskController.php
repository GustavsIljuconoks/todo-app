<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Laravel\Sanctum\PersonalAccessToken;
class TaskController extends Controller
{
    private $user;

    public function index(Request $request): JsonResponse
    {
        $token = PersonalAccessToken::findToken($request->userToken);
        $this->user = $token->tokenable_id;

        $tasks = Task::where('user_id', $this->user)
            ->orderBy('created_at', 'desc')
            ->get();
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

    public function store(Request $request)
    {
        $token = PersonalAccessToken::findToken($request->userToken);
        $this->user = $token->tokenable_id;

        $validatedData = $this->validate(request(), [
            'name' => 'required | max:45',
            'description' => 'required | max:255',
            'due_date' => 'required',
        ]);

        $postData = $request->post();
        if ($postData === null) {
            return response()->json("Invalid JSON data.", 400);
        }

        // Get request data
        $task = new Task;
        $task->name = $validatedData['name'];
        $task->description = $validatedData['description'];
        $task->due_date = $validatedData['due_date'];
        $task->user_id = $this->user;
        $task->save();

        return response()->json([
            "message" => "Task added"
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
                "success" => "Todo deleted succesfully"
            ],200);
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
