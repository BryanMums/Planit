@extends('layouts.app')

@section('content')
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//codeorigin.jquery.com/ui/1.10.2/jquery-ui.min.js"></script>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <h2><b>{{ $project->name}}</b> | Nouveau collaborateur</h2>
          <form method="POST" action="/project/{{ $project->id }}/collaborater/create">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="project_id" value="{{$project->id}}">
            <div class="form-group">
              <label for="name">Email du collaborateur</label>
              <input id="user_search" type="text" name="user_search" class="form-control"/  required>
              <input id="id_user" type="hidden" name="user_id" value=""  required/>
            </div>
            <h3>Droits</h3>
            <div class="form-group">
              <div class="row">
              <div class="col-md-4">
                <label>Informations de base</label>
                <div class="radio">
                  <label><input type="radio" name="informations_rights" value=0 required>Aucun droit</label>
                </div>
                <div class="radio">
                  <label><input type="radio" name="informations_rights" value=1 required>Lecture</label>
                </div>
                <div class="radio">
                  <label><input type="radio" name="informations_rights" value=2 required>Modification</label>
                </div>
              </div>

              <div class="col-md-4">
                <label>Collaborateurs</label>
                <div class="radio">
                  <label><input type="radio" name="collaboraters_rights" value=0 required>Aucun droit</label>
                </div>
                <div class="radio">
                  <label><input type="radio" name="collaboraters_rights" value=1 required>Lecture</label>
                </div>
                <div class="radio">
                  <label><input type="radio" name="collaboraters_rights" value=2 required>Modification</label>
                </div>
              </div>

              <div class="col-md-4">
                <label>Ressources</label>
                <div class="radio">
                  <label><input type="radio" name="resources_rights" value=0 required>Aucun droit</label>
                </div>
                <div class="radio">
                  <label><input type="radio" name="resources_rights" value=1 required>Lecture</label>
                </div>
                <div class="radio">
                  <label><input type="radio" name="resources_rights" value=2 required>Modification</label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Gantt</label>
                <div class="radio">
                  <label><input type="radio" name="gantt_rights" value=0 required>Aucun droit</label>
                </div>
                <div class="radio">
                  <label><input type="radio" name="gantt_rights" value=1 required>Lecture</label>
                </div>
                <div class="radio">
                  <label><input type="radio" name="gantt_rights" value=2 required>Modification</label>
                </div>
              </div>

              <div class="col-md-4">
                <label>Budget</label>
                <div class="radio">
                  <label><input type="radio" name="budget_rights" value=0 required>Aucun droit</label>
                </div>
                <div class="radio">
                  <label><input type="radio" name="budget_rights" value=1 required>Lecture</label>
                </div>
                <div class="radio">
                  <label><input type="radio" name="budget_rights" value=2 required>Modification</label>
                </div>
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
<script type="text/javascript">
    $(function() {
        $("#user_search").autocomplete({
            source: "/getusers/{{$project->id}}",
            minLength: 1,
            select: function( event, ui ) {
                $('#id_user').val(ui.item.id);
            }
        });
    });
</script>
@endsection
