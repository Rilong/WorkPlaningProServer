<?php


namespace App\Repositories;


use App\Project;

class ProjectRepository
{
    public function all() {
        $projects = Project::with('tasks')->where(['user_id' => auth()->id()])->get()->toArray();

        foreach ($projects as $key => $project) {
            $project['tasks'] = $this->tasksTree($project['tasks']);
            $projects[$key] = $project;
        }
        return $projects;
    }

    private function tasksTree($tasks, $parent_id = 0) {
        $array = [];

        foreach ($tasks as $task) {
            if ($task['parent_id'] == $parent_id) {
                $children = $this->tasksTree($tasks, $task['id']);
                if ($children) {
                    $task['tasks'] = $children;
                }

                $array[] = $task;
            }
        }

        return $array;
    }
}
