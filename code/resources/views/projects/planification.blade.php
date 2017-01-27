@extends('layouts.app')

@section('content')
  <script type="text/javascript">
    var canModify = {{ $project->modify_gantt() }}
    google.charts.load('current', {'packages':['gantt']});
    google.charts.setOnLoadCallback(drawChart);
    function daysToMilliseconds(days) {
      return days * 24 * 60 * 60 * 1000;
    }

    function drawChart() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Task ID');
      data.addColumn('string', 'Task Name');
      data.addColumn('string', 'Resource');
      data.addColumn('date', 'Start Date');
      data.addColumn('date', 'End Date');
      data.addColumn('number', 'Duration');
      data.addColumn('number', 'Percent Complete');
      data.addColumn('string', 'Dependencies');

      $('#listTasks').empty();
      $('#listTasks').append('<tbody></tbody>');
      $.get('/project' + '/' + {{$project->id}} + '/' + 'tasks', function (tasks) {
        var heightTot = 50;
        // Pour chaque tâche
        $.each(tasks, function(i, task){
          /************* Création des entrées pour le Gantt Chart ******************/
          var resources = "";
          // Pour chaque ressource de la tâche
          $.each(task.resources, function(j, resource){
            resources += resource.firstname+",";
          });
          resources = resources.replace(/,\s*$/, "");
          var dependencies = "";

          // Pour chaque dépendance de la tâche
          $.each(task.dependencies, function(k, dependencie){
            dependencies += dependencie.predecessor_id.toString()+",";
          });
          dependencies = dependencies.replace(/,\s*$/, "");
          var date = task.date_begin_plan;
          var date_array = date.split("-");
          data.addRow([task.id.toString(), task.title, resources,
           new Date(date_array[0], date_array[1]-1, date_array[2]), null, daysToMilliseconds(task.duration_plan),  task.percent_done,  dependencies]);
          /************* Fin création des entrées pour le Gantt Chart *************/
          var line = '<tr id="gantttask'+task.id+'" >';
          line += '<td id="gantttask'+task.id+'Name">'+task.title+'</td>';
          line += '<td><button class="btn btn-warning btn-xs btn-detail open-modal-gantttask" value="'+ task.id +'">Voir/Modifier</button>';
          if(canModify){
            line += '<button class="btn btn-xs btn-danger btn-delete delete-gantttask" value="'+ task.id +'">Supprimer</button>';
          }
          line += '</td></tr>';
          $('#listTasks > tbody').append(line);
          /************* Mise à jour des champs de la liste ******************/
           heightTot += 42;
        });
        var widthTot = $('#container_gantt').width() * ($('#widthGantt').val()/100);
        if(heightTot < 200){
          heightTot = 200;
        }
        var options = {
          height: heightTot,
          width: widthTot
        };

        var chart = new google.visualization.Gantt(document.getElementById('chart_div'));

        chart.draw(data, options);
      });
    }
  </script>
<div class="container">
  <div class="row">
    <h2><b>{{ $project->name }}</b> | Planification </h2>
    <hr/>

    <div class="col-md-12">
      <h3>Gantt</h3>
      <div style="overflow-x:scroll;overflow-y:scroll" class="col-md-12" id="container_gantt">
        <div id="chart_div"></div>
      </div>
      <div class="col-md-12">
        <div class="col-md-2">
          <button class="btn btn-primary" onclick="drawChart();">Mettre à jour</button>
        </div>
        <div class="col-md-10">
          <input type="range" class="form-control" id="widthGantt" min="100" max="1000" value="100" step="10" />
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <hr/>
      @if($project->modify_gantt())
        <a href="#" value="0" class="btn btn-success open-modal-gantttask">Ajouter une nouvelle tâche</a> <!--Open Modal-->
      @endif
      @if($project->see_gantt())
      <h2>Tâches</h2>
      <table class="table" id="listTasks">
        <tr>
          <th>Nom</th>
          <th>Action</th>
        </tr>
        <tbody>
          <tr><td>test</td></tr>
        </tbody>
        @foreach ($project->gantttasks as $task)
        <tr id="gantttask{{$task->id}}">
          <td id="gantttask{{$task->id}}Name">{{$task->title}}</td>
          <td>
            <button class="btn btn-warning btn-xs btn-detail open-modal-gantttask" value="{{$task->id}}">Voir/Modifier</button>
            @if ($project->modify_gantt())
            <button class="btn btn-xs btn-danger btn-delete delete-gantttask" value="{{$task->id}}">Supprimer</button>
            @endif
          </td>
        </tr>
      @endforeach
      </table>
      @endif
      <!-- END : Task -->
    </div>
  </div>
