var url = location.href;
var CKEditorFuncNum = url.match(/CKEditorFuncNum=([0-9]+)/) ? url.match(/CKEditorFuncNum=([0-9]+)/)[1] : null;

$().ready(function(){
	//Eliminador
	$('.btn-danger').click(function(){
		var t = $(this);
		var parent = t.parents('TD'); //Imagen
		if(parent.size() == 0) parent = t.parents('li'); //Documento
		if(confirm('Confirme la eliminación de este archivo'))
		{
			parent.css({ opacity: .3 });
			parent.find('.btn').addClass('disabled');
			$.ajax({
				url: _sitePath_ + 'publicidad/delete' + _suffix_,
				method: 'post',
				data: { archivo: parent.data('archivo') },
				success: function(data){
					if(data.error == 0)
					{
						parent.fadeOut();
					}
					else
					{
						alert('Ha ocurrido un error y el archivo no pudo ser eliminado. Intente de nuevo más tarde.');
					}
				},
				error: function(){
					alert('Ha ocurrido un error y el archivo no pudo ser eliminado. Intente de nuevo más tarde.');
				}
			});
		}
	});
	
	//Insertar
	$('.btn-success').click(function(){
		var t = $(this);
		// var tipo = 'imagenes';
		var parent = t.parents('TD');
		if(parent.size() == 0) parent = t.parents('li'); //Documento
			
		// {
			// tipo = 'documentos'
		// }
		
			window.opener.CKEDITOR.tools.callFunction(CKEditorFuncNum, parent.data('directorio') + parent.data('archivo'));
		// if(tipo == 'imagenes') //Es imagen
		// else //Es documento
		// {
			// var funcNum = ;
			// var fileUrl = parent.data('directorio') + parent.data('archivo');
			// window.opener.CKEDITOR.tools.callFunction( CKEditorFuncNum, parent.data('directorio') + parent.data('archivo') );
		// }

		window.close();
	});
});