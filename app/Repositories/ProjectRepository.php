<?php


namespace App\Repositories;


use App\Project;

class ProjectRepository extends CoreRepository
{
    public function all()
    {
        $projects = Project::with('tasks')->where(['user_id' => auth()->id()])->get()->toArray();

        foreach ($projects as $key => $project) {
            $project['tasks'] = $this->makeTree($project['tasks'], 'tasks');
            $projects[$key] = $project;
        }
        return $projects;
    }
}
