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
    protected $fillable = ['user_id', 'informations_rights',
                            'collaboraters_rights', 'resources_rights', 'gantt_rights',
                            'budget_rights'];
}
