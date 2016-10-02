$().ready(function(){
	var formulario = $('#puntajeForm');
	var errorAlert = formulario.find('.form-error');
	var formWait = formulario.find('.form-wait').hide();
	var formSuccess = formulario.find('.form-success').hide();
	
	var suma1 = $('#sumatoria1');
	var suma2 = $('#sumatoria2');
	
	//Hacer que los campos sumen
	$('.golJugador').each(function(){
		var t = $(this);
		t.blur(function(){
			var equipo = t.data('equipo');
			var gol = parseInt(t.val());
			
			if(parseInt(t.val()) < 0 || isNaN(parseInt(t.val())))
				t.val(0);
			
			sumatoriaEquipo(equipo);
		});
	}).blur();
	
	//Función de sumatoria
	function sumatoriaEquipo(equipo)
	{
		var suma = 0;
		$(".gol" + equipo).each(function(){
			var t = $(this);
			var gol = parseInt(t.val());
			suma += gol;
		});
		$('[name="sumatoria[' + equipo + ']"]').val(suma);
	}
	
	//Shoot outs
	var checkShoot = $('[name="shootouts"]');
	shootShow(0);
	checkShoot.change(function(){
		shootShow(1);
	});
	
	function shootShow(n)
	{
		var effect = (n == 0) ? 0 : 'clip'; //Sin efecto al cargar página
		
		if(checkShoot.is(':checked'))
			$('.shootEquipo').show(effect);
		else
			$('.shootEquipo').hide(effect);
	}
	
	formulario.validator().submit(function (e) {
		if(!e.isDefaultPrevented() && !_ajaxBussy_)
		{
			alertErrorHide();
			botonesDeshabilitar();
			formWait.show();
			formSuccess.hide();
			
			//Validar empate
			if(parseInt(suma1.val()) == parseInt(suma2.val()) && (!checkShoot.is(':checked') || $('.shootEquipo:checked').size() == 0 || $('.shootEquipo:checked').size() > 1))
			{
				botonesHabilitar();
				formWait.hide();
				alertErrorMsg("Ante un empate es necesario indicar el quipo que ganó por shoot outs.");
			}
			else
			{
				//Deshabilitar empate si no lo es
				if(parseInt(suma1.val()) != parseInt(suma2.val()))
				{
					checkShoot.prop('checked', false);
					$('.shootEquipo:checked').prop('checked', false);
					shootShow(1);
				}
				
				//Enviar solicitud al servidor
				_ajaxBussy_ = true;
				$.ajax({
					url: _sitePath_ + 'torneo/partidos/puntaje_submit' + _suffix_,
					data: formulario.serialize(),
					success: function(data){
						if(data.error == 0)
						{
							formSuccess.show();
							setTimeout(function(){ formSuccess.hide('slide'); },3000);
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
		}
	});

	//Ocultar alerta de error
	function alertErrorHide(){ errorAlert.hide(); }
	alertErrorHide();

	//Llenar alerta de error
	function alertErrorMsg(s){ errorAlert.html('<i class="fa fa-fw fa-warning"></i> ' + s).show(); }

	//Deshabilitar botones
	function botonesDeshabilitar(){ formulario.find('.form-botones .btn').addClass('disabled').prop('disabled', true); }

	//Habilitar botones
	function botonesHabilitar(){ formulario.find('.form-botones .btn').removeClass('disabled').prop('disabled', false); }
});