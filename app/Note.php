<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{

    public function project() {
        return $this->hasOne('App\Project', 'id', 'project_id');
    }

    public static function add(string $content)
    {
        $new_note = new static();
        $new_note->content = $content;
        $new_note->save();
        return $new_note;
    }

    public function setContent(string $content)
    {
        $this->content = $content;
        return $this->save();
    }

    public function remove()
    {
        return $this->delete();
    }
}
