@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <h1>{{ $project->name }} </h1>
        <hr/>
        <!-- Colonne de gauche -->
        <div class="col-md-6">
          <h2>Informations</h2>
            <h4>Description</h4>
            <p>{{ $project->description }}</p>

            <h4>Budget</h4>
            <p>{{ $project->budget }} CHF</p>

            <div class="col-md-6">
              <h4>Date de début</h4>
              <p>{{ $project->date_begin }}</p>
            </div>
            <div class="col-md-6">
              <h4>Date de fin</h4>
              <p>{{ $project->date_end }}</p>
            </div>

            <h4>Infos du client</h4>
            <p><a href="{{ $project->client_mail }}" >{{ $project->client_name }}</a> | {{ $project->client_tel }}</p>
        </div>
        <!-- Colonne de droite -->
        <div class="col-md-6">
          <h2>Collaborateurs</h2>
          <table class="table">
            <tr>
              <th>Nom</th>
              <th>Action</th>
            </tr>
            @foreach ($project->collaboraters as $collaborater)
              <tr id="collaborater{{$collaborater->id}}">
                <td>{{$collaborater->user->name}}</td>
                <td>
                  <a href="#" class="btn btn-sm btn-warning">Modifier</a>
                  <button class="btn btn-sm btn-danger btn-delete delete-collaborater" value="{{$collaborater->id}}">Supprimer</button>
                </td>
              </tr>
            @endforeach

          </table>
          <a href="/project/{{ $project->id }}/collaborater/create" class="btn btn-success">Ajouter un nouveau collaborateur</a>
          <!-- BEGIN : Ressources -->
          <h2>Ressources</h2>
          <table class="table">
            <tr>
              <th>Nom</th>
              <th>Rôle</th>
              <th>Action</th>
            </tr>
            <tr>
              <td>Muhmenthaler Bryan</td>
              <td>Responsable</td>
              <td><a href="#" class="btn btn-sm btn-warning">Modifier</a><a href="#" class="btn btn-sm btn-danger">Supprimer</a></td>
            </tr>
          </table>
          <!-- END : Ressources -->
        </div>
    </div>
    <a href="#" class="btn btn-default">Gestion du Gantt</a>
    <a href="#" class="btn btn-default">Gestion du budget/Coûts</a>
    <a href="#" class="btn btn-default">Voir page statistiques</a>
</div>
<meta name="_token" content="{!! csrf_token() !!}" />
<script>
$(document).ready(function(){
  $.ajaxSetup(
{
    headers:
    {
        'X-CSRF-Token': $('input[name="_token"]').val()
    }
});
//delete task and remove it from list
    $('.delete-collaborater').click(function(){
        var collaborater_id = $(this).val();

        $.ajax({
            type: "DELETE",
            url: '/collaborater/' + collaborater_id,
            success: function (data) {
                console.log(data);

                $("#collaborater" + collaborater_id).remove();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });
  });
</script>
@endsection
