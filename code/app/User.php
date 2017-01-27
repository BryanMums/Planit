<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    // Définit les champs pouvant être remplis.
    protected $fillable = [
        'name', 'email', 'password',
    ];

    // Définit les champs devant être cachés.
    protected $hidden = [
        'password', 'remember_token',
    ];

    // Permet de retourner les projets où l'utilisateur est le créateur.
    public function projects()
    {
      return $this->hasMany(Project::class);

    }

    // Permet de retourner les projets où l'utilisateur est collaborateur.
    public function projectsCol(){
      return $this->belongsToMany(Project::class, 'collaboraters');
    }

    // Permet de retourner les collaborateurs liés à l'utilisateur.
    public function collaboraters()
    {
      return $this->hasMany(Collaborater::class);
    }



}
