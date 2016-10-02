$().ready(function(){
	//Eventos de eliminación: Hace que los botones hagan algo
	function btnEliminar()
	{
		$('.btn-eliminar').each(function(){
			eliminar($(this));
		});
	}
	
	btnEliminar();
});