<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{

    public function project()
    {
      return $this->belongsTo(Project::class);
    }

    protected $fillable = ['firstname', 'lastname', 'email', 'role',
                          'cost_initial', 'cost_per_hour'];
}
