@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <h2>Accueil</h2>
          <p>Bienvenue sur votre page <b> {{ $user->name }}</b></p>
    </div>
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <h4>Vos projets</h4>
        <table class="table">

        @foreach ($user->projects as $pro)
          <tr>
            <td><h5><a href="/project/{{$pro->id}}" >{{$pro->name}}</a></h5></td>
          </tr>
        @endforeach
      </table>
        <a href="/project/create" class="btn btn-primary">Créer un nouveau projet</a>
        <br/>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <h4>Projets où vous êtes collaborateur</h4>
        <table class="table">
        @foreach ($user->projectsCol as $pro)
          <tr>
            <td><h5><a href="/project/{{$pro->id}}" >{{$pro->name}}</a></h5></td>
          </tr>
        @endforeach
      </table>
    </div>
</div>
@endsection
