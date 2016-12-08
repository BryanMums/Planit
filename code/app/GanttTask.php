<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GanttTask extends Model
{
    public function project(){
      return $this->belongsTo(Project::class);
    }

    public function resources(){
      return $this->belongsToMany(Resource::class);
    }
}
