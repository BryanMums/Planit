@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <h2><b>{{ $project->name}}</b> | Nouvelle ressource</h2>
          <form method="POST" action="/project/{{ $project->id }}/resource/create">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="project_id" value="{{$project->id}}">
            <div class="form-group">
              <label for="firstname">Prénom</label>
              <input id="firstname" type="text" name="firstname" class="form-control" required/ >
            </div>
            <div class="form-group">
              <label for="lastname">Nom</label>
              <input id="lastname" type="text" name="lastname" class="form-control" required/ >
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input id="email" type="text" name="email" class="form-control"/ >
            </div>
            <div class="form-group">
              <label for="role">Rôle</label>
              <input id="role" type="text" name="role" class="form-control"/ >
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="cost_initial">Coût initial</label>
                  <input id="cost_initial" type="number" name="cost_initial" class="form-control" required/ >
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="cost_per_hour">Coût à l'heure</label>
                  <input id="cost_per_hour" type="number" name="cost_per_hour" class="form-control" required/ >
                </div>
              </div>
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-primary">Créer</button>
              <a href="/project/{{$project->id}}" class="btn btn-default">Annuler</a>
            </div>
          </form>
        </div>
    </div>
</div>
@endsection
