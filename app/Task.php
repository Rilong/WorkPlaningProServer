<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public $fillable = ['title', 'deadline_date', 'finished_date'];

    public function project()
    {
        return $this->hasOne('App\Project', 'id', 'project_id');
    }

    public static function add(array $fields)
    {
        $new_task = new static();
        $new_task->fill($fields);
        return $new_task->save();
    }

    public function change(array $fields)
    {
        $this->fill($fields);
        return $this->save();
    }

    public function remove()
    {
        return $this->delete();
    }

    public function check() {
        $this->is_done = true;
        return $this->save();
    }

    public function uncheck() {
        $this->is_done = false;
        return $this->save();
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
