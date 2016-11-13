<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function collaboraters()
    {
      return $this->hasMany(Collaborater::class);
    }

    public function resources()
    {
      return $this->hasMany(Resource::class);
    }
}
