$().ready(function(){
	//Colorear botones
	$('.cambioRol, .cambioEstatus').each(function(){
		cambioBool($(this));
		cambioColor($(this));
	});
	
	//Eventos de eliminación
	$('.btn-eliminar').each(function(){
		eliminar($(this));
	});
});