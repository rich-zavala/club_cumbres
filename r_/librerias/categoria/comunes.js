/*
Funciones comunes de torneos
*/
var currAgno = new Date();
currAgno = currAgno.getFullYear();

$().ready(function(){
	
	
	//Editar Categoría
	$('.editarCategoria').click(function(e){
		e.preventDefault();	
		var data = $(this).data();
		$('#formNombre').val(data.valor);
		$('#formId').val(data.id);
		$('#modalFormulario').modal('show').find('.modal-title').text('Editar categoría');
	});
	
	//Validaciones de formulario
	$('#categoriaForm').validator().on('submit', function (e) {
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
		$(this).find('INPUT:first').focus().select();
	});
	
	//Ocultar alertas
	setTimeout(function(){ $('.autoHide').hide('clip'); }, 3000);
});