<div class="container marginBottom20">
	<h1>Publicaci&oacute;n de aviso.</h1>
	<hr>
	<div class="row">
		<div class="col-sm-12 text-center">
        
        
	<?php
	
	if(isset($save) && $save == 1){
		
	?>
    
    <h2>Se guardo satisfactoriamente!</h2>
    
    <?php	
		
		
		}
	
	
	$atributos = array(
		'name' => 'contenido',
		'id' => 'contenido',
		'value' => 'Este es un texto de ejemplo.' //<<< Aquí podrías poner el valor del registro si es edición
	);

if($torneo_activo == 1){

	echo	form_open('aviso/registrar')
			.	form_textarea($atributos)
			.	display_ckeditor($ckeditor)
			.	form_button($form_opciones['boton'])
			.	form_close();
}else{
?>
<div class="alert alert-warning">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<table>
			<tr>
				<td class="top paddingRight10" width="1"><i class="fa fa-info-circle"></i></td>
				<td class="ln1">Verificar que se encuentre activo un solo torneo!
				</td>
			</tr>
		</table>
	</div>
<?php	
	
	}
	?>
		</div>
	</div>
</div>