<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// Entité coût
class Cost extends Model
{
  // Permet de retourner le projet auquel le coût est lié.
  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  // Définit les champs pouvant être remplis
  protected $fillable = ['name', 'description', 'value'];
}
