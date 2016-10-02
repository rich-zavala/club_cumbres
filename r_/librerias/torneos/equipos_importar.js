$().ready(function(){
	var formularioModal = $('#modalFormularioImportar');
	var formulario = $('#modalFormulario');
	var errorAlert = $('#formError');
	var workingAlert = $('#formWorking');
	var equipoOriginal;
	
	//Establecer acciones a los botones
	btnImportar();
	
	//Validaciones de formulario
	formulario.validator().on('submit', function (e) {
		if(!e.isDefaultPrevented() && !_ajaxBussy_)
		{
			_ajaxBussy_ = true;
			alertErrorHide();
			botonesDeshabilitar();
			workingAlert.show();
			
			//Enviar solicitud al servidor
			$.ajax({
				url: _sitePath_ + 'torneo/equipos/importar_submit' + _suffix_,
				data: formulario.serialize(),
				success: function(data){
					if(data.error == 0)
					{
						equipoOriginal.addClass('disabled').parents('LI:first').addClass('text-muted');
						$('#tablaListadoContenedor').load(window.location + " #tablaListado", function(){
							$('#formFooterBotones').hide();
							workingAlert.hide();
							$('#formFooterCerrar, #formSuccess').show();
						});
					}
					else
					{
						alertErrorHide();
						alertErrorMsg( data.msg );
					}
					
					botonesHabilitar();
				},
				error: function(){
					alertErrorHide();
					alertErrorMsg(_errorMsg_);
					botonesHabilitar();
				}
			});
			
			return false;
		}
	});
	
	//Editar un equipo: Hace que los botones hagan algo
	function btnImportar()
	{
		$('.btn-importar').click(function(e){
			e.preventDefault();
			var t = $(this);
			var d = t.data();
			equipoOriginal = t; //Asignar variable global
			
			//Establecemos los valores al formulario
			$('#modalEquipoNombre').text(d.equipo);
			$('#modalTorneoNombre').text(d.torneo);
			$('#formEquipoId').val(d.id);
			formularioModal.modal('show').find('.modal-title').text('Importación de equipo');
			
			//Mostramos/Ocultamos mensajes de error o éxito si es que este formulario ya fue usado anteriormente
			$('#formFooterBotones').show();
			$('#formFooterCerrar, #formSuccess').hide();
			alertErrorHide();
		});
	}

	//Ocultar alerta de error
	function alertErrorHide(){
		errorAlert.hide();
		workingAlert.hide();
	}

	//Llenar alerta de error
	function alertErrorMsg(s){ errorAlert.html('<i class="fa fa-warning"></i> ' + s).show(); }

	//Deshabilitar botones
	function botonesDeshabilitar(){ formulario.find('BUTTON').prop('disabled', true); }

	//Habilitar botones
	function botonesHabilitar(){ formulario.find('BUTTON').prop('disabled', false); }
});