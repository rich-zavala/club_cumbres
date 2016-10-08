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
	
	//Crear
	$('#crearArbitro').click(function(e){
		e.preventDefault();
		$('#arbitroForm')[0].reset();
		$('#formId').val(0); //Índice de nuevo registro
		$('#modalFormulario').modal('show').find('.modal-title').text('Regístro de árbitro');
	});
	
	//Editar
	$('.btn-editar').click(function(e){
		e.preventDefault();
		var d = $(this).data('info');
		$('#formNombre').val(d.nombre);
		$('#formTelefono').val(d.telefono);
		$('#formId').val(d.id);
		$('#modalFormulario').modal('show').find('.modal-title').text('Edición de árbitro');
	});
	
	//Validaciones de formulario
	$('#arbitroForm').validator().on('submit', function (e) {
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
		setDatePicker();
		setTimePicker();
	});
});