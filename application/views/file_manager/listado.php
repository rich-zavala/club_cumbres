<div class="container marginBottom20">
	<h3>Administrador de archivos</h3>
	<hr>
	<?php
	//Manejador de errores
	$upload_error = $this->session->flashdata('upload_error');
	if($upload_error) echo "<div class='alert alert-danger marginBottom20'><i class='fa fa-warning marginRight10'></i> <b>Error al subir archivo:</b> {$upload_error['error']}</div>";
	
	//Nuevo archivo disponible
	if($this->session->flashdata('upload_success')) echo "<div class='alert alert-success marginBottom20'><i class='fa fa-check marginRight10'></i> El archivo nuevo está disponible.</div>";
	
	//Sin archivos
	if(count($archivos) == 0) echo "<div class='alert alert-warning marginBottom20'><i class='fa fa-info-circle marginRight10'></i> No hay archivos disponibles.</div>";
	
	//Para imágenes
	if($tipo == 'imagenes')
	{
	?>
	<table class="table table-bordered imagen-contenedor">
		<?php
		$i = 0;
		foreach($archivos as $archivo)
		{
			$extensionesDocumentos = array('doc', 'docx', 'pdf');
			$imagen = (in_array(archivo_extension($archivo), $extensionesDocumentos)) ? base() . 'r_/images/file_icon.png' : $directorio . $archivo;			
			if($i == 0) echo '<tr>';
		?>	
		<td class="text-center" data-directorio="<?=$directorio?>" data-archivo="<?=$archivo?>">
			<div class="btn-group" role="group">
				<button type="button" class="btn btn-xs btn-danger" title="Eliminar"><i class="fa fa-trash-o"></i></button>
				<button type="button" class="btn btn-xs btn-success" title="Insertar"><i class="fa fa-plus"></i></button>
			</div>
		
			<img src="<?=$imagen?>" class="imagen" />
			<div class="font11 thumb"><?=$archivo?></div>
		</td>
		
		<?php
			if($i == 5) echo '</tr>';
			
			$i++;
			if($i == 6) $i = 0;
		}
		?>
	</table>
	<?php
	}
	else //Para documentos
	{
	?>
	<ul class="list-group">
		<?php
		foreach($archivos as $archivo)
		{
		?>
		<li class="list-group-item" data-directorio="<?=$directorio?>" data-archivo="<?=$archivo?>">
			<?=$archivo?>
			<div class="btn-group" role="group">
				<a href="<?=$directorio . $archivo?>" target="_new" class="btn btn-xs btn-primary" title="Descargar"><i class="fa fa-download"></i></a>
				<button type="button" class="btn btn-xs btn-danger" title="Eliminar"><i class="fa fa-trash-o"></i></button>
				<button type="button" class="btn btn-xs btn-success" title="Insertar"><i class="fa fa-plus"></i></button>
			</div>
		</li>
		<?php
		}
		?>
	</ul>
	<?php
	}
	?>

	<hr>
	<h4>Subir una nuevo archivo</h4>
	<?php
	echo	form_open_multipart('aviso/upload')
			.	form_upload($form_opciones['file'])
			.	form_input($form_opciones['tipo'])
			.	form_button($form_opciones['boton'])
			.	form_close();
	?>
</div>