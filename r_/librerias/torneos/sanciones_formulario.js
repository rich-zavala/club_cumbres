$().ready(function(){
	var formulario = $('#sancionForm');
	var errorAlert = formulario.find('.form-error');
	var formWait = formulario.find('.form-wait').hide();
	var formSuccess = formulario.find('.form-success').hide();
	
	//Inicializar acciones
	function accionesInicializar()
	{
		btnEliminar();
	}
	accionesInicializar();
	
	formulario.validator().submit(function (e) {
		if(!e.isDefaultPrevented() && !_ajaxBussy_)
		{
			alertErrorHide();
			botonesDeshabilitar();
			formWait.show();
			formSuccess.hide();
			
			//Enviar solicitud al servidor
			_ajaxBussy_ = true;
			$.ajax({
				url: _sitePath_ + 'torneo/sanciones/submit' + _suffix_,
				data: formulario.serialize(),
				success: function(data){
					if(data.error == 0)
					{
						formSuccess.show();
						setTimeout(function(){ formSuccess.hide('slide'); },3000);
						formulario[0].reset();
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
		}
		
		return false;
	});

	//Ocultar alerta de error
	function alertErrorHide(){ errorAlert.hide(); }
	alertErrorHide();

	//Llenar alerta de error
	function alertErrorMsg(s){ errorAlert.html('<i class="fa fa-warning"></i> ' + s).show(); }

	//Deshabilitar botones
	function botonesDeshabilitar(){ formulario.find('.form-botones .btn').addClass('disabled').prop('disabled', true); }

	//Habilitar botones
	function botonesHabilitar(){ formulario.find('.form-botones .btn').removeClass('disabled').prop('disabled', false); }
	
	//Recargador de formulario de partido
	function recargarFormularioPartido()
	{		
		$('#sancionesContenedor').load(window.location + " #sancionesContenido", function(){
			accionesInicializar();
		});
	}
	
	//Remover una sanción
	function btnEliminar()
	{
		$('.btn-eliminar-sancion').each(function(){
			eliminarSancion($(this));
		});
	}
	
	function eliminarSancion(o, callback)
	{
		$(o).unbind('click').click(function(e){
			e.preventDefault();
			if(!_ajaxBussy_ && confirm('Confirme la eliminación de este registro'))
			{
				_ajaxBussy_ = true;
				
				var t = $(this);
				var data = t.data();
				var clase = t.attr('class');
				if(typeof data.location != 'undefined') newLocation = data.location;
				obj = spinner(t);
				
				$.ajax({
					url: _sitePath_ + 'torneo/sanciones/sancion_remover' + _suffix_,
					data: { id: data.id },
					method: 'post',
					dataType: 'json',
					success: function(data){
						if(data.error == 0)
						{
							obj.parents('TR:first').fadeOut(); //Identifica el TR padre del botón y lo desaparece
						}
						else
						{
							alertError();
							broken(obj);//.replaceWith('<i class="fa fa-unlink"></i>');
						}
					},
					error: function(){
						alertError();
						broken(obj);//.replaceWith('<i class="fa fa-unlink"></i>');
					},
					complete: function(){ _ajaxBussy_ = false; }
				});
			}
		});
	}
});