$().ready(function(){
	var formulario = $('#grupoForm');
	var errorAlert = formulario.find('.form-error');
	var formWait = formulario.find('.form-wait').hide();
	var formSuccess = formulario.find('.form-success').hide();
	
	//Estilos para botones con checkboxes
	$('LABEL.btn-checkbox').click(function(){
		btnSwitch(this);
	}).each(function(){ btnSwitch(this) });
	
	formulario.validator().submit(function (e) {
		if(!e.isDefaultPrevented() && !_ajaxBussy_)
		{
			_ajaxBussy_ = true;
			alertErrorHide();
			botonesDeshabilitar();
			formWait.show();
			formSuccess.hide();
			
			//Enviar solicitud al servidor
			$.ajax({
				url: _sitePath_ + 'torneo/partidos/grupo_submit' + _suffix_,
				data: formulario.serialize(),
				success: function(data){
					if(data.error == 0)
					{
						//Si es creación refrescar la página
						if(parseInt($('#grupoFormId').val()) == 0)
							window.location = _sitePath_ + 'torneo/partidos/grupo_formulario/' + data.redirect + _suffix_;
						else
						{
							formSuccess.show();
							setTimeout(function(){ formSuccess.hide('slide'); },3000);
						}
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
	});

	//Ocultar alerta de error
	function alertErrorHide(){ errorAlert.hide(); }
	alertErrorHide();

	//Llenar alerta de error
	function alertErrorMsg(s){ errorAlert.text(s).show(); }

	//Deshabilitar botones
	function botonesDeshabilitar(){ formulario.find('.form-botones .btn').addClass('disabled').prop('disabled', true); }

	//Habilitar botones
	function botonesHabilitar(){ formulario.find('.form-botones .btn').removeClass('disabled').prop('disabled', false); }
});

function btnSwitch(o)
{
	var o = $(o).addClass('w100');
	var cb = o.find('[type="checkbox"]').hide();
	if(cb.is(':checked'))
		o.removeClass('btn-default').addClass('btn-success').addClass('active');
	else
		o.removeClass('btn-success').removeClass('active').addClass('btn-default');
}