<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Project extends Model
{
    //
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users(){
      return $this->belongsToMany(User::class, 'collaboraters');
    }

    public function collaboraters()
    {
      return $this->hasMany(Collaborater::class);
    }

    public function resources()
    {
      return $this->hasMany(Resource::class);
    }

    public function costs()
    {
      return $this->hasMany(Cost::class);
    }

    public function is_admin()
    {
        if(Auth::id() != $this->user_id){
          return false;
        }
        return true;
    }

    public function is_collaborater()
    {
      $collaborater = Collaborater::where('user_id','=',Auth::id())->where('project_id', '=', $this->id)->first();
      if($collaborater == null) return false;
      return true;
    }

    private function droits($field, $value){
      if($this->is_admin()) return true;
      $collaborater = Collaborater::where('user_id','=',Auth::id())->where('project_id', '=', $this->id)->first();
      if($collaborater == null) return false;
      if($collaborater->$field > $value) return true;
      return false;
    }

    public function see_informations(){
      return $this->droits('informations_rights', 0);
    }

    public function modify_informations(){
      return $this->droits('informations_rights', 1);
    }

    public function see_collaboraters(){
      return $this->droits('collaboraters_rights', 0);
    }

    public function modify_collaboraters(){
      return $this->droits('collaboraters_rights', 1);
    }

    public function see_resources(){
      return $this->droits('resources_rights', 0);
    }

    public function modify_resources(){
      return $this->droits('resources_rights', 1);
    }

    public function see_finance(){
      return $this->droits('budget_rights', 0);
    }

    public function modify_finance(){
      return $this->droits('budget_rights', 1);
    }

    public function see_gantt(){
      return $this->droits('gantt_rights', 0);
    }

    public function modify_gantt(){
      return $this->droits('gantt_rights', 1);
    }

    protected $fillable = ['name', 'description', 'budget', 'date_begin',
                          'date_end', 'client_name', 'client_mail', 'client_tel'];
}
