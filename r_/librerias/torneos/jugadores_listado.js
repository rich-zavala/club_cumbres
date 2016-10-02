$().ready(function(){
	//Formulario de creación
	var formularioCrearModal = $('#modalFormularioCrear').on('shown.bs.modal', function () { $(this).find('TEXTAREA:first').focus().select(); });
	var formularioCrear = $('#equipoCrearForm');
	var errorAlert = $('.form-error');
	
	//Crear jugadores
	$('#crearJugares').click(function(e){
		e.preventDefault();
		var t = $(this);
		// var d = t.data();
		
		//Establecemos los valores al formulario
		$('#formJugadores').val('');
		formularioCrearModal.modal('show');
		
		//Mostramos/Ocultamos mensajes de error o éxito si es que este formulario ya fue usado anteriormente
		$('.form-footer-botones').show();
		$('.form-footer-cerrar, .form-success').hide();
		alertErrorHide();
	});
	
	//Validaciones de formulario
	formularioCrear.validator().on('submit', function (e) {
		if(!e.isDefaultPrevented() && !_ajaxBussy_)
		{
			_ajaxBussy_ = true;
			alertErrorHide();
			var t = $(this);
			botonesDeshabilitar(t);
			
			//Enviar solicitud al servidor
			$.ajax({
				url: _sitePath_ + 'torneo/equipos/submit_jugadores_crear' + _suffix_,
				data: t.serialize(),
				success: function(data){
					if(data.error == 0)
						tablaRecargar(t);
					else
						alertErrorMsg( data.msg );
					
					botonesHabilitar(t);
				},
				error: function(){
					alertErrorMsg(_errorMsg_);
					botonesHabilitar(t);
				}
			});			
		}
		return false;
	});
	
	//Eventos de eliminación: Hace que los botones hagan algo
	function btnEliminar()
	{
		$('.btn-eliminar').each(function(){
			eliminar($(this));
		});
	}
	
	//Editar un jugador
	var formularioJugadorModal = $('#modalFormularioJugador');
	var formularioJugador = $('#jugadorForm');
	function btnEditar()
	{
		$('.btn-editar').click(function(e){
			e.preventDefault();
			
			var t = $(this);
			var d = t.data();
			
			//Establecemos los valores al formulario
			formularioJugadorModal.modal('show');
			
			//Mostramos/Ocultamos mensajes de error o éxito si es que este formulario ya fue usado anteriormente
			$('.form-footer-cerrar, .form-loading').show();
			$('.form-footer-botones, .form-success').hide();
			alertErrorHide();
			
			//Cargar formulario
			var url = _sitePath_ + 'torneo/jugadores/edicion' + '/' + d.id + _suffix_;
			$('#modalFormularioAjax').load(url, function(){
				$('.form-footer-cerrar, .form-loading').hide();
				$('.form-footer-botones').show();
				setDatePicker();
			});
		});
	}
	
	//Validaciones de formulario
	formularioJugador.validator().on('submit', function (e) {
		if(!e.isDefaultPrevented() && !_ajaxBussy_)
		{
			_ajaxBussy_ = true;
			alertErrorHide();
			var t = formularioJugador;
			botonesDeshabilitar(t);
			
			//Enviar solicitud al servidor
			$.ajax({
				url: _sitePath_ + 'torneo/jugadores/actualizar' + _suffix_,
				data: t.serialize(),
				success: function(data){
					if(data.error == 0)
						tablaRecargar(t);
					else
						alertErrorMsg( data.msg );
				},
				error: function(){
					alertErrorMsg(_errorMsg_);
				},
				complete: function(){
					botonesHabilitar(t);
					_ajaxBussy_ = false;
				}
			});
		}
		return false;
	});
	
	//Recargar la tabla después de ajax
	function tablaRecargar(t)
	{
		$('#tablaListadoContenedor').load(window.location + " #tablaListado", function(){
			
			//Los botones de la tabla recién cargada no tienen eventos de "click" aplicados, por lo que ejecutamos las siguientes funciones:
			btnEliminar();
			btnEditar();
			btnFicha();
			
			//Ocultamos los botones de registro y mostramos el mensaje de éxito
			t.find('.form-footer-botones').hide();
			t.find('.form-footer-cerrar, .form-success').show();
		});
	}
	
	//Ocultar alerta de error
	function alertErrorHide(){ errorAlert.hide(); }

	//Llenar alerta de error
	function alertErrorMsg(s){ errorAlert.html('<i class="fa fa-warning"></i>' + s).show(); }

	//Deshabilitar botones
	function botonesDeshabilitar(formulario){ formulario.find('BUTTON').prop('disabled', true); }

	//Habilitar botones
	function botonesHabilitar(formulario){ formulario.find('BUTTON').prop('disabled', false); }
	
	//Establecer acciones a los botones
	btnEliminar();
	btnEditar();
	btnFicha();
	
	//Abrir ventana de ficha
	function btnFicha()
	{
		$('.btn-ficha').unbind('click').click(function(e){
			e.preventDefault();
			abreVentana($(this).attr('href'), '650', '480', '0', '0', 'c:sb');
		});
	}
	
	function abreVentana(aPag, aAncho, aAlto, aArriba, aIzquierda, aAtributos){
		var mAtrib= aAtributos.split(":");
		var mAtribL= mAtrib.length;
		var cadAtrib="";
		var cBand=false;
		var fBand=false;
		for (var i=0; i<mAtribL; i++){
			switch (mAtrib[i]){
				case "t": cadAtrib += "toolbars=yes,"; break;
				case "l": cadAtrib += "location=yes,"; break;
				case "m": cadAtrib += "menubar=yes,"; break;
				case "st": cadAtrib += "status=yes,"; break;
				case "sb": cadAtrib += "scrollbars=yes,"; break;
				case "r": cadAtrib += "resizable=yes,"; break;
				case "f": cadAtrib += "fullscreen=yes,"; fBand=true; break;
				case "c": 
					var an = (screen.width - aAncho)/2;
					var alt = (screen.height - aAlto)/2;
					cadAtrib += "top="+alt+",left="+an+",";
					cBand=true;
					break;
			}
		}
		if(fBand){
			cadAtrib += "height="+screen.height+", width="+screen.width;
		} else {
			cadAtrib += "height="+aAlto+", width="+aAncho;
		}
		if (!cBand){
			cadAtrib += ", top="+aArriba+",left="+aIzquierda;
		}
		gV1 = window.open(aPag, "Identificador", cadAtrib);
	}
});