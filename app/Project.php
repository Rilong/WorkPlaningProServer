<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public $fillable = ['name', 'description', 'budget', 'start_date', 'deadline_date', 'finished_date'];
    public $appends  = ['percent'];

    public function getPercentAttribute() {
        $done    = 0;
        $percent = 0;

        if ($this->relationLoaded('tasks') && count($this->tasks) > 0) {
            foreach ($this->tasks as $task) {
                if ($task->is_done === 1) {
                    $done++;
                }
            }
            $total = count($this->tasks);
            $percent = ceil(100 * $done / $total);
        }
        return $percent;
    }

    public function tasks()
    {
        return $this->hasMany('App\Task', 'project_id');
    }

    public function notes()
    {
        return $this->hasMany('App\Note', 'project_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public static function add(array $fields)
    {
        $new_project = new static();
        $new_project->budget = 0;
        $new_project->start_date = Carbon::now();
        $new_project->fill($fields);
        return $new_project;
    }

    public function change(array $fields)
    {
        $this->fill($fields);
        return $this->save();
    }

    public function remove()
    {
        $this->tasks()->delete();
        $this->notes()->delete();

        return $this->delete();
    }

    public function setBudget(float $budget)
    {
        $this->budget = $budget;
        return $this->save();
    }

    public function setName(string $name)
    {
        $this->name = $name;
        return $this->save();
    }

    public function finish()
    {
        $this->finished_date = Carbon::now();
        return $this->save();
    }
}
