<h1 class="page-header">Administraci&oacute;n de avisos</h1>

<br /><br />
<?php
if($registros_cantidad > 0)
{
?>
<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th>Nombre</th>
            <th>Aviso</th>
            <th>Eliminar</th>
  			
		</tr>
	</thead>
	<tbody>
		<?php
		foreach($registros as $r)
		{
		?>
		<tr>
			<td class="text-primary" ><?=$r->NomTorneo?></td>
            <td class="text-primary" ><?=$r->TextoAviso?></td>
           
            		
			           
            
            <td width="100" class="text-center"><a href="#" class="btn btn-circle btn-warning marginRight10 btn-eliminar" data-tabla="avisos" data-id="<?=$r->ID_Aviso?>" data-campo="ID_Aviso" > <i class="fa fa-remove"></i></a></td>
            
            
			
		</tr>
        
		<?php
		}
		?>
	</tbody>
</table>
<?php
}
else echo "<div class='alert alert-warning'>No hay registros actualmenre</di>";


?>