</div>


@if($project->see_gantt())
<!--******************************MODALS EDIT TASK*****************************-->
<div class="modal fade" id="gantttaskModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Edition de la tâche<span id="modTaskName"></span></h4>
            </div>
            <div class="modal-body">
              <div class="container-fluid">
                <form id="frmGantttask" name="frmGantttask" class="form-horizontal" novalidate="">
                  <div class="row">
                  <div class="col-md-8">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <input type="hidden" name="project_id" id="project_id" value="{{$project->id}}">
                  <div class="form-group">
                    <label for="title">Titre</label>
                    <input id="title" type="text" name="title" class="form-control" required/ >
                  </div>
                </div>
                <div class="col-md-4">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                  <label for="order_id">Ordre</label>
                  <input id="order_id" type="number" name="order_id" class="form-control"/ >
                </div>
              </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control"></textarea>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-8">
                  <div class="form-group">
                    <label for="resources">Ressources</label>
                    <select id="resources_select" name="resources" class="selectpicker form-control" multiple>

                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="color">Couleur</label>
                    <input id="color" type="color" name="color" class="form-control"/ >
                  </div>
                </div>
              </div>
                  <div class="row">
                    <div class="col-md-8">
                      <div class="form-group">
                        <label for="cost_initial">Dépend de </label>
                        <!--<input id="cost_initial" type="number" name="cost_initial" class="form-control"/ >-->
                        <select id="depends" class="selectpicker form-control" multiple>

                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="parent">Parent</label>
                        <select id="parent" type="number" name="cost_per_hour" class="form-control">

                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <h4>Planification</h4>
                      <hr/>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="date_begin_plan">Date de début</label>
                        <input id="date_begin_plan" type="date" name="date_begin_plan" class="form-control" required/ >
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="duration_plan">Nombre de jours</label>
                        <input id="duration_plan" type="number" name="duration_plan" class="form-control" required/ >
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="hours_plan">Nombre d'heures</label>
                        <input id="hours_plan" type="number" name="hours_plan" class="form-control" required/ >
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <h4>Effective</h4>
                      <hr/>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="date_begin_real">Date de début</label>
                        <input id="date_begin_real" type="date" name="date_begin_real" class="form-control"/ >
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="duration_real">Nombre de jours</label>
                        <input id="duration_real" type="number" name="duration_real" class="form-control"/ >
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="hours_real">Nombre d'heures</label>
                        <input id="hours_real" type="number" name="hours_real" class="form-control"/ >
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">

                      Pourcentage terminé : <input type="range" id="percent_done" value="0" min="0" max="100"/>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <div class="modal-footer">
                @if($project->modify_gantt())
                <button type="button" class="btn btn-primary" id="btn-save-gantttask" value="add">Save changes</button>
                @endif
                <input type="hidden" id="modalGantttask_id" name="gantttask_id" value="0">
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
    @if($project->see_gantt())
    $('body').on('click', '.open-modal-gantttask', function() {
        var gantttask_id = $(this).val();
        // Ici on va remplir le select des ressources.
        $.get('/project/{{$project->id}}/resources', function(resources){
            var resources_select = $('#resources_select');
            resources_select.html('');
            $.each(resources, function (i, resource) {
                resources_select.append($('<option>', {
                    value: resource.id,
                    text: resource.firstname
                }));
            });
            // On met à jour l'affichage
            $('#resources_select').selectpicker('refresh');
        });

        // Ici on va remplir les selects des activités.
        $.get('/project/{{$project->id}}/tasks', function(tasks){
            var depends_select = $('#depends');
            var parent_select = $('#parent');
            depends_select.html('');
            parent_select.html('');
            parent_select.append($('<option>',{
              value: 0,
              text: "Aucun"
            }));
            $.each(tasks, function(i, task){
              if(task.id != gantttask_id){
                  // Depends
                  depends_select.append($('<option>',{
                    value: task.id,
                    text: task.title
                  }));
                  //Parent
                  parent_select.append($('<option>',{
                    value:task.id,
                    text: task.title,
                  }))
              }
            });
            depends_select.selectpicker('refresh');
        });

        if(gantttask_id == 0){
          // On prépare le formulaire d'AJOUT
          $('#modalGantttask_id').val(0);
          $('#title').val("");
          $('#order_id').val("");
          $('#description').val("");
          $('#color').val("#FFFFFF");
          $('#date_begin_plan').val("");
          $('#duration_plan').val("");
          $('#hours_plan').val("");
          $('#date_begin_real').val("");
          $('#duration_real').val("");
          $('#hours_real').val("");
          $('#percent_done').val(0);
        }else{
          // On prépare le formulaire d'EDITION
          $.get('/gantttask' + '/' + gantttask_id, function (data) {
            console.log(data.resources);
              $('#modalGantttask_id').val(gantttask_id);
              $('#title').val(data.title);
              $('#order_id').val(data.order_id);
              $('#description').val(data.description);
              $('#color').val(data.color);
              $('#date_begin_plan').val(data.date_begin_plan);
              $('#duration_plan').val(data.duration_plan);
              $('#hours_plan').val(data.hours_plan);
              $('#date_begin_real').val(data.date_begin_real);
              $('#duration_real').val(data.duration_real);
              $('#hours_real').val(data.hours_real);
              $('#percent_done').val(data.percent_done);
              $("#parent option[value='"+data.parent_id+"']").prop('selected', true);
              var id_resources = [];
              $.each(data.resources, function(i, resource){
                id_resources.push(resource.id);
              });
              $('#resources_select').val(id_resources);
              $('#resources_select').selectpicker('refresh');
              var id_dependencies = [];
              $.each(data.dependencies, function(i, dependencie){
                id_dependencies.push(dependencie.predecessor_id);
              });
              $('#depends').val(id_dependencies);
              $('#depends').selectpicker('refresh');
          })
        }
        $('#gantttaskModal').modal('show');


      });
        @endif

        @if($project->modify_gantt())
        $("#btn-save-gantttask").click(function (e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            })

            e.preventDefault();

            var resources_selected = [];
              $('#resources_select :selected').each(function(i, selected){
                resources_selected[i] = $(selected).val();
              });

            var dependencies_selected = [];
            $('#depends :selected').each(function(i, selected){
              dependencies_selected[i] = $(selected).val();
            });

            var selectedValues = $('#depends').val();

            var formData = {
                title : $('#title').val(),
                project_id : $('#project_id').val(),
                order_id: $('#order_id').val(),
                description: $('#description').val(),
                color: $('#color').val(),
                resources: resources_selected,
                dependencies: dependencies_selected,
                parent_id: $('#parent').val(),
                date_begin_plan: $('#date_begin_plan').val(),
                duration_plan: $('#duration_plan').val(),
                hours_plan: $('#hours_plan').val(),
                date_begin_real: $('#date_begin_real').val(),
                duration_real: $('#duration_real').val(),
                percent_done: $('#percent_done').val(),
                hours_real: $('#hours_real').val(),
            }


            var gantttask_id = $('#modalGantttask_id').val();

            if(gantttask_id != 0){
              var type = "PUT"; // Update
              var my_url = '/project/{{$project->id}}/gantttask/' + gantttask_id;
            }else{
              console.log("CREATE");
              var type = "POST"; // Create
              var my_url = '/project/{{$project->id}}/gantttask';
            }

            $.ajax({
                type: type,
                url: my_url,
                data: formData,
                dataType: 'json',
                success: function (data) {
                    $('#gantttaskModal').modal('hide')
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
            drawChart();
        });




    $('body').on('click', '.delete-gantttask', function() {
      if(confirm("Voulez-vous vraiment supprimer cette tâche ?")){
        var gantttask_id = $(this).val();

        $.ajax({
            type: "DELETE",
            url: '/gantttask/' + gantttask_id,
            success: function (data) {

            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
        drawChart();
      }
    });
    @endif
  });
</script>
@endsection
