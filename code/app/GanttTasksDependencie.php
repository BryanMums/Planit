<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GanttTasksDependencie extends Model
{
    public function gantttask(){
        return $this->belongsTo(GanttTask::class);
    }

    protected $table = 'gantttasksdependencies';
    protected $fillable = ['predecessor_id'];
}
