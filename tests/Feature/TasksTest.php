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

        $request = ['name' => 'Task', 'priority' => 'Alta'];
        $response = $this->postJson('/tasks', $request);
        $response->assertExactJson(['message' => 'Task created']);
        $task = Task::where('name', 'Task')->first();
        $this->assertEquals($request['name'], $task->name);
        $response->assertStatus(201);
    }

    public function test_cant_create_task_permission_denied() : void {
        $response = $this->postJson('/tasks', ['name' => 'Task', 'priority' => 'Alta']);
        $response->assertStatus(401);
    }

    public function test_get_tasks() : void {
        /*login*/
        $user = User::factory()->create();
        $this->actingAs($user);
        Task::factory()->count(10)->create();
        $response = $this->getJson('/tasks');
        $tasks = Task::all();
        $response->assertJson(['tasks' => $tasks->toArray()]);
        $response->assertStatus(200);
    }
}
