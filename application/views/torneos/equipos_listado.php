<ol class="breadcrumb">
  <li><a href="<?=base()?>torneos/listado<?=suffix()?>">Torneos</a></li>
  <li class="active"><?=$categoria->NomCat?></li>
</ol>

<div class="btn-group pull-right">
	<a href="#" id="crearEquipo" class="btn btn-default btn-md" data-categoria="<?=$categoria->ID_CatTorn?>"><i class="fa fa-plus"></i> Crear equipo</a>
	<a href="<?=base()?>torneo/equipos/importar/<?=$torneo?>/<?=$categoria->ID_CatTorn.suffix()?>" class="btn btn-primary btn-md" data-categoria="<?=$categoria->ID_CatTorn?>"><i class="fa fa-download"></i> Importar equipo</a>
</div>

<h2>Equipos</h2>

<div id="tablaListadoContenedor">
	<div id="tablaListado">
		<?php
		if(count($equipos) > 0)
		{
		?>
		<table class="table table-border-top table-striped table-hover table-condensed table-middle">
			<thead>
				<tr>
					<th>Nombre del equipo</th>
					<th>Jugadores</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach($equipos as $r)
				{
					//Colorear una fila si ha sido manipulada
					/*
					FlashData es una variable de sesión que sólo se utiliza una vez.
					En "moequipo /crear / actualizar" registro el ID de un equipo recién manipulado.
					Así pues, cuando se cree/edite un registro y jQuery.load() solicite esta página se pintará el renglón de verde
					*/
					$trColor = (@$this->session->flashdata('equipo_manipulado') == $r->ID_Equipo) ? 'success' : '';
				?>
				<tr class="<?=$trColor?>">
					<td><?=$r->NomEquipo?></td>
					<td class="text-center" width="1"><a href="<?=base() . 'torneo/equipos/jugadores/' . $torneo . '/' . $categoria->ID_CatTorn . '/' . $r->ID_Equipo . suffix()?>" data-id="<?=$r->ID_Equipo?>" class="btn btn-sm btn-primary" title="Gestiona los jugadores de este equipo"><i class="fa fa-male"></i> (<?=$r->jugadores?>)</a></td>
					<td class="text-center" width="1" nowrap>
						<div class="btn-group-circle" role="group">
							<a href="#" class="btn btn-circle btn-success btn-editar marginRight10" title="Editar equipo" data-id="<?=$r->ID_Equipo?>" data-nombre="<?=htmlspecialchars($r->NomEquipo)?>" data-categoria="<?=$categoria->ID_CatTorn?>"><i class="fa fa-pencil"></i></a>
							<a href="#" class="btn btn-circle btn-info btn-email-masivo marginRight10" title="Enviar email"  data-destino="equipo" data-destino-valor="<?=$r->ID_Equipo?>"><i class="fa fa-envelope"></i></a>
							<!--<a href="#" class="btn btn-circle btn-default marginRight10" title="Transferencias"><i class="fa fa-exchange"></i></a>-->
							<a href="#" class="btn btn-circle btn-warning btn-eliminar" title="Eliminar equipo" data-id="<?=$r->ID_Equipo?>" data-tabla="equipos_catalog"><i class="fa fa-remove"></i></a>
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

<div class="modal fade" id="modalFormularioEquipo">
  <div class="modal-dialog">
		<form action="<?=base()?>torneo/equipos/submit<?=suffix()?>" method="post" id="equipoForm" class="modal-content">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="formNombre">Nombre</label>
						<input type="text" class="form-control" id="formEquipoNombre" name="nombre" data-minlength="3" data-error="Especifique el nombre del equipo" maxlength="30" required>
						<div class="help-block with-errors"></div>
					</div>
					<div class="form-group">
						<label for="formEquipocategoria">Categoría</label>
						<select class="form-control" id="formEquipoCategoria" name="categoria" required>
							<?php foreach($info->categorias as $k => $cat){ ?>
							<option value="<?=$k?>"><?=$cat->NomCat?></option>
							<?php } ?>
						</select>
					</div>
					<div id="formSuccess" class="alert alert-success"><i class="fa fa-check"></i> El registro se realizó exitosamente.</div>
					<div id="formError" class="alert alert-danger"></div>
					<input type="hidden" id="formEquipoId" name="id" value="<?=@(int)$ID_Torneo?>" />
				</div>
				<div class="modal-footer" id="formFooterCerrar">
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
				</div>
				<div class="modal-footer" id="formFooterBotones">
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
					<button type="submit" class="btn btn-primary" id="formNuevoEquipoSubmit"><i class="fa fa-save"></i> Registrar torneo</button>
				</div>
			</div>
    </form>
	</div>
</div>