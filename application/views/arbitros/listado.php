<h1>
	<span><i class="fa fa-fw fa-male"></i> Gestión de árbitros</span>
	<a href="#" id="crearArbitro" class="btn btn-default btn-sm pull-right"><i class="fa fa-plus"></i> Registrar árbitro</a>
</h1>
<?php
if($registros_cantidad > 0)
{
?>
<table class="table table-border-top table-striped table-hover">
	<thead>
		<tr>
			<th>Nombre del árbitro</th>
			<th>Teléfono</th>
			<th>Fecha de registro</th>
			<th>Estatus</th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach($registros as $r)
		{
		?>
		<tr>
			<td><?=$r->nombre?></a></td>
			<td><?=$r->telefono?></td>
			<td><?=$r->fecha?></td>
			<td width="100" class="text-center"><a href="#" class="btn btn-xs w100 cambioEstatus" data-tabla="arbitros" data-id="<?=$r->id?>" data-campo="activo" data-valor="<?=$r->activo?>"></a></td>
			<td width="1" class="text-center"><a href="#" class="btn btn-warning btn-xs btn-eliminar" title="Eliminar registro" data-id="<?=$r->id?>" data-tabla="arbitros"><i class="fa fa-remove"></i></a></td>
			<td width="1" class="text-center"><a href="#" class="btn btn-primary btn-xs btn-editar" title="Editar registro" data-info="<?=htmlspecialchars(json_encode($r))?>"><i class="fa fa-pencil"></i></a></td>
		</tr>
		<?php
		}
		?>
	</tbody>
</table>
<?php
}
else echo "<div class='alert alert-warning'><i class='fa fa-info-circle'></i> No hay registros actualmente</div>";

//Mostrar formulario de edición / creación
echo $formularioArbitro;
?>