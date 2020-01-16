<?php

namespace Tests\Feature;

use App\Project;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testShowProjects()
    {
        $response = $this->actingAs(User::find(1))->get('/api/projects');
        $response->assertJsonStructure([
            [
                'id',
                'name',
                'description',
                'budget',
                'user_id',
                'deadline_date',
                'finished_date',
                'created_at',
                'updated_at',
                'percent'
            ]
        ]);
        $response->assertStatus(200);
    }

    public function testShowProjectsWithPercent()
    {
        $response = $this->actingAs(User::find(1))->get('/api/projects?tasks=1');

        $response->assertJson([
            [
                'id' => 1,
                'percent' => 20
            ]
        ]);
        $response->assertStatus(200);
    }

    public function testShowProject()
    {
        $response = $this->actingAs(User::find(1))->get('/api/projects/1');
        $response->assertJson([
            'id' => 1
        ]);
        $response->assertStatus(200);
    }

    public function testShowProjectNotFound()
    {
        $response = $this->actingAs(User::find(1))->get('/api/projects/10');
        $response->assertStatus(404);
    }

    public function testStoreProject()
    {
        $response = $this->actingAs(User::find(1))->post('/api/projects', ['name' => 'test 1']);
        $data = $response->json();
        $project = Project::find($data['id']);

        $response->assertStatus(201);
        $this->assertNotNull($project);
    }

    public function testStoreProjectFailed()
    {
        $response = $this->actingAs(User::find(1))->post('/api/projects', ['name' => '']);
        $response->assertStatus(400);
    }

    public function testUpdateProject()
    {
        $response = $this->actingAs(User::find(1))->put('/api/projects/1', ['name' => 'newTest']);
        $project = Project::find(1);

        $this->assertEquals('newTest', $project->name);
        $response->assertStatus(200);

    }

    public function testUpdateProjectNotFound()
    {
        $response = $this->actingAs(User::find(1))->put('/api/projects/10', ['name' => 'newTest']);
        $response->assertStatus(404);
    }

    public function testDeleteProject()
    {
        $response = $this->actingAs(User::find(1))->delete('/api/projects/1');
        $project = Project::find(1);

        $this->assertNull($project);
        $response->assertStatus(200);
    }

    public function testDeleteProjectNotFound()
    {
        $response = $this->actingAs(User::find(1))->delete('/api/projects/10');
        $response->assertStatus(404);
    }

    public function testShowAllTheProjects()
    {
        $response = $this->actingAs(User::find(1))->get('/api/projects/all');
        $response->assertJsonStructure([
            [
                'tasks'
            ]
        ]);
        $response->assertStatus(200);
    }

    public function testShowProjectWithModels()
    {
        $response = $this->actingAs(User::find(1))->get('/api/projects/1/all');
        $response->assertJsonStructure([
            'project',
            'tasks'
        ]);
        $response->assertStatus(200);
    }
}
