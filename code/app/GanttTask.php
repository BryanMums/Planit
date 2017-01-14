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

    public function dependencies(){
      return $this->hasMany(GanttTasksDependencie::class);
    }

     protected $table = 'gantttasks';
     protected $fillable = ['parent_id', 'order_id', 'title', 'description',
                            'date_begin_plan', 'duration_plan', 'hours_plan',
                            'date_begin_real', 'duration_real', 'hours_real',
                            'color'];
}
