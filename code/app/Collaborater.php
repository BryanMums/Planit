<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// Entité collaborateur
class Collaborater extends Model
{
    // Permet de retourner l'utilisateur qui est le collaborateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Permet de retourner le projet sur lequel travail le collaborateur
    public function project()
    {
      return $this->belongsTo(Project::class);
    }

    // Définit les champs pouvant être remplis
    protected $fillable = ['user_id', 'informations_rights',
                            'collaboraters_rights', 'resources_rights', 'gantt_rights',
                            'budget_rights'];
}
