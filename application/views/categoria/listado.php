<h1 class="page-header">Administraci&oacute;n de categor&iacute;as</h1>
<?php
if($registros_cantidad > 0)
{
?>
<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th colspan="2">&nbsp;&nbsp;</th>
			<th>Modificar</th>
  			
		</tr>
	</thead>
	<tbody>
		<?php
		foreach($registros as $r)
		{
		?>
		<tr>
			<td colspan="2"class="text-primary" ><?=$r->NomCat?></td>
			
			<td width="100" class="text-center"><a href="#" class="btn btn-circle btn-success editarCategoria" data-tabla="torneos" data-id="<?=$r->ID_Cat?>" data-campo="ID_Cat" data-valor="<?=$r->NomCat?>"> <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
            
            
			
		</tr>
        
		<?php
		}
		?>
	</tbody>
</table>
<?php
}
else echo "<div class='alert alert-warning'>No hay registros actualmenre</di>";

echo $formularioCategoria;
?>