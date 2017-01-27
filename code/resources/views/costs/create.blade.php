@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <h2><b>{{ $project->name}}</b> | Nouveau coût</h2>
          <form method="POST" action="/project/{{ $project->id }}/cost/create">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="project_id" value="{{$project->id}}">
            <div class="form-group">
              <label for="name">Titre</label>
              <input id="name" type="text" name="name" class="form-control"/ required>
            </div>
            <div class="form-group">
              <label for="description">Description</label>
              <textarea id="description" name="description" class="form-control"></textarea>
            </div>
            <div class="form-group">
              <label for="value">Coût</label>
              <input id="value" type="number" name="value" class="form-control"/ required >
            </div>

            <div class="form-group">
              <button type="submit" class="btn btn-primary">Créer</button>
              <a href="/project/{{$project->id}}/finance" class="btn btn-default">Annuler</a>
            </div>
          </form>
        </div>
    </div>
</div>
@endsection
