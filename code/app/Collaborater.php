<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collaborater extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
      return $this->belongsTo(Project::class);
    }
}
