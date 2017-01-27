@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <h2>Création d'un nouveau projet</h2>
          <form method="POST" action="/project/create">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
              <label for="name">Titre du projet</label>
              <input type="text" name="name" class="form-control"/ required >
            </div>
            <div class="form-group">
              <label for="description">Description</label>
              <textarea name="description" class="form-control"></textarea>
            </div>
            <div class="form-group col-md-4">
              <label for="budget">Budget</label>
              <input type="number" name="budget" class="form-control"/ required >
            </div>
            <div class="form-group col-md-4">
              <label for="date_begin">Date de début</label>
              <input type="date" name="date_begin" class="form-control"/ required >
            </div>
            <div class="form-group col-md-4">
              <label for="date_end">Date de fin</label>
              <input type="date" name="date_end" class="form-control"/ required >
            </div>
            <hr/>
            <h3>Informations sur le client</h3>
            <h5>Laisser vide si projet personnel</h5>
            <div class="form-group col-md-4">
              <label for="client_name">Nom du client</label>
              <input type="text" name="client_name" class="form-control"/ >
            </div>
            <div class="form-group col-md-4">
              <label for="client_mail">Email du client</label>
              <input type="email" name="client_mail" class="form-control"/ >
            </div>
            <div class="form-group col-md-4">
              <label for="client_tel">Téléphone du client</label>
              <input type="text" name="client_tel" class="form-control"/ >
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-primary">Créer</button>
              <a href="/home" class="btn btn-default">Annuler</a>
            </div>
          </form>
        </div>
    </div>
</div>
@endsection
