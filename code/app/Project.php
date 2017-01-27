<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

// Entité Projet
class Project extends Model
{
    // Permet de retourner le propriétaire du projet.
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Permet de retourner les collaborateurs du projet.
    /*public function users(){
      return $this->belongsToMany(User::class, 'collaboraters');
    }*/

    // Permet de retourner les collaborateurs du projet.
    public function collaboraters()
    {
      return $this->hasMany(Collaborater::class);
    }

    // Permet de retourner les ressources du projet.
    public function resources()
    {
      return $this->hasMany(Resource::class);
    }

    // Permet de retourner les coûts du projet.
    public function costs()
    {
      return $this->hasMany(Cost::class);
    }

    // Permet de retourner les tâches liées au projet.
    public function gantttasks(){
      return $this->hasMany(GanttTask::class);
    }

    // Permet de savoir si l'utilisateur authentifié est l'administrateur.
    public function is_admin()
    {
        if(Auth::id() != $this->user_id){
          return false;
        }
        return true;
    }

    // Permet de savoir si l'utilisateur authentifié est un collaborateur du projet.
    public function is_collaborater()
    {
      $collaborater = Collaborater::where('user_id','=',Auth::id())->where('project_id', '=', $this->id)->first();
      if($collaborater == null) return false;
      return true;
    }

    // Méthode appelés dans les méthodes de gestion de droits pour savoir si l'utilisateur a les différents droits conernant une section.
    private function droits($field, $value){
      if($this->is_admin()) return true;
      $collaborater = Collaborater::where('user_id','=',Auth::id())->where('project_id', '=', $this->id)->first();
      if($collaborater == null) return false;
      if($collaborater->$field > $value) return true;
      return false;
    }

    // Permet de savoir si l'utilisateur a le droit de voir les informations du projet.
    public function see_informations(){
      return $this->droits('informations_rights', 0);
    }

    // Permet de savoir si l'utilisateur authentifié a le droit de modifier les informations du projet.
    public function modify_informations(){
      return $this->droits('informations_rights', 1);
    }

    // Permet de savoir si l'utilisateur authentifié a le droit de voir les collaborateurs.
    public function see_collaboraters(){
      return $this->droits('collaboraters_rights', 0);
    }

    // Permet de savoir si l'utilisateur authentifié a le droit d'ajouter/modifier/supprimer des collaborateurs.
    public function modify_collaboraters(){
      return $this->droits('collaboraters_rights', 1);
    }

    // Permet de savoir si l'utilisateur authentifié a le droit de voir les ressources.
    public function see_resources(){
      return $this->droits('resources_rights', 0);
    }

    // Permet de savoir si l'utilisateur authentifié a le droit d'ajouter/modifier/supprimer des ressources.
    public function modify_resources(){
      return $this->droits('resources_rights', 1);
    }

    // Permet de savoir si l'utilisateur authentifié a le droit de voir les coûts.
    public function see_finance(){
      return $this->droits('budget_rights', 0);
    }

    // Permet de savoir si l'utilisateur authentifié a le droit d'ajouter/modifier/supprimer des coûts.
    public function modify_finance(){
      return $this->droits('budget_rights', 1);
    }

    // Permet de savoir si l'utilisateur authentifié a le droit de voir la planification
    public function see_gantt(){
      return $this->droits('gantt_rights', 0);
    }

    // Permet de savoir si l'utilisateur authentifié a le droit de gérer la planification.
    public function modify_gantt(){
      return $this->droits('gantt_rights', 1);
    }

    // Définit les champs pouvant être remplis
    protected $fillable = ['name', 'description', 'budget', 'date_begin',
                          'date_end', 'client_name', 'client_mail', 'client_tel'];
}
