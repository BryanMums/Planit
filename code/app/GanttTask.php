<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// Entité Tâche
class GanttTask extends Model
{
    // Permet de retourner le projet auquel la tâche est liée.
    public function project(){
      return $this->belongsTo(Project::class);
    }

    // Permet de retourner les ressources utilisées par la tâche.
    public function resources(){
      return $this->belongsToMany(Resource::class);
    }

    // Permet de retourner les dépendances de la tâche.
    public function dependencies(){
      return $this->hasMany(GanttTasksDependencie::class);
    }

    // Définit le nom de la table dans la base de données à utiliser par le modèle.
     protected $table = 'gantttasks';

     // Définit les champs pouvant être remplis
     protected $fillable = ['parent_id', 'order_id', 'title', 'description',
                            'date_begin_plan', 'duration_plan', 'hours_plan',
                            'date_begin_real', 'duration_real', 'hours_real',
                            'color', 'percent_done'];
}
