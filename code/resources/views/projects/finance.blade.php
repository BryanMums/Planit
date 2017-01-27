@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <h2><b>{{ $project->name }}</b> | Finance </h2>
        <hr/>
        <!-- Colonne centrale -->
        <div class="col-md-10 col-md-offset-1">
            <h2>Budget</h2>
            <p>{{ $project->budget }} CHF</p>

            <h2>Les coûts</h2>
            <table class="table">
              <tr>
                <th>Titre</th>
                <th>Description</th>
                <th>Coût</th>
                <th></th>
              </tr>
              @foreach ($project->costs as $cost)
                <tr id="cost{{$cost->id}}">
                  <td id="cost{{$cost->id}}Name">{{$cost->name}}</td>
                  <td id="cost{{$cost->id}}Description">{{$cost->description}}</td>
                  <td id="cost{{$cost->id}}Value">{{$cost->value}}</td>
                  <td>
                    @if ($project->modify_finance())
                      <button class="btn btn-warning btn-xs btn-detail open-modal-cost" value="{{$cost->id}}">Modifier</button>
                      <button class="btn btn-xs btn-danger btn-delete delete-cost" value="{{$cost->id}}">Supprimer</button>
                    @endif
                  </td>
                </tr>
              @endforeach
              <tr>
                <td colspan="2"><b>Coût total</b></td>
                <td id="costsTotal"><b>{{$project->costs->sum('value')}}</b></td>
              </tr>
            </table>
            @if ($project->modify_finance())
              <a href="/project/{{ $project->id }}/cost/create" class="btn btn-success">Ajouter un nouveau coût</a>
            @endif
        </div>
    </div>
</div>
<meta name="_token" content="{!! csrf_token() !!}" />



<!--******************************MODALS EDIT COST*****************************-->
<div class="modal fade" id="costModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Edition du coût <span id="modColName"></span></h4>
            </div>
            <div class="modal-body">
              <div class="container-fluid">
                <form id="frmCosts" name="frmCosts" class="form-horizontal" novalidate="">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <input type="hidden" name="project_id" value="{{$project->id}}">
                  <div class="form-group">
                    <label for="name">Titre</label>
                    <input id="name" type="text" name="name" class="form-control"/  required>
                  </div>
                  <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control"></textarea>
                  </div>
                  <div class="form-group">
                    <label for="value">Coût</label>
                    <input id="value" type="number" name="value" class="form-control"/  required>
                  </div>
                </form>
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-save-cost" value="add">Enregistrer</button>
                <input type="hidden" id="modalCost_id" name="cost_id" value="0">
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

$('.open-modal-cost').click(function(){
        var cost_id = $(this).val();

        $.get('/cost' + '/' + cost_id, function (data) {
            //success data
            $('#name').val(data.name);
            $('#description').val(data.description);
            $('#value').val(data.value);
            $('#modalCost_id').val(cost_id);
            $('#costModal').modal('show');
        })
    });
//delete task and remove it from list
    $('.delete-cost').click(function(){
      if(confirm("Voulez-vous vraiment supprimer ce coût ?")){
        var cost_id = $(this).val();

        $.ajax({
            type: "DELETE",
            url: '/cost/' + cost_id,
            success: function (data) {
                console.log(data);

                $("#cost" + cost_id).remove();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
      }
    });



        //create new task / update existing task
        $("#btn-save-cost").click(function (e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            })

            e.preventDefault();

            var formData = {
              name: $('#name').val(),
              description: $('#description').val(),
              value: $('#value').val(),
            }


            var cost_id = $('#modalCost_id').val();;

            var type = "PUT"; //for updating existing resource
            var my_url = '/cost/' + cost_id;

            $.ajax({

                type: type,
                url: my_url,
                data: formData,
                dataType: 'json',
                success: function (data) {

                  $('#cost'+ cost_id+'Name').html(data.name);
                  $('#cost'+ cost_id+'Description').html(data.description);
                  $('#cost'+ cost_id+'Value').html(data.value);



                    $('#frmCost').trigger("reset");

                    $('#costModal').modal('hide')
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        });

  });
</script>
@endsection
