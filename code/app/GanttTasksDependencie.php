<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GanttTasksDependencie extends Model
{
    public function successors(){
      return $this->belongsToMany(GanttTask::class, 'successor_id');
    }

    public function predecessors(){
      return $this->belongsToMany(GanttTask::class, 'predecessor_id');
    }
}
