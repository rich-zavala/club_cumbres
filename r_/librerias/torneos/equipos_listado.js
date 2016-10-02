$().ready(function(){
	var formularioModal = $('#modalFormularioEquipo');
	var formulario = $('#equipoForm');
	var errorAlert = $('#formError');
	
	//Establecer acciones a los botones
	btnEliminar();
	btnEditar();
	
	//Crear un equipo
	$('#crearEquipo').click(function(e){
		e.preventDefault();
		var t = $(this);
		var d = t.data();
		
		//Establecemos los valores al formulario
		$('#formEquipoNombre').val('');
		$('#formEquipoCategoria').val(d.categoria);
		$('#formEquipoId').val(0);
		formularioModal.modal('show').find('.modal-title').text('Creación de nuevo equipo');
		
		//Mostramos/Ocultamos mensajes de error o éxito si es que este formulario ya fue usado anteriormente
		$('#formFooterBotones').show();
		$('#formFooterCerrar, #formSuccess').hide();
		alertErrorHide();
	});
	
	//Validaciones de formulario
	formulario.validator().on('submit', function (e) {
		if(!e.isDefaultPrevented() && !_ajaxBussy_)
		{
			_ajaxBussy_ = true;
			alertErrorHide();
			botonesDeshabilitar();
			
			//Enviar solicitud al servidor
			$.ajax({
				url: _sitePath_ + 'torneo/equipos/submit' + _suffix_,
				data: formulario.serialize(),
				success: function(data){
					if(data.error == 0)
					{
						//Recargar tabla con los cambios aplicados
						/*
						jQuery(x).load < Carga el contenido de un URL dentro del contenedor "x". Si usas el parámetro "#" se cargará únicamente el objeto con el ID establecido
						En este caso quiero obtener la tabla del listado con los nuevos registros. El ID de dicha tabla es "tablaListado".
						Así se reflejarán los cambios en la página sin tener que cargar otra vez.
						*/
						$('#tablaListadoContenedor').load(window.location + " #tablaListado", function(){
							
							//Los botones de la tabla recién cargada no tienen eventos de "click" aplicados, por lo que ejecutamos las siguientes funciones:
							btnEliminar();
							btnEditar();
							
							//Ocultamos los botones de registro y mostramos el mensaje de éxito
							$('#formFooterBotones').hide();
							$('#formFooterCerrar, #formSuccess').show();
						});
					}
					else
						alertErrorMsg( data.msg );
					
					botonesHabilitar();
				},
				error: function(){
					alertErrorMsg(_errorMsg_);
					botonesHabilitar();
				}
			});
			
			return false;
		}
	});
	
	//Eventos de eliminación: Hace que los botones hagan algo
	function btnEliminar()
	{
		$('.btn-eliminar').each(function(){
			eliminar($(this));
		});
	}
	
	//Editar un equipo: Hace que los botones hagan algo
	function btnEditar()
	{
		$('.btn-editar').click(function(e){
			e.preventDefault();
			var t = $(this);
			var d = t.data();
			
			//Establecemos los valores al formulario
			$('#formEquipoNombre').val(d.nombre);
			$('#formEquipoCategoria').val(d.categoria);
			$('#formEquipoId').val(d.id);
			formularioModal.modal('show').find('.modal-title').text('Edición de equipo');
			
			//Mostramos/Ocultamos mensajes de error o éxito si es que este formulario ya fue usado anteriormente
			$('#formFooterBotones').show();
			$('#formFooterCerrar, #formSuccess').hide();
			alertErrorHide();
		});
	}

	//Ocultar alerta de error
	function alertErrorHide(){ errorAlert.hide(); }

	//Llenar alerta de error
	function alertErrorMsg(s){ errorAlert.text(s).show(); }

	//Deshabilitar botones
	function botonesDeshabilitar(){ formulario.find('BUTTON').prop('disabled', true); }

	//Habilitar botones
	function botonesHabilitar(){ formulario.find('BUTTON').prop('disabled', false); }
});