@extends('layouts.app')

@section('content')
<div class="container-fluid vh-100">
  <div class="row d-flex">
    <div class="col-lg-3 col-md-4 sidebar flex-grow-1">
      <!-- Contenido del sidebar (formulario de fecha) -->
      <h4>Contadores</h4>
      <div class="input-group">
        <input type="text" class="form-control" id="fecha" placeholder="Seleccione una fecha">
        <div class="input-group-append">
          <button class="btn btn-outline-secondary" type="button" id="fechaBtn">Calcular</button>
        </div>
      </div>
      
      <!-- Cuadrantes de contadores -->
      <div class="row">
      @foreach ($contadores as $contador)
        <div class="col-lg-6 col-md-4 ">
          <div class="card mt-3">
            <div class="card-body contador">
              <h5 class="card-title">{{ $contador->nombre }}</h5>
              <p class="card-text">
                <p>Power: <span data-name="{{ $contador->id }}" class="poweravg">--</span> kW</p>
                <p>Energy: <span data-name="{{ $contador->id }}" class="energytotal">--</span> kWh</p>
              </p>
            </div>
          </div>
        </div>
        @endforeach

      </div>
    </div>
    
    <div class="col-lg-9 col-md-8 content flex-grow-1">
      <!-- Contenido principal (gráficas) -->
      <h4>Datos Gráficos</h4>
      
      <div id="graficosContainer">
        <div id="graficoEnergiaContainer"></div>
        <div id="graficoPotenciaContainer"></div>
      </div>
    </div>
  </div>
</div>



@endsection



