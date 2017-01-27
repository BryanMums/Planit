@extends('layouts.app')

@section('content')

<div class="container">
  <div class="row">
    <h2><b>{{ $project->name }}</b> | Statistiques </h2>
    <hr/>
    <div class="row">
      <div class="col-md-12">
        <h3>Avancement du projet dans le temps</h3>
        <div class="progress">
          @if ($days[0] != 0)
            @if ($days[1] < 0)
              <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;background:#2980b9;">
                0%
              </div>
            @elseif($days[1] > $days[0])
              <div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;background:#2980b9;">
                100%
              </div>
            @else
              <div class="progress-bar" role="progressbar" aria-valuenow="{{$days[1]}}" aria-valuemin="0" aria-valuemax="{{$days[0]}}" style="width: {{($days[1]/$days[0])*100}}%;background:#2980b9;">
                {{number_format(($days[1]/$days[0])*100, 0)}}%
              </div>
            @endif

          @endif
        </div>
      </div>
      <div class="col-sm-4">
        <div class="card">
          <div class="card-content">
            <h3>{{$project->date_begin}}</h3>
          </div>
          <div class="card-title">
            Date de début
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="card">
          <div class="card-content">
            <h3 id="today"></h3>

            <script>
              var d = new Date();
              var month = ("0" + (d.getMonth() + 1)).slice(-2); //months from 1-12
              var day = ("0" + d.getDate()).slice(-2);
              var year = d.getFullYear();
              document.getElementById("today").innerHTML = year + "-" + month + "-" + day;
            </script>
          </div>
          <div class="card-title">
            Aujourd'hui
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="card">
          <div class="card-content">
            <h3>{{$project->date_end}}</h3>
          </div>
          <div class="card-title">
            Date de fin
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12">
        <h3>Répartition du travail</h3>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-content">
            <div id="chart_resources_hours_plan" style="width: 100%; height: auto;"></div>
          </div>
          <div class="card-title">
            Répartition Fictive
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-content">
            <div id="chart_resources_hours_real" style="width: 100%; height: auto;"></div>
          </div>
          <div class="card-title">
            Répartition Effective
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="row">
    <div class="col-md-12">
      <h3>Répartitions des coûts</h3>
      <div class="card" style="">
        <div class="card-content" style="padding-right:20px;padding-left:20px;overflow-x:scroll;">
          <div id="dual_y_div" style="width: 100%; height: 500px;"></div>
        </div>
        <div class="card-title">
          Répartition des coûts
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
      google.charts.load("current", {packages:["corechart", "bar"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Resources', 'Hours planified'],
          @foreach ($resources_hours as $resource)
            ['{{$resource[0]}}', {{$resource[1]}}],
          @endforeach
        ]);

        var options = {
          pieHole: 0.4,
          pieSliceText: 'none',
          title : 'Nombre d\'heures',
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart_resources_hours_plan'));
        chart.draw(data, options);
      }
    </script>
    <script type="text/javascript">
        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
          var data = google.visualization.arrayToDataTable([
            ['Resources', 'Hours planified'],
            @foreach ($resources_hours as $resource)
              ['{{$resource[0]}}', {{$resource[2]}}],
            @endforeach
          ]);

          var options = {
            pieHole: 0.4,
            pieSliceText: 'none',
            title : 'Nombre d\'heures',
          };

          var chart = new google.visualization.PieChart(document.getElementById('chart_resources_hours_real'));
          chart.draw(data, options);
        }
      </script>

      <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawStuff);

      function drawStuff() {
        var data = new google.visualization.arrayToDataTable([
          ['Coûts', 'Fictif', 'Effectif'],
          @foreach ($costs as $cost)
          ['{{$cost[0]}}', {{$cost[1]}}, {{$cost[2]}}],
          @endforeach
        ]);

        var options = {
          width: 900,
          chart: {
            title: 'Coûts du projet',
            subtitle: 'Les coûts fictifs et effectifs des ressources ainsi que des achats.'
          },
          series: {
            0: { axis: 'distance' }, // Bind series 0 to an axis named 'distance'.
            1: { axis: 'distance' } // Bind series 1 to an axis named 'brightness'.
          },
          axes: {
            y: {
              distance: {label: 'CHF'}, // Left y-axis.
            }
          }
        };

      var chart = new google.charts.Bar(document.getElementById('dual_y_div'));
      chart.draw(data, options);
    };
    </script>
@endsection
