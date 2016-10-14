$().ready(function(){
	var formularioModal = $('#modalFormularioCrear');
	var formulario = $('#jornadaForm');
	/*var errorAlert = formulario.find('.form-error');
	var formWait = formulario.find('.form-wait').hide();*/
	var formSuccess = formulario.find('.form-success').hide();
	var errorAlert = $('.modal .form-error');
	var formWait = $('.modal .form-wait').hide();
	var formSuccess = $('.modal .form-success').hide();

	//Recargador de formulario de partido
	function recargarFormularioPartido()
	{
		$('#modalFormularioPartido').load(window.location + " #modalFormularioPartidoContenido", function(){
			accionesInicializar();
		});
	}

	//Inicializar acciones
	function accionesInicializar()
	{
		btnEliminar();
		btnEditar();
		jornadaCrear();
		partidoEliminar();
		partidoFormulario();
		$('[data-toggle="tooltip"]').tooltip();
		$('.partido-no-editable').unbind('click').click(function(){ alert($(this).attr('title')); });
	}
	accionesInicializar();

	//Crear una jornada
	function jornadaCrear()
	{
		$('.btn-jornada-crear').unbind('click').click(function(e){
			e.preventDefault();
			var t = $(this);
			var d = t.data();

			//Establecemos los valores al formulario
			$('#formNombre').val('');
			$('#formId').val(0);
			formularioModal.modal('show').find('.modal-title').text('Creación de nueva jornada');

			//Mostramos/Ocultamos mensajes de error o éxito si es que este formulario ya fue usado anteriormente
			$('.form-footer-botones').show();
			$('.form-footer-cerrar, .form-success').hide();
			alertErrorHide();
		});
	}

	//Habilitar formulario
	formularioModal.on('show.bs.modal', function (e) {
		$('#formNombre').prop('disabled', false).focus();
	});

	//Validaciones de formulario
	formulario.validator().submit(function (e) {
		if(!e.isDefaultPrevented() && !_ajaxBussy_)
		{
			_ajaxBussy_ = true;
			alertErrorHide();
			botonesDeshabilitar();
			formWait.show();
			formSuccess.hide();
			var t = formularioModal;

			//Enviar solicitud al servidor
			$.ajax({
				url: _sitePath_ + 'torneo/partidos/jornada_submit' + _suffix_,
				data: formulario.serialize(),
				success: function(data){
					if(data.error == 0)
					{
						//Recargar página
						$('#infoContenedor').load(window.location + " #infoContenedor", function(){
							t.find('.form-footer-botones').hide();
							t.find('.form-footer-cerrar, .form-success').show();
							accionesInicializar();
						});

						//Deshabilitar formulario
						$('#formNombre').prop('disabled', true);

						//Recargar formulario de partido (para incluir la nueva jornada)
						recargarFormularioPartido();
					}
					else
						alertErrorMsg( data.msg );
				},
				error: function(){ alertErrorMsg(_errorMsg_); },
				complete: function(){
					botonesHabilitar();
					formWait.hide();
					_ajaxBussy_ = false;
				}
			});

			return false;
		}
		e.preventDefault();
	});

	//Eventos de eliminación: Hace que los botones hagan algo
	function btnEliminar()
	{
		$('.btn-eliminar').each(function(){
			eliminar($(this));
		});

		$('.btn-jornada-eliminar').each(function(){
			eliminar($(this), function(){ recargarFormularioPartido(); });
		});
	}

	//Editar un equipo: Hace que los botones hagan algo
	function btnEditar()
	{
		$('.btn-jornada-editar').unbind('click').click(function(e){
			e.preventDefault();
			var t = $(this);
			var d = t.data();

			//Establecemos los valores al formulario
			$('#formNombre').val(d.nombre);
			$('#formId').val(d.id);
			formularioModal.modal('show').find('.modal-title').text('Edición de jornada');

			//Mostramos/Ocultamos mensajes de error o éxito si es que este formulario ya fue usado anteriormente
			$('.form-footer-botones').show();
			$('.form-footer-cerrar, .form-success').hide();
			alertErrorHide();
		});
	}

	/*
	PARTIDOS
	*/
	var formularioPartidoModal = $('#modalFormularioPartido');
	var jornadaActual = 0; //Índice de jornada vieja
	formularioPartidoModal.on('show.bs.modal', function (e) {
		$('.modal .form-error').hide();
		$('.modal .form-wait').hide();
		formSuccess.hide();
	});

	//Inicializar formulario de partido
	function partidoFormulario()
	{
		var formularioPartido = $('#partidoForm');
		function partidoCrear()
		{
			$('.btn-partido-crear').unbind('click').click(function(e){
				e.preventDefault();
				var t = $(this);
				var d = t.data();

				//Establecemos los valores al formulario
				formularioPartido[0].reset();
				$('#formPartidoJornada').val(d.jornada);
				$('#formPartidoId').val(0);
				$('#formFecha').val((new Date().toJSON().slice(0,10)));
				setTime($('#formHora').val(''));
				formularioPartidoModal.modal('show').find('.modal-title').text('Creación de nuevo partido');

				//Mostramos/Ocultamos mensajes de error o éxito si es que este formulario ya fue usado anteriormente
				$('.form-footer-botones, #formPartidoTiempos').show();
				$('.form-footer-cerrar, .form-success').hide();
				$('.s-equipo:first').change();
				alertErrorHide();
			});
		}

		//Edición de partido
		function partidoEditar()
		{
			$('.partido-editar').unbind('click').click(function(e){
				e.preventDefault();
				var t = $(this);
				var d = t.data('info');

				//Establecemos los valores al formulario
				formularioPartido[0].reset();
				$('#formPartidoJornada').val(d.ID_Jornada);
				$('#formPartidoId').val(d.ID_Partido);
				$('#equipo1').val(d.equipos[0].id);
				$('#equipo2').val(d.equipos[1].id);
				$('#tipoCancha').val(d.TipoCancha);
				$('#es_pendiente').val(d.Es_Pendiente);
				$('#formFecha').val(d.FechaHora.slice(0,10));
				setTime($('#formHora').val(d.hora + ':00'));

				for(var i = 0; i < 3; i++)
					$('#formArbitro' + (i + 1)).val(d.arbitros[i]);

				formularioPartidoModal.modal('show').find('.modal-title').text('Edición de partido');

				//Jornada actual
				jornadaActual = d.ID_Jornada;

				//Mostramos/Ocultamos mensajes de error o éxito si es que este formulario ya fue usado anteriormente
				$('.form-footer-botones, #formPartidoTiempos').show();
				$('.form-footer-cerrar, .form-success').hide();
				$('.s-equipo:first').change();
				alertErrorHide();
			});
		}

		//Validar equipos enfrentados
		function partidoEquiposComparar()
		{
			$('.s-equipo').unbind('change').change(function(){
				var t = $(this);
				var d = t.data();
				var otro = (d.equipo == 2) ? $('#equipo1') : $('#equipo2');
				if(t.val() == otro.val()) otro.find('option:selected').prop("selected", false).next().prop("selected", true);
			}).change();
		}

		//Validaciones de formulario de partido
		formularioPartido.validator().submit(function (e) {
			if(!e.isDefaultPrevented() && !_ajaxBussy_)
			{
				_ajaxBussy_ = true;
				alertErrorHide();
				botonesDeshabilitar();
				formWait.show();
				formSuccess.hide();

				//Enviar solicitud al servidor
				$.ajax({
					url: _sitePath_ + 'torneo/partidos/partido_submit' + _suffix_,
					data: formularioPartido.serialize(),
					success: function(data){
						if(data.error == 0)
						{
							formularioPartido.find('.form-footer-cerrar, .form-success').show();
							formularioPartido.find('.form-footer-botones').hide();

							//Recargar página
							var jContenedor = $('#jornada-' + $('#formPartidoJornada').val());
							jContenedor.load(window.location + " #" + jContenedor.attr('id') + ' > *', function(){
								accionesInicializar();
							});

							//Recargar jornada vieja si es necesario
							if(parseInt(jornadaActual) != parseInt($('#formPartidoJornada').val()))
							{
								var jContenedor = $('#jornada-' + jornadaActual);
								jContenedor.load(window.location + " #" + jContenedor.attr('id') + ' > *', function(){
									accionesInicializar();
								});
							}
						}
						else
						{
							//Tipos de error
							var msg = _errorMsg_;
							switch(data.error)
							{
								case 1: msg = 'Los equipos no pueden ser iguales.'; break;
								case 2: msg = 'No hay disponibilidad para el tipo de cancha en la fecha y hora seleccionados.'; break;
								case 3: msg = 'Uno de los equipos seleccionados ya tiene partido programado para esta jornada.'; break;
								case 9: msg = 'El puntaje de este partido ya se ha establecido. No puede ser editado.'; break;
							}
							alertErrorMsg( '<i class="fa fa-warning"></i> ' + msg );
						}
					},
					error: function(){ alertErrorMsg(_errorMsg_); },
					complete: function(){
						botonesHabilitar();
						formWait.hide();
						_ajaxBussy_ = false;
					}
				});

				return false;
			}
			e.preventDefault();
		});

		partidoCrear();
		partidoEditar();
		partidoEquiposComparar();
	}

	//Deshabilitar campos cuando es pendiente
	$('#es_pendiente').change(function(){
		var t = $(this);
		var isP = parseInt(t.val()) == 1;
		if(isP)
			$('#formPartidoTiempos').hide();
		else $('#formPartidoTiempos').show();

		$('#formFecha, #formHora').prop('disabled', isP);
	});

	//Eliminación de partido
	function partidoEliminar()
	{
		$('.partido-eliminar').unbind('click').click(function(e){
			e.preventDefault();
			if(confirm('Confirme la eliminación de este partido.'))
			{
				var t = $(this);
				t.parents('.panel-body:first').hide('clip', function(){
					t.parents('TABLE:first').hide();
					var msg = $("<div class='alert alert-warning marginTop10 marginBottom10 marginLeft10 marginRight10'><i class='fa fa-spin fa-spinner'></i> Actualizando información...</div>");
					$(this).append(msg).show('clip');

					//Enviar solicitud al servidor
					setTimeout(function(){
						$.ajax({
							url: _sitePath_ + 'torneo/partidos/eliminar' + _suffix_,
							data: { id: t.data('id') },
							success: function(data){
								if(data.error == 0)
								{
									//Recargar página
									var jContenedor = $('#jornada-' + t.data('jornada'));
									jContenedor.load(window.location + " #" + jContenedor.attr('id') + ' > *', function(){
										jContenedor.find('.panel-body').hide().show('slide');
										accionesInicializar();
									});
								}
								else
								{
									alert('Ha ocurrido un error. Intente de nuevo más tarde.');
									msg.remove();
									t.parents('TABLE:first').show();
								}
							},
							error: function(){
								alert('Ha ocurrido un error. Intente de nuevo más tarde.');
								msg.remove();
								t.parents('TABLE:first').show();
							}
						});
					}, 1000);
				});
			}
		});
	}

	//Ocultar alerta de error
	function alertErrorHide(){ errorAlert.hide(); }
	alertErrorHide();

	//Llenar alerta de error
	function alertErrorMsg(s){ errorAlert.html(s).show(); }

	//Deshabilitar botones
	function botonesDeshabilitar(){ formulario.find('.form-botones .btn').addClass('disabled').prop('disabled', true); }

	//Habilitar botones
	function botonesHabilitar(){ formulario.find('.form-botones .btn').removeClass('disabled').prop('disabled', false); }

	//Tabla de posiciones
	$('#tablaPosiciones').unbind('click').click(function(e){
		e.preventDefault();
		var tData = $(this).data();
		var modalTablaPosiciones = $('#modalTablaPosiciones');
		var modalTablaPosicionesContenido = $('#modalTablaPosicionesContenido').empty();
		var modalTablaPosicionesError = $('#modalTablaPosicionesError').hide();
		var modalTablaPosicionesCargando = $('#modalTablaPosicionesCargando').show();

		//Cargar información
		$.ajax({
			url: _sitePath_ + 'torneo/tabla_posiciones' + _suffix_,
			data: { categoria: tData.categoria, tipo: 1, vuelta: true },
			dataType: 'html',
			method: 'post',
			success: function(data){
				modalTablaPosicionesContenido.html(data);
			},
			error: function(e){
				modalTablaPosicionesError.show();
			},
			complete: function(){
				modalTablaPosicionesCargando.hide();
			}
		});

		//Modal
		c(tData);
		modalTablaPosiciones.modal('show');
	});
});
