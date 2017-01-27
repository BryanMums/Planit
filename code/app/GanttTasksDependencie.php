<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// Entité dépendance
class GanttTasksDependencie extends Model
{
    // Permet de retourner la tâche liée à la dépendance.
    public function gantttask(){
        return $this->belongsTo(GanttTask::class);
    }

    // Permet de définir le nom de la table dans la base de données à utiliser par le modèle.
    protected $table = 'gantttasksdependencies';
    protected $fillable = ['predecessor_id'];
}
