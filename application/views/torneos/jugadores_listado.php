<ol class="breadcrumb">
  <li><a href="<?=base()?>torneos/listado<?=suffix()?>">Torneos</a></li>
  <li><a href="<?=base()?>torneo/equipos/listado/<?=$torneo?>/<?=$categoria->ID_CatTorn?><?=suffix()?>"><?=$categoria->NomCat?></a></li>
	<li class="active">Equipos</li>
</ol>

<a href="#" id="crearJugares" class="btn btn-default btn-md pull-right" data-categoria="<?=$categoria->ID_CatTorn?>"><i class="fa fa-plus"></i> Agregar jugadores</a>
<h2><?=$equipo->NomEquipo?></h2>
<div id="tablaListadoContenedor">
	<div id="tablaListado">
		<?php
		if(count($equipo->jugadores) > 0)
		{
		?>
		<table class="table table-border-top table-striped table-hover table-condensed table-middle">
			<thead>
				<tr>
					<th>Número de afiliación</th>
					<th>Nombre</th>
					<th>Edad</th>
					<th>Teléfono</th>
					<th>Email</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$idsManipulados = @explode(',', $this->session->flashdata('id_manipulado'));
				foreach($equipo->jugadores as $r)
				{
					$trColor = @in_array($r->ID_Jugador, $idsManipulados) ? 'success' : '';
				?>
				<tr class="<?=$trColor?>">
					<td><?=$r->NumAfiJug > 0 ? $r->NumAfiJug : '~'?></td>
					<td><?=$r->NomJug?> <?=$r->ApeJug?></td>
					<td><?=$r->edad?></td>
					<td><?=$r->TelJug?><br><?=$r->TelCasaJug?></td>
					<td><?=$r->EmailJug?></td>
					<td class="text-center" width="1" nowrap>
						<div class="btn-group-circle" role="group">
							<a href="<?=base()?>torneo/ficha_jugador/generar/<?=$equipo->ID_Equipo?>/<?=$r->ID_Jugador.suffix()?>" data-id="<?=$r->ID_Jugador?>" class="btn btn-circle btn-primary btn-ficha marginRight10" title="Ver ficha" target="_new"><i class="fa fa-user"></i></a>
							<a data-id="<?=$r->ID_Jugador?>" class="btn btn-circle btn-success btn-editar marginRight10" title="Editar jugador"><i class="fa fa-pencil"></i></a>
							<a href="<?=base()?>torneo/sanciones/registro/<?=$torneo?>/<?=$r->ID_Jugador.suffix()?>" class="btn btn-circle btn-default marginRight10" title="Establecer sanciones"><i class="fa fa-bullhorn"></i></a>
							<a href="#" data-id="<?=$r->ID_Jugador?>" data-tabla="equipos_jugadores" class="btn btn-circle btn-warning btn-eliminar" title="Eliminar equipo"><i class="fa fa-remove"></i></a>
						</div>
					</td>
				</tr>
				<?php
				}
				?>
			</tbody>
		</table>
		<?php
		}
		else echo "<div class='alert alert-warning'><i class='fa fa-info-circle'></i> No hay registros actualmente</div>";
		?>
	</div>
</div>

<div class="modal fade" id="modalFormularioCrear">
  <div class="modal-dialog">
		<form action="<?=base()?>torneo/equipos/submit<?=suffix()?>" method="post" id="equipoCrearForm" class="modal-content">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Agregar jugadores a equipo</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
					<label for="formJugadores">Escriba una línea por cada jugador a agregar</label>
						<textarea class="form-control" id="formJugadores" name="jugadores" data-minlength="4" data-error="Especifique el nombre de los jugadores de este equipo" rows="10" required></textarea>
						<div class="help-block with-errors"></div>
					</div>
					<div class="alert alert-success form-success"><i class="fa fa-check"></i> El registro se realizó exitosamente.</div>
					<div class="alert alert-danger form-error"></div>
					<input type="hidden" name="id" value="<?=(int)$equipo->ID_Equipo?>" />
				</div>
				<div class="modal-footer form-footer-cerrar">
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
				</div>
				<div class="modal-footer form-footer-botones">
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
					<button type="submit" class="btn btn-primary" id="formCrearSubmit"><i class="fa fa-save"></i> Registrar jugadores</button>
				</div>
			</div>
    </form>
	</div>
</div>

<div class="modal fade" id="modalFormularioJugador">
  <div class="modal-dialog">
		<form action="<?=base()?>torneo/jugadores/actualizar<?=suffix()?>" method="post" id="jugadorForm" class="modal-content">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Edición de jugador</h4>
				</div>
				<div class="modal-body">
					<div id="modalFormularioAjax"></div>
					<div class="alert alert-warning form-loading margin0">Cargando información...</div>
					<div class="alert alert-success form-success"><i class="fa fa-check"></i> El registro se realizó exitosamente.</div>
					<div class="alert alert-danger form-error"></div>
				</div>
				<div class="modal-footer form-footer-cerrar">
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
				</div>
				<div class="modal-footer form-footer-botones">
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
					<button type="submit" class="btn btn-primary" id="formJugadorSubmit"><i class="fa fa-save"></i> Registrar cambios</button>
				</div>
			</div>
    </form>
	</div>
</div>