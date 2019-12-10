<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public $fillable = ['title', 'deadline_date', 'finished_date'];

    public function project()
    {
        return $this->hasOne('App\Project', 'id', 'project_id');
    }

    public function tasks() {
        return $this->hasMany('App\Task', 'parent_id');
    }

    public function task() {
        return $this->hasOne('App\Task', 'id', 'parent_id');
    }

    public static function add(array $fields, $is_save = true)
    {
        $new_task = new static();
        $new_task->fill($fields);
        return $is_save ?  $new_task->save() : $new_task;
    }

    public function change(array $fields)
    {
        $this->fill($fields);
        return $this->update();
    }

    public function remove()
    {
        return $this->delete();
    }

    public function check() {
        $this->is_done = true;
        $this->finished_date = Carbon::now()->hours(0)->minutes(0)->seconds(0);
        return $this->update();
    }

    public function uncheck() {
        $this->is_done = false;
        $this->finished_date = null;
        return $this->update();
    }

    public function toggleCheck() {
        $this->is_done = !$this->is_done;
        return $this->save();
    }

    public function setTitle(string $title) {
        $this->title = $title;
        return $this->save();
    }


}
