<?php


namespace App\Repositories;


use App\Task;
use Carbon\Carbon;

class TaskRepository extends CoreRepository
{
    public function allByUser($user_id)
    {
        return $this->makeTree(Task::where(['user_id' => $user_id])->get()->toArray(), 'tasks');
    }

    public function allByProject($project_id, $user_id)
    {
        return $this->makeTree(Task::where(['project_id' => $project_id, 'user_id' => $user_id])->get()->toArray(), 'tasks');
    }

    public function allByDate($date_param, $user_id)
    {
        $tasks = Task::with('project')->where('user_id', $user_id);
        $date = new Carbon(urldecode($date_param));
        return $tasks->where(['deadline_date' => $date])->get();
    }

   public function allByMonth($date_param, $user_id) {
       $tasks = Task::with('project')->where('user_id', $user_id);
       $date = new Carbon(urldecode($date_param));
       $start = $date->clone()->startOfMonth()->startOfWeek(Carbon::MONDAY);
       $end = $date->clone()->endOfMonth()->endOfWeek(Carbon::MONDAY);

       return $tasks->whereBetween('deadline_date', [$start, $end])->get();
   }

   public function getById($id) {
        return Task::find($id);
   }
}
