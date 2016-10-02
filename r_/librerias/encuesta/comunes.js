$().ready(function(){
	//Crear un torneo
	$('#crearEncuesta').click(function(e){
		e.preventDefault();	
		
		$('#modalFormulario').modal('show').find('.modal-title').text('Creación de nuevo encuesta');
	});
	
	//Editar un torneo
	//$('#editarTorneo').click(function(e){
	//	e.preventDefault();	
		// $('#formAgno').val(currAgno);
	//	$('#modalFormulario').modal('show').find('.modal-title').text('Creación de nuevo torneo');
	//});
	
	//Eventos de eliminación
	$('.btn-eliminar').each(function(){
		eliminar($(this));
	});
	
	//Validaciones de formulario
	$('#usuarioForm').validator().on('submit', function (e) {
		if(!e.isDefaultPrevented())
		{
			$(this).find('BUTTON').prop('disabled', true);
			return true;
		}
	});
	
	//Evento de botón
	$('.formNuevoSubmit').click(function(e){
		e.preventDefault();
		$(this).parents('FORM:first').submit();
	});
	
	//Configurar modal
	$('.modal').on('shown.bs.modal', function () {
		$(this).find('INPUT, SELECT').first().focus().select();
	});
	
	//Ocultar alertas
	setTimeout(function(){ $('.autoHide').hide('clip'); }, 3000);
	
	setDatePicker();
	setTimePicker();
	
	//Tooltips
	$('[data-toggle="tooltip"]').tooltip();
});
	
//Campo de fecha
