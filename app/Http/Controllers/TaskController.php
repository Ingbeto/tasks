<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeStateTaskRequest;
use App\Http\Requests\TaskStoreRequest;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) : JsonResponse
    {
        $query = Task::query();
        $query->when($request->state, function (Builder $query) use ($request) {
            $query->where('state', $request->state);
        });
        $tasks = $query->get();
        return response()->json(['tasks' => $tasks],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function updateState(ChangeStateTaskRequest $request) : JsonResponse {
        $task = Task::find($request->id);
        $task->state = $request->state;
        $task->save();
        return response()->json(['message' => 'Task update'], 200);
    }

    public function store(TaskStoreRequest $request) : JsonResponse
    {
        $task = new Task($request->only('name', 'priority', 'due_date'));
        $task->state = 'progress';
        $task->due_date = Carbon::now()->add(15, 'day')->toDateTime();
        $task->save();
        return response()->json(['message' => 'Task created'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}
