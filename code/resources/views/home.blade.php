@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    Votre id est le suivant : {{ $user->id }}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <a href="/project/create" class="btn btn-primary">Créer un nouveau projet</a>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <ul class="list-group">
          @foreach ($user->projects as $pro)
            <li class="list-group-item"><a href="/project/{{$pro->id}}" >{{$pro->name}}</a></li>
          @endforeach
        </ul>
      </div>
    </div>
</div>
@endsection
