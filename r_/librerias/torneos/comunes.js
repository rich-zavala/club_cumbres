/*
Funciones comunes de torneos
*/
var currAgno = new Date();
currAgno = currAgno.getFullYear();

$().ready(function(){
	//Crear un torneo
	$('#crearTorneo').click(function(e){
		e.preventDefault();	
		$('#formAgno').val(currAgno);
		$('#formId').val(0); //Índice de nuevo registro
		$('#modalFormulario').modal('show').find('.modal-title').text('Creación de nuevo torneo');
	});
	
	//Editar un torneo
	$('#editarTorneo').click(function(e){
		e.preventDefault();	
		// $('#formAgno').val(currAgno);
		$('#modalFormulario').modal('show').find('.modal-title').text('Edición de torneo');
	});
	
	//Validaciones de formulario
	$('#torneoForm').validator().on('submit', function (e) {
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
	
	//Ocultar alertas
	setTimeout(function(){ $('.autoHide').hide('clip'); }, 3000);
	
	setDatePicker();
	setTimePicker();
	
	//Tooltips
	$('[data-toggle="tooltip"]').tooltip();
	
	//Eliminar categoría
	function btnEliminarCat()
	{
		$('.btn-eliminar-cat').each(function(){
			eliminar($(this), undefined);
		});
	}
	btnEliminarCat();
	
	//Partidos por cancha
	$('#partidosPorCancha').click(function(e){
		e.preventDefault();
		$('#modalFormularioPartidosCancha').modal('show');
	});
	
	//Reporte de salarios
	$('#reporteSueldos').click(function(e){
		e.preventDefault();
		$('#modalFormularioSueldos').modal('show');
	});
});
	
//Campo de fecha
function setDatePicker()
{
	try
	{
		$('.date').datepicker({
			format: 'yyyy-mm-dd',
			autoclose: true,
			language: 'es',
			forceParse: true
		}).attr('placeholder', 'yyyy-mm-dd').attr('readonly', fale);
	} catch(e){ c('No JS > datepicker'); }
}

//Campo de time
function setTimePicker()
{
	try
	{
		$('.time').blur(function(){
			setTime(this);
		}).each(function(){ setTime(this); }).timepicker({
			disableTextInput: true,
			timeFormat: 'H:i:s',
			selectOnBlur: true,
			show2400: true,
			step: 15,
			orientation: 'top',
			language: 'es'
		});
	} catch(e){ c('No JS > timepicker'); }
}

!function(a){a.fn.datepicker.dates.es={days:["Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado"],daysShort:["Dom","Lun","Mar","Mié","Jue","Vie","Sáb"],daysMin:["Do","Lu","Ma","Mi","Ju","Vi","Sa"],months:["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],monthsShort:["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],today:"Hoy",clear:"Borrar",weekStart:1,format:"dd/mm/yyyy"}}(jQuery);