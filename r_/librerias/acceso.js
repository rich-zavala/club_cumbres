/*
Inicio de sesiones
*/

function doError(s)
{
	$('#login_working').addClass('hidden');
	$('#login_error').html(s).removeClass('hidden');
}

//Inicializar
$().ready(function(){
	$('.loginform').submit(function(event){
		event.preventDefault();
		var t = $(this);
		if($('#InputUser').val().length > 0 && $('#InputPassword').val().length > 0)
		{
			var verificar = function(){
				$.ajax({
					url: 'acceso/login.html',
					data: t.serialize(),
					dataType: 'json',
					method: 'post',
					success: function(data){
						if(data.error == 0)
						{
							$('#login_working').html('Acceso confirmado.');
							$('.container').fadeOut(function(){
								window.location.reload();
							});
						}
						else
						{
							doError('Tu nombre de usuario o contraseña son incorrectos.');
							t.find('.login').removeClass('disabled');
						}
					},
					error: function(){
						doError('Ha ocurrido un error. Intenta de nuevo más tarde.');
						t.find('.login').removeClass('disabled');
					}
				});
			};
			
			t.find('.login').addClass('disabled');
			$('#login_error').addClass('hidden');
			$('#login_working').removeClass('hidden');
			
			var ln = $('#login_notificaciones');
			if(!ln.is(':visible'))
				ln.removeClass('hidden').hide().show('clip', verificar());
			else
				verificar();
		}
	});
});