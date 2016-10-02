<h1 class="page-header">Administraci&oacute;n de usuarios</h1>
<a href="#" id="crearUsuario" class="btn btn-default btn-sm pull-right"><i class="fa fa-plus"></i> Crear usuario</a>
<br /><br />
<?php
if($registros_cantidad > 0)
{
?>
<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th>Nombre</th>
            <th>Usuario</th>
            <th>Editar</th>
            <th>Eliminar</th>
  			
		</tr>
	</thead>
	<tbody>
		<?php
		foreach($registros as $r)
		{
		?>
		<tr>
			<td class="text-primary" ><?=$r->NomUsr?></td>
            <td class="text-primary" ><?=$r->Usuario?></td>
           
            		
			<td width="100" class="text-center"><a href="#" class="btn btn-circle btn-success btn-editar marginRight10 editarUsuario" data-tabla="usuarios_admin" data-id="<?=$r->ID_Usuario?>" data-campo="ID_Usuario" data-valor="<?=$r->NomUsr?>" data-user="<?=$r->Usuario?>"> <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
             
            
            <td width="100" class="text-center"><a href="#" class="btn btn-circle btn-warning marginRight10 btn-eliminar" data-tabla="usuarios_admin" data-id="<?=$r->ID_Usuario?>" data-campo="ID_Usuario" data-valor="<?=$r->NomUsr?>"> <i class="fa fa-remove"></i></a></td>
            
            
			
		</tr>
        
		<?php
		}
		?>
	</tbody>
</table>
<?php
}
else echo "<div class='alert alert-warning'>No hay registros actualmenre</di>";

echo $formularioUsuario;
?>