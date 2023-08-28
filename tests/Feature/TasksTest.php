<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TasksTest extends TestCase
{

    use RefreshDatabase;

    public function test_can_create_task(): void
    {
        /*login*/
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = ['name' => 'Task', 'priority' => 'height'];
        $response = $this->post('/tasks', $request);
        $response->assertExactJson(['message' => 'Task created']);
        $task = Task::where('name', 'Task')->first();
        $this->assertEquals($request['name'], $task->name);
        $response->assertStatus(201);
    }

    public function test_cant_create_task_permission_denied() : void {
        /*the column priority can be a table call priorities in the request can us send the id of the priority*/
        /*priorities ['height', 'middle', 'low']*/
        $response = $this->postJson('/tasks', ['name' => 'Task', 'priority' => 'height', 'state' => 'progress']);
        $response->assertStatus(401);
    }

    public function test_change_state_of_task() : void {
         /*login*/
        $user = User::factory()->create();
        $this->actingAs($user);

        $task = Task::factory()->create();

        /*the column state can be a table call states in the request can us send the id of the state*/
        /*states ['in progress', 'in test', 'finish']*/
        $request = ['state' => 'finish', 'id' => $task->id];

        $response = $this->postJson("/tasks/state", $request);

        $response->assertExactJson(['message' => 'Task update']);
        $response->assertStatus(200);

        $task = Task::find($task->id);
        $this->assertEquals($request['state'], $task->state);
    }

    public function test_get_tasks() : void {
        /*login*/
        $user = User::factory()->create();
        $this->actingAs($user);
        Task::factory()->count(10)->create();
        $response = $this->get('/tasks');
        $tasks = Task::all();
        $response->assertJson(['tasks' => $tasks->toArray()]);
        $response->assertStatus(200);
    }

    public function test_filter_tasks() : void {
         /*login*/
        $user = User::factory()->create();
        $this->actingAs($user);

        $tasks = Task::factory()->count(10)->create();

        $response = $this->get('/tasks?state=progress');
        $tasks = Task::where('state', 'progress')->get();

        $response->assertJson(['tasks' => $tasks->toArray()]);
        $response->assertStatus(200);
    }

    public function test_filter_with_state_in_null() : void {
        /*login*/
        $user = User::factory()->create();
        $this->actingAs($user);

        $tasks = Task::factory()->count(10)->create();

        $response = $this->get('/tasks');
        $tasks = Task::all();

        $response->assertJson(['tasks' => $tasks->toArray()]);
        $response->assertStatus(200);
    }


    public function test_sort_tasks_by_due_date() : void {
        /*login*/
        $user = User::factory()->create();
        $this->actingAs($user);

        $tasks = Task::factory()->count(10)->create();
        $tasks->orderBy('due_date');

        $response = $this->get('/tasks');
        $tasks = Task::all();

        $response->assertJson(['tasks' => $tasks->toArray()]);
        $response->assertStatus(200);
    }

}
