@extends('layouts.app')

@section('content')

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<div class="container">
    <div class="row">
        <h2><b>{{ $project->name }}</b> | Accueil </h2>
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
                  <button class="btn btn-warning btn-xs btn-detail open-modal-collaborater" value="{{$collaborater->id}}">Modifier</button>
                  <button class="btn btn-xs btn-danger btn-delete delete-collaborater" value="{{$collaborater->id}}">Supprimer</button>
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
            @foreach ($project->resources as $resource)
            <tr id="resource{{$resource->id}}">
              <td id="resource{{$resource->id}}Firstname">{{$resource->firstname}}</td>
              <td id="resource{{$resource->id}}Role">{{$resource->role}}</td>
              <td>
                <button class="btn btn-warning btn-xs btn-detail open-modal-resource" value="{{$resource->id}}">Modifier</button>
                <button class="btn btn-xs btn-danger btn-delete delete-resource" value="{{$resource->id}}">Supprimer</button>
              </td>
            </tr>
          @endforeach
          </table>
          <a href="/project/{{ $project->id }}/resource/create" class="btn btn-success">Ajouter une nouvelle ressource</a>
          <!-- END : Ressources -->
        </div>
    </div>
</div>
<meta name="_token" content="{!! csrf_token() !!}" />



<!--******************************MODALS EDIT COLLABORATER*****************************-->
<div class="modal fade" id="collaboraterModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Edition du collaborateur <span id="modColName"></span></h4>
            </div>
            <div class="modal-body">
                <form id="frmCollaboraters" name="frmCollaboraters" class="form-horizontal" novalidate="">

                  <div class="form-group">
                    <div class="col-md-4">
                      <label>Informations de base</label>
                      <div class="radio">
                        <label><input id="info0" type="radio" name="inforadio" value=0>Aucun droit</label>
                      </div>
                      <div class="radio">
                        <label><input id="info1" type="radio" name="inforadio" value=1>Lecture</label>
                      </div>
                      <div class="radio">
                        <label><input id="info2" type="radio" name="inforadio" value=2>Modification</label>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <label>Gantt</label>
                      <div class="radio">
                        <label><input type="radio" name="ganttradio" value=0>Aucun droit</label>
                      </div>
                      <div class="radio">
                        <label><input type="radio" name="ganttradio" value=1>Lecture</label>
                      </div>
                      <div class="radio">
                        <label><input type="radio" name="ganttradio" value=2>Modification</label>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <label>Budget</label>
                      <div class="radio">
                        <label><input type="radio" name="budgetradio" value=0>Aucun droit</label>
                      </div>
                      <div class="radio">
                        <label><input type="radio" name="budgetradio" value=1>Lecture</label>
                      </div>
                      <div class="radio">
                        <label><input type="radio" name="budgetradio" value=2>Modification</label>
                      </div>
                    </div>
                  </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-save-collaborater" value="add">Save changes</button>
                <input type="hidden" id="modalCollaborater_id" name="collaborater_id" value="0">
            </div>
        </div>
    </div>
</div>

<!--******************************MODALS EDIT RESOURCE*****************************-->
<div class="modal fade" id="resourceModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Edition de la ressource<span id="modResName"></span></h4>
            </div>
            <div class="modal-body">
              <div class="container-fluid">
                <form id="frmResources" name="frmResources" class="form-horizontal" novalidate="">
                  <div class="row">
                  <div class="col-md-12">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <div class="form-group">
                    <label for="firstname">Prénom</label>
                    <input id="firstname" type="text" name="firstname" class="form-control"/ >
                  </div>
                  <div class="form-group">
                    <label for="lastname">Nom</label>
                    <input id="lastname" type="text" name="lastname" class="form-control"/ >
                  </div>
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="text" name="email" class="form-control"/ >
                  </div>
                  <div class="form-group">
                    <label for="role">Rôle</label>
                    <input id="role" type="text" name="role" class="form-control"/ >
                  </div>
                </div>
              </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="cost_initial">Coût initial</label>
                        <input id="cost_initial" type="number" name="cost_initial" class="form-control"/ >
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="cost_per_hour">Coût à l'heure</label>
                        <input id="cost_per_hour" type="number" name="cost_per_hour" class="form-control"/ >
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-save-resource" value="add">Save changes</button>
                <input type="hidden" id="modalResource_id" name="resource_id" value="0">
            </div>
        </div>
    </div>
