@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <h2><b><span id="view_title">{{ $project->name }}</span></b> | Accueil </h2>
      <hr/>
      <!-- Colonne de gauche -->
      @if ($project->see_informations())
        <div class="col-md-6">
          <h2>Informations</h2>
          <h4>Description</h4>
          <p id="view_description">{{ $project->description }}</p>

          <h4>Budget</h4>
          <p id="view_budget">{{ $project->budget }} CHF</p>

          <div class="col-md-6">
            <h4>Date de début</h4>
            <p id="view_date_begin">{{ $project->date_begin }}</p>
          </div>
          <div class="col-md-6">
            <h4>Date de fin</h4>
            <p id="view_date_end">{{ $project->date_end }}</p>
          </div>

          <h4>Infos du client</h4>
          <p><a href="mailto:{{ $project->client_mail }}" >{{ $project->client_name }}</a> | {{ $project->client_tel }}</p>
          @if ($project->modify_informations())
            <a href="#" class="btn btn-warning btn-xs open-modal-informations" value="{{$project->id}}">Modifier les informations</a>
          @endif
        </div>
      @endif
      <!-- Colonne de droite -->
      <div class="col-md-6">
        @if($project->see_collaboraters())
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
                  <button class="btn btn-warning btn-xs btn-detail open-modal-collaborater" value="{{$collaborater->id}}">Voir/Modifier</button>
                  @if ($project->modify_collaboraters())
                    <button class="btn btn-xs btn-danger btn-delete delete-collaborater" value="{{$collaborater->id}}">Supprimer</button>
                  @endif
                </td>
              </tr>
            @endforeach

          </table>
          @if ($project->is_admin())
            <a href="/project/{{ $project->id }}/collaborater/create" class="btn btn-success">Ajouter un nouveau collaborateur</a>
          @endif
        @endif
        <!-- BEGIN : Ressources -->
        @if($project->see_resources())
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
                  <button class="btn btn-warning btn-xs btn-detail open-modal-resource" value="{{$resource->id}}">Voir/Modifier</button>
                  @if ($project->modify_resources())
                    <button class="btn btn-xs btn-danger btn-delete delete-resource" value="{{$resource->id}}">Supprimer</button>
                  @endif
                </td>
              </tr>
            @endforeach
          </table>
          @if($project->modify_resources())
            <a href="/project/{{ $project->id }}/resource/create" class="btn btn-success">Ajouter une nouvelle ressource</a>
          @endif
          <!-- END : Ressources -->
        @endif
      </div>
      <div class="col-md-6">
        @if ($project->is_admin())
          <a href="#" class="btn btn-danger delete_project">Supprimer le projet</a>
        @elseif ($project->is_collaborater())
          <a href="/project/{{$project->id}}/quit" class="btn btn-danger quit_project" onclick="confirm('Êtes-vous sûr de vouloir quitter ce projet ?')">Quitter le projet</a>
        @endif
      </div>
    </div>
  </div>
  <meta name="_token" content="{!! csrf_token() !!}" />

  @if ($project->modify_informations())
    <!--******************************MODALS EDIT RESOURCE*****************************-->
    <div class="modal fade" id="informationsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myModalLabel">Edition des informations<span id="modInfoName"></span></h4>
          </div>
          <div class="modal-body">
            <div class="container-fluid">
              <form id="frmInformations" name="frmInformations" class="form-horizontal" novalidate="">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                  <label for="name">Titre du projet</label>
                  <input type="text" name="name" id="update_title" class="form-control"/  required>
                </div>
                <div class="form-group">
                  <label for="description">Description</label>
                  <textarea name="description" id="update_description" class="form-control"></textarea>
                </div>
                <div class="form-group col-md-4">
                  <label for="budget">Budget</label>
                  <input type="number" name="budget" id="update_budget" class="form-control" required/ >
                </div>
                <div class="form-group col-md-4">
                  <label for="date_begin">Date de début</label>
                  <input type="date" name="date_begin" id="update_date_begin" class="form-control" required/ >
                </div>
                <div class="form-group col-md-4">
                  <label for="date_end">Date de fin</label>
                  <input type="date" name="date_end" id="update_date_end" class="form-control" required/ >
                </div>
                <h3>Informations sur le client</h3>
                <h5>Laisser vide si projet personnel</h5>
                <div class="form-group col-md-4">
                  <label for="client_name">Nom du client</label>
                  <input type="text" name="client_name" id="update_name_client" class="form-control"/ >
                </div>
                <div class="form-group col-md-4">
                  <label for="client_mail">Email du client</label>
                  <input type="email" name="client_mail" id="update_email_client" class="form-control"/ >
                </div>
                <div class="form-group col-md-4">
                  <label for="client_tel">Téléphone du client</label>
                  <input type="text" name="client_tel" id="update_tel_client" class="form-control"/ >
                </div>
              </form>
            </div>
          </div>
          <div class="modal-footer">
            @if($project->modify_resources())
              <button type="button" class="btn btn-primary" id="btn-save-informations" value="add">Save changes</button>
            @endif
            <input type="hidden" id="modalInformation_id" name="project_id" value="{{$project->id}}">
          </div>
        </div>
      </div>
    </div>
  @endif

  @if($project->see_collaboraters())
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
                    <label><input id="info0" type="radio" name="inforadio" value=0 required>Aucun droit</label>
                  </div>
                  <div class="radio">
                    <label><input id="info1" type="radio" name="inforadio" value=1 required>Lecture</label>
                  </div>
                  <div class="radio">
                    <label><input id="info2" type="radio" name="inforadio" value=2 required>Modification</label>
                  </div>
                </div>

                <div class="col-md-4">
                  <label>Collaborateurs</label>
                  <div class="radio">
                    <label><input type="radio" name="collaboradio" value=0 required>Aucun droit</label>
                  </div>
                  <div class="radio">
                    <label><input type="radio" name="collaboradio" value=1 required>Lecture</label>
                  </div>
                  <div class="radio">
                    <label><input type="radio" name="collaboradio" value=2 required>Modification</label>
                  </div>
                </div>

                <div class="col-md-4">
                  <label>Ressources</label>
                  <div class="radio">
                    <label><input type="radio" name="resoradio" value=0 required>Aucun droit</label>
                  </div>
                  <div class="radio">
                    <label><input type="radio" name="resoradio" value=1 required>Lecture</label>
                  </div>
                  <div class="radio">
                    <label><input type="radio" name="resoradio" value=2 required>Modification</label>
                  </div>
                </div>

                <div class="col-md-4">
                  <label>Gantt</label>
                  <div class="radio">
                    <label><input type="radio" name="ganttradio" value=0 required>Aucun droit</label>
                  </div>
                  <div class="radio">
                    <label><input type="radio" name="ganttradio" value=1 required>Lecture</label>
                  </div>
                  <div class="radio">
                    <label><input type="radio" name="ganttradio" value=2 required>Modification</label>
                  </div>
                </div>

                <div class="col-md-4">
                  <label>Budget</label>
                  <div class="radio">
                    <label><input type="radio" name="budgetradio" value=0 required>Aucun droit</label>
                  </div>
                  <div class="radio">
                    <label><input type="radio" name="budgetradio" value=1 required>Lecture</label>
                  </div>
                  <div class="radio">
                    <label><input type="radio" name="budgetradio" value=2 required>Modification</label>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            @if($project->modify_collaboraters())
              <button type="button" class="btn btn-primary" id="btn-save-collaborater" value="add">Save changes</button>
            @endif
            <input type="hidden" id="modalCollaborater_id" name="collaborater_id" value="0">
          </div>
        </div>
      </div>
    </div>
  @endif

  @if($project->see_resources())
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
                  </div>
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
              </form>
            </div>
          </div>
          <div class="modal-footer">
            @if($project->modify_resources())
              <button type="button" class="btn btn-primary" id="btn-save-resource" value="add">Save changes</button>
            @endif
            <input type="hidden" id="modalResource_id" name="resource_id" value="0">
          </div>
        </div>
      </div>
    </div>
  @endif
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

      @if($project->is_admin())

      $('.delete_project').on('click', function () {
        if(confirm('Êtes-vous sûr de supprimer ce projet ?')){
          $.ajax({
            type: "DELETE",
            url: '/project/' + {{$project->id}},
            success: function (data) {
              window.location.replace("/home");
            },
            error: function (data) {
              console.log('Error:', data);
            }
          });
        }
      });
      @endif

      @if($project->modify_informations())
      $('.open-modal-informations').click(function(){
        $.get('/project' + '/' + {{$project->id}} + '/' + 'json', function (data) {
          //success data
          $('#update_title').val(data.name);
          $('#update_description').val(data.description);
          $('#update_budget').val(data.budget);
          $('#update_date_begin').val(data.date_begin);
          $('#update_date_end').val(data.date_end);
          $('#update_name_client').val(data.client_name);
          $('#update_tel_client').val(data.client_tel);
          $('#update_email_client').val(data.client_mail);
          $('#modalInformation_id').val({{$project->id}});
          $('#informationsModal').modal('show');
        })
      });

      $("#btn-save-informations").click(function (e) {
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
          }
        })

        e.preventDefault();

        var formData = {
          name: $('#update_title').val(),
          description: $('#update_description').val(),
          budget: $('#update_budget').val(),
          date_begin: $('#update_date_begin').val(),
          date_end: $('#update_date_end').val(),
          client_name: $('#update_name_client').val(),
          client_tel: $('#update_tel_client').val(),
          client_mail: $('#update_email_client').val(),
        }


        var project_id = $('#modalInformation_id').val();;

        var type = "PUT"; //for updating existing resource
        var my_url = '/project/' + project_id;

        $.ajax({

          type: type,
          url: my_url,
          data: formData,
          dataType: 'json',
          success: function (data) {
            console.log(data);

            $('span#view_title').text(data.name);
            $('p#view_description').text(data.description);
            $('p#view_budget').text(data.budget+' CHF');
            $('p#view_date_begin').text(data.date_begin);
            $('p#view_date_end').text(data.date_end);
            $('#frmInformations').trigger("reset");

            $('#informationsModal').modal('hide')
          },
          error: function (data) {
            console.log('Error:', data);
          }
        });
      });

      @endif


      @if($project->see_collaboraters())
      $('.open-modal-collaborater').click(function(){
        var collaborater_id = $(this).val();

        $.get('/collaborater' + '/' + collaborater_id, function (data) {
          //success data
          var $radios = $('input:radio[name=inforadio]');
          if($radios.is(':checked') === false) {
            $radios.filter('[value='+data.informations_rights+']').prop('checked', true);
          }
          $radios = $('input:radio[name=collaboradio]');
          if($radios.is(':checked') === false) {
            $radios.filter('[value='+data.collaboraters_rights+']').prop('checked', true);
          }
          $radios = $('input:radio[name=resoradio]');
          if($radios.is(':checked') === false) {
            $radios.filter('[value='+data.resources_rights+']').prop('checked', true);
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
      @endif


      @if($project->modify_collaboraters())
      $("#btn-save-collaborater").click(function (e) {
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
          }
        })

        e.preventDefault();

        var formData = {
          informations_rights: $('input[name=inforadio]:checked').val(),
          collaboraters_rights: $('input[name=collaboradio]:checked').val(),
          resources_rights: $('input[name=resoradio]:checked').val(),
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
      @endif

      @if($project->see_resources())
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
      @endif

      @if($project->modify_resources())
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
      @endif
    });
    </script>
  @endsection
