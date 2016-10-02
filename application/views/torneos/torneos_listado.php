<h1>
	<span>Acceso a torneos</span>
	<a href="#" id="crearTorneo" class="btn btn-default btn-sm pull-right"><i class="fa fa-plus"></i> Crear torneo</a>
</h1>
<?php
if($registros_cantidad > 0)
{
?>
<table class="table table-border-top table-striped table-hover">
	<thead>
		<tr>
			<th colspan="3">Nombre del torneo</th>
			<th>Rol general</th>
			<th>Estatus</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach($registros as $r)
		{
		?>
		<tr>
			<td><a href="<?=base() . 'torneos/inicio/' . $r->ID_Torneo . suffix()?>"><?=$r->NomTorneo?></a></td>
			<td nowrap class="text-muted"><small><?=$r->tipo?></small></td>
			<td class="text-muted"><small><?=$r->YrTorn?></small></td>
			<td width="100" class="text-center"><a href="#" class="btn btn-xs w100 cambioRol" data-tabla="torneos" data-id="<?=$r->ID_Torneo?>" data-campo="TabTmpAct" data-valor="<?=$r->TabTmpAct?>"></a></td>
			<td width="100" class="text-center"><a href="#" class="btn btn-xs w100 cambioEstatus" data-tabla="torneos" data-id="<?=$r->ID_Torneo?>" data-campo="estatus" data-valor="<?=$r->estatus?>"></a></td>
			<td width="1" class="text-center"><a href="#" class="btn btn-warning btn-xs btn-eliminar" title="Eliminar registro" data-id="<?=$r->ID_Torneo?>" data-tabla="torneos"><i class="fa fa-remove"></i></a></td>
		</tr>
		<?php
		}
		?>
	</tbody>
</table>
<?php
}
else echo "<div class='alert alert-warning'><i class='fa fa-info-circle'></i> No hay registros actualmente</di>";

//Mostrar formulario de edición / creación
echo $formularioTorneo;
?>