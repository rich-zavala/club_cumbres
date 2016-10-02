<h1 class="page-header">Administraci&oacute;n de publicidad</h1>
<a href="#" id="crearAnuncio" class="btn btn-default btn-sm pull-right"><i class="fa fa-plus"></i> Crear anuncio</a>
<br /><br /><br /><br /><br />
<?php
$tipo = array(0=>"Central",1=>"Banner Primero",2=>"Banner Segundo",3=>"Mini 1",4=>"Mini 2",5=>"Boton Fotos",999=>"Popup clubcumbres.com");

if(isset($error_data)){
	
?>
   
 <div class="alert alert-warning" role="alert"><?=$error_data ?></div>   
 
 
<?php
	
	}


if($registros_cantidad > 0)
{
	
	foreach($registros as $r)
		{
?>
<div class="panel panel-primary">
	<div class="panel-heading">
        <h3 class="panel-title"><a href="#" class="btn btn-warning marginRight10 btn-eliminar" data-tabla="anuncios" data-id="<?= $r->ID_Anuncio ?>" data-campo="ID_Anuncio" data-valor="<?=$r->NomArchivo?>"> <i class="fa fa-remove"></i></a> <?=$tipo[$r->TipoAnuncio]?> </h3>
      </div>
      <div class="panel-body text-center">
        <embed src="<?= base_url()."uploads/".$r->NomArchivo ?>" />
      </div>

</div>


<?php

		}//Fin de foreach
		
	}//Fin de if
	else echo "<div class='alert alert-warning'>No hay registros actualmente!</di>";
	
	echo $formulariopublicidad;
?>