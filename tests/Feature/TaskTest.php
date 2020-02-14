<?php

namespace Tests\Feature;

use App\Task;
use App\User;
use Tests\TestCase;

class TaskTest extends TestCase
{
    public function testIndexAvailable()
    {
        $user_id = 1;
        $response = $this->actingAs(User::find($user_id))->get('/api/tasks');
        $response->assertStatus(200);
    }

    public function testIndexShowListByUserEmpty()
    {
        $user_id = 3;
        $response = $this->actingAs(User::find($user_id))->get('/api/tasks');
        $response->assertStatus(200);
        $response->assertJson([]);
    }

    public function testIndexShowListByUser()
    {
        $user_id = 2;
        $tasks = Task::where(['user_id' => $user_id])->get()->toArray();
        $response = $this->actingAs(User::find($user_id))->get('/api/tasks');
        $response->assertStatus(200);
        $response->assertJson($tasks);
    }

    public function testIndexShowListByProject()
    {
        $user_id = 1;
        $project_id = 1;

        $response = $this->actingAs(User::find($user_id))->get("/api/tasks?project_id=$project_id");
        $response->assertStatus(200);
        $response->assertJson([
            [
                'user_id' => $user_id,
                'project_id' => $project_id
            ]
        ]);
    }

    public function testIndexDenied()
    {
        $user_id = 1;
        $project_id = 4;

        $response = $this->actingAs(User::find($user_id))->get("/api/tasks?project_id=$project_id");
        $response->assertStatus(400);

    }

    public function testIndexShowListByDate()
    {
        $date = '2020-02-11 00:00:00';
        $user_id = 2;

        $response = $this->actingAs(User::find($user_id))->get("/api/tasks?date=" . urlencode($date));
        $response->assertStatus(200);
        $response->assertJson([
                [
                    'user_id' => $user_id,
                    'deadline_date' => $date
                ]
            ]
        );
    }

    public function testIndexShowListByMonth()
    {
        $date = '2020-02-11 00:00:00';
        $user_id = 3;
        $response = $this->actingAs(User::find($user_id))->get("/api/tasks?date_month=" . urlencode($date));
        $response->assertStatus(200);
        $response->assertJson([
            ['deadline_date' => '2020-02-01 00:00:00'],
            ['deadline_date' => '2020-03-01 00:00:00']
        ]);
    }

    public function testShowTaskById()
    {
        $user_id = 1;
        $task_id = 1;
        $task = Task::find($task_id)->toArray();
        $response = $this->actingAs(User::find($user_id))->get("/api/tasks/$task_id");
        $response->assertStatus(200);
        $response->assertJson($task);
    }

    public function testShowTaskByIdNotFound()
    {
        $user_id = 1;
        $task_id = 1000;
        $response = $this->actingAs(User::find($user_id))->get("/api/tasks/$task_id");
        $response->assertStatus(404);
        $response->assertSee('Task not found.');
    }

    public function testStoreTask()
    {
        $user_id = 1;
        $response = $this->actingAs(User::find($user_id))->post('/api/tasks', [
            'title' => 'Test 2'
        ]);

        $data = $response->json();
        $task = Task::find($data['id']);
        $response->assertStatus(201);
        $this->assertNotNull($task);
        $this->assertEquals($user_id, $data['user_id']);
    }

    public function testStoreTaskInProject() {
        $user_id = 1;
        $project_id = 2;
        $response = $this->actingAs(User::find($user_id))->post("/api/tasks", [
            'title' => 'Test 2',
            'project_id' => $project_id
        ]);

        $data = $response->json();
        $task = Task::find($data['id']);
        $response->assertStatus(201);
        $this->assertNotNull($task);
        $this->assertEquals($user_id, $data['user_id']);
        $this->assertEquals($project_id, $data['project_id']);
    }

    public function testUpdateTask()
    {
        $user_id = 1;
        $task_id = 1;
        $response = $this->actingAs(User::find($user_id))->put("/api/tasks/$task_id", [
            'title' => 'Test 2 updated',
        ]);

        $task = Task::find($task_id)->toArray();
        $response->assertStatus(200);
        $response->assertSee('The task was updated');
        $this->assertEquals('Test 2 updated', $task['title']);
    }

    public function testUpdateTaskFailed()
    {
        $user_id = 1;
        $task_id = 20;
        $response = $this->actingAs(User::find($user_id))->put("/api/tasks/$task_id", [
            'title' => 'Test 2 updated',
        ]);

        $response->assertStatus(404);
        $response->assertSee('Task not found.');
    }

    public function testTaskCheck()
    {
        $user_id = 1;
        $task_id = 2;
        $task1 = Task::find($task_id);
        $response = $this->actingAs(User::find($user_id))->post("/api/tasks/$task_id/check");
        $task2 = Task::find($task_id);
        $response->assertStatus(200);
        $response->assertSee('The task checked.');
        $this->assertNotEquals($task1->is_done, $task2->is_done);
    }

    public function testTaskCheckFailed()
    {
        $user_id = 1;
        $task_id = 1000;
        $response = $this->actingAs(User::find($user_id))->post("/api/tasks/$task_id/check");
        $response->assertStatus(404);
        $response->assertSee('Task not found.');
    }

    public function testTaskDestroy()
    {
        $user_id = 1;
        $task_id = 1;

        $response = $this->actingAs(User::find($user_id))->delete("/api/tasks/$task_id");
        $response->assertStatus(200);
        $response->assertSee('The task was deleted.');
        $this->assertNull(Task::find($task_id));
    }

    public function testTaskDestroyFailed()
    {
        $user_id = 1;
        $task_id = 1000;

        $response = $this->actingAs(User::find($user_id))->delete("/api/tasks/$task_id");
        $response->assertStatus(404);
        $response->assertSee('Task not found.');
    }
}
