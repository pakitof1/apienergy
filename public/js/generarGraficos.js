
$(document).on('click', '#fechaBtn', function(event) {
    event.preventDefault(); 

    var formatoFecha = /^\d{2}\/\d{2}\/\d{4}$/;

  // Obtener la fecha ingresada por el usuario
  var fecha = $("#fecha").val();

  if(formatoFecha.test(fecha)!== true) {
      alert("Fecha inválida");
      return false;
    }

  // Realizar la solicitud a la API
  $.ajax({
    url: '/api/datos-dia',
    method: 'POST',
    data: { fecha: fecha },
    success: function(response) {

      var datos = response.data;
      var resume = response.resume;

      resume.forEach(function(dato) {
        $('span[data-name="' + dato[0].contador_id + '"].poweravg').text(dato[0].avgPower);
        $('span[data-name="' + dato[0].contador_id + '"].energytotal').text(dato[0].total_energy);
      });      

    generarGraficos(datos, 'power'); // Generar gráficos de power
    generarGraficos(datos, 'energy'); // Generar gráficos de energy

    },
    error: function(jqXHR, textStatus, errorThrown) {
      //console.error(errorThrown);
    }
  });
});


function generarGraficos(datos, tipo) {
  // Objeto para almacenar las series por contador
  var seriesPorContador = {};
  var listHoras = [];
  var categorias;
  var intervalo;

  datos.forEach(function(dato) {
      var contadorId = dato.contador_id;
      var contadorNombre = 'Contador ' + contadorId;

      // Verificar si ya existe una serie para el contador actual
      if (!seriesPorContador.hasOwnProperty(contadorId)) {
          // Si no existe, crear una nueva serie para el contador actual
          seriesPorContador[contadorId] = {
              type: tipo === 'power' ? 'spline' : 'column',
              name: contadorNombre,
              data: [],
              pointWidth: 10
          };
      }
      // Obtener la hora del dato actual para datos horarios de energia
      var fecha = new Date(dato.datetime);
      var hora = fecha.getHours();
      
      // Verificar si ya existe un dato.energy para la hora actual
      var datoHoraActual = seriesPorContador[contadorId].data.find(function(item) {
        return item.name === hora.toString();
      });

      // Si no existe, agregar el dato(data[]) a la serie correspondiente
      if (!datoHoraActual) {
          if (tipo === 'power') {
              seriesPorContador[contadorId].data.push(dato.power);
          } else if (tipo === 'energy') {

            var minutos = fecha.getMinutes();
            var horas = fecha.getHours();

            if (minutos === 59) {//coger el ultimo valor por hora
              listHoras.push((horas+1).toString().padStart(2, '0'));
              seriesPorContador[contadorId].data.push(dato.energy);
            }
          }

      }
  });

  // Obtener las categorías e intervalos para el eje x según el tipo de dato
  if (tipo === 'power') {
      categorias = datos.map(function(dato) {
          var date = new Date(dato.datetime);
          return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
      });
      intervalo = 30;
  } else if (tipo === 'energy') {
      categorias = listHoras;
      intervalo= 1;
    }

  // Configurar el gráfico con Highcharts
  var series = Object.values(seriesPorContador);

   Highcharts.chart(tipo === 'power' ? 'graficoEnergiaContainer' : 'graficoPotenciaContainer', {
      chart: {
          zoomType: 'x',
      },
      title: {
          text: tipo === 'power' ? 'Power (KW)' : 'Energy (KWh)'
      },
      xAxis: {
          categories: categorias,
          tickInterval: intervalo,
      },
      yAxis: {
          title: {
              text: tipo === 'power' ? 'Power' : 'Energy'
          }
      },
      series: series
  });
}



