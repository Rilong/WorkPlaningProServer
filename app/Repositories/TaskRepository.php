<?php


namespace App\Repositories;


use App\Task;

class TaskRepository extends CoreRepository
{
    public function all($project_id)
    {
        return $this->makeTree(Task::where(['project_id' => $project_id])->get()->toArray(), 'tasks');
    }
}
