var jugadoresData = [];
var san_nombre;
$().ready(function(){
	san_nombre = $("#jugadorNombre").prop('disabled', true);
	
	//Obtener lista de jugadores
	$.get(_sitePath_ + 'torneo/sanciones/jugadores_listado/' + san_nombre.data('torneo') + _suffix_, function(data){
		san_nombre.prop('disabled', false);
		jugadoresData = data;
    san_nombre.typeahead({
			source: jugadoresData,
			afterSelect: jugadorSeleccionar,
			highlighter: jugadorHighlight
		});
	},'json');
	
	//Template para mostrar el nombre y equipo en el autoselect
	function jugadorHighlight(item){
		var result = $.grep(jugadoresData, function(e){
			return e.name == item;
		})[0];
		return '<div><b>' + result.name + '</b> <span class="pull-right label label-default marginLeft10 padding5 font11">' + result.NomEquipo + '</span></div>';
	}
	
	//Evento de consulta de información del jugador
	function jugadorSeleccionar(item){
		if(parseInt(item.ID_Jugador) > 0)
		{
			san_nombre.prop('disabled', true);
			window.location = _sitePath_ + 'torneo/sanciones/registro/' + san_nombre.data('torneo') + '/' + item.ID_Jugador + _suffix_
		}
	}
});