</div>

<!--******************************SCRIPT AJAX*****************************-->
<script>
$(document).ready(function(){
  $.ajaxSetup(
{
    headers:
    {
        'X-CSRF-Token': $('input[name="_token"]').val()
    }
});

$('.open-modal-collaborater').click(function(){
        var collaborater_id = $(this).val();

        $.get('/collaborater' + '/' + collaborater_id, function (data) {
            //success data
            var $radios = $('input:radio[name=inforadio]');
            if($radios.is(':checked') === false) {
                $radios.filter('[value='+data.informations_rights+']').prop('checked', true);
            }
            $radios = $('input:radio[name=budgetradio]');
            if($radios.is(':checked') === false) {
                $radios.filter('[value='+data.budget_rights+']').prop('checked', true);
            }
            $radios = $('input:radio[name=ganttradio]');
            if($radios.is(':checked') === false) {
                $radios.filter('[value='+data.gantt_rights+']').prop('checked', true);
            }
            $('#modalCollaborater_id').val(collaborater_id);
            $('#collaboraterModal').modal('show');
        })
    });
//delete task and remove it from list
    $('.delete-collaborater').click(function(){
      if(confirm("Voulez-vous vraiment supprimer ce collaborateur ?")){
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
      }
    });



        //create new task / update existing task
        $("#btn-save-collaborater").click(function (e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            })

            e.preventDefault();

            var formData = {
                informations_rights: $('input[name=inforadio]:checked').val(),
                gantt_rights: $('input[name=ganttradio]:checked').val(),
                budget_rights: $('input[name=budgetradio]:checked').val(),
            }


            var collaborater_id = $('#modalCollaborater_id').val();;

            var type = "PUT"; //for updating existing resource
            var my_url = '/collaborater/' + collaborater_id;


            $.ajax({

                type: type,
                url: my_url,
                data: formData,
                dataType: 'json',
                success: function (data) {

                    $('#frmCollaborater').trigger("reset");

                    $('#collaboraterModal').modal('hide')
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        });


    $('.open-modal-resource').click(function(){
            var resource_id = $(this).val();

            $.get('/resource' + '/' + resource_id, function (data) {
                //success data
                $('#firstname').val(data.firstname);
                $('#lastname').val(data.lastname);
                $('#email').val(data.email);
                $('#role').val(data.role);
                $('#cost_initial').val(data.cost_initial);
                $('#cost_per_hour').val(data.cost_per_hour);
                $('#modalResource_id').val(resource_id);
                $('#resourceModal').modal('show');
            })
        });

        $("#btn-save-resource").click(function (e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            })

            e.preventDefault();

            var formData = {
                firstname: $('#firstname').val(),
                lastname: $('#lastname').val(),
                email: $('#email').val(),
                role: $('#role').val(),
                cost_initial: $('#cost_initial').val(),
                cost_per_hour: $('#cost_per_hour').val(),
            }


            var resource_id = $('#modalResource_id').val();

            var type = "PUT"; //for updating existing resource
            var my_url = '/resource/' + resource_id;

            $.ajax({

                type: type,
                url: my_url,
                data: formData,
                dataType: 'json',
                success: function (data) {

                    $('#resource'+ resource_id+'Firstname').html(data.firstname);
                    $('#resource'+ resource_id+'Role').html(data.role);


                    $('#frmResource').trigger("reset");

                    $('#resourceModal').modal('hide')
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        });

    $('.delete-resource').click(function(){
      if(confirm("Voulez-vous vraiment supprimer cette ressource ?")){
        var resource_id = $(this).val();

        $.ajax({
            type: "DELETE",
            url: '/resource/' + resource_id,
            success: function (data) {

                $("#resource" + resource_id).remove();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
      }
    });
  });
</script>
@endsection
