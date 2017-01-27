<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// Entité Ressource
class Resource extends Model
{
    // Permet de retourner le projet auquel la ressource appartient.
    public function project()
    {
      return $this->belongsTo(Project::class);
    }

    // Permet de retourner les tâches auxquelles la ressource est associée
    public function ganttTasks()
    {
      return $this->belongsToMany(GanttTask::class);
    }

    // Définit les champs pouvant être remplis
    protected $fillable = ['firstname', 'lastname', 'email', 'role',
                          'cost_initial', 'cost_per_hour'];
}
