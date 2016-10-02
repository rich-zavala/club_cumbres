<h1 class="page-header">Administraci&oacute;n de encuesta</h1>

<?php

if($torneo_activo == 1){
	
	

?>
<a href="#" id="crearEncuesta" class="btn btn-default btn-sm pull-right"><i class="fa fa-plus"></i> Crear encuesta</a>
<br /><br /><br /><br /><br />


<?php	
	
	
	
	



if($registros_cantidad > 0  and $reg_cant > 0)
{
	
	foreach($registros as $r)
		{
?>
<div class="panel panel-primary">
	<div class="panel-heading">
        <h3 class="panel-title"><a href="#" class="btn btn-warning marginRight10 btn-eliminar" data-tabla="encuestas" data-id="<?= $r->ID_Encuesta ?>" data-campo="ID_Encuesta" > <i class="fa fa-remove"></i></a> <?=$r->PregEnc?> </h3>
      </div>
     


<?php

		}//Fin de foreach
?>
<div class="panel-body text-center">
<table class="table table-striped">
     <thead>
        <tr>
          <th>Respuesta</th>
          <th>Contador</th>
          <th>Eliminar</th>
         </tr>
      </thead>
      <tbody>
      	
<?php		
		foreach($reg as $re){
			
?>
 
       <tr><td><?=$re->DenRes?></td><td><?=$re->VotosRes?></td><td width="100" class="text-center"><a href="#" class="btn btn-circle btn-warning marginRight10 btn-eliminar" data-tabla="enc_res" data-id="<?=$re->ID_Respuesta?>" data-campo="ID_Respuesta"> <i class="fa fa-remove"></i></a></td></tr>
      
     

<?php			
			
			}//Fin de foreach

?>

</tbody>
</table>
 </div>

</div>
<?php
		
	}else echo "<div class='alert alert-warning'>No hay registros actualmenre</di>";
		//Fin de if
	
}
	else {
		
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
	
	echo $formularioencuesta;
?>