<div class="row">
	<div class="col-md-3 padding0 menuSecundario">
		<div class="panel panel-default marginTop30">
			<div class="panel-heading"><i class="fa fa-certificate"></i> Categorías</div>
			<ul class="list-group">
				<?php
				foreach($info->categorias as $cat)
				{
					//Contabilizar equipos
					$equiposCantidad = @count($info->equipos[$cat->ID_CatTorn]);
					
					//Evento después de eliminar una categoría
					$location = (substr_count(actualURL(), $cat->ID_CatTorn) > 0) ? base() . 'torneos/inicio/' . $torneo.suffix() : actualURL();
				?>
				<li class="list-group-item cat-equipos">
					<div class="btn-group pull-right marginLeft10">
						<?php if($equiposCantidad == 0){ ?><a class="btn btn-xs btn-danger btn-eliminar-cat" href="#" title="Eliminar esta categoría" data-id="<?=$cat->ID_CatTorn?>" data-tabla="torneos_cats" data-location="<?=$location?>"><i class="fa fa-times"></i></a><?php } ?>
						<a class="btn btn-xs btn-primary" href="<?=base() . 'torneo/equipos/listado/' . $info->ID_Torneo . '/' . $cat->ID_CatTorn . suffix()?>" title="Gestiona los equipos de esta categoría"><i class="fa fa-users"></i> <?=$equiposCantidad?></a>
					</div>
					<a href="<?=base()?>torneo/partidos/listado/<?=$info->ID_Torneo?>/<?=$cat->ID_CatTorn?><?=suffix()?>"><?=$cat->NomCat?></a>
				</li>
				<?php } ?>
			</ul>
		</div>
		
		<div class="panel panel-default">
			<div class="panel-heading"><i class="fa fa-gears"></i> Acciones</div>
			<ul class="list-group menuSecundario">
				<li class="list-group-item"><a href="#" id="editarTorneo"><i class="fa fa-pencil"></i> Editar información del torneo</a></li>
				<li class="list-group-item"><a href="#" id="partidosPorCancha"><i class="fa fa-tasks"></i> Lista de partidos por cancha</a></li>
				<li class="list-group-item"><a href="<?=base()?>torneo/sanciones/listado/<?=$info->ID_Torneo.suffix()?>"><i class="fa fa-bullhorn"></i> Actualizar sancionados</a></li>
			</ul>
		</div>
		
		<div class="panel panel-default">
			<div class="panel-heading"><i class="fa fa-repeat"></i> Estatus</div>
			<ul class="list-group menuSecundario">
				<li class="list-group-item">
					Rol general
					<span class="pull-right">
						<a href="#" class="btn btn-xs badge cambioRol" data-tabla="torneos" data-id="<?=$info->ID_Torneo?>" data-campo="TabTmpAct" data-valor="<?=$info->TabTmpAct?>"></a>
					</span>
				</li>
				<li class="list-group-item">
					Torneo
					<span class="pull-right">
						<a href="#" class="btn btn-xs pull-right badge cambioEstatus" data-tabla="torneos" data-id="<?=$info->ID_Torneo?>" data-campo="estatus" data-valor="<?=$info->estatus?>"></a>
					</span>
				</li>
				<li class="list-group-item">
					Registro
					<span class="pull-right">
						<a href="#" class="btn btn-xs pull-right badge cambioEstatus" data-tabla="config" data-id="1" data-campo="val_config" data-valor="<?=$config['estatus_registro']?>"></a>
					</span>
				</li>
			</ul>
		</div>
	</div>
	<div class="col-md-9">
		<h3 class="page-header"><i class="fa fa-fw fa-sitemap"></i> <?=$info->NomTorneo?></span></h3>
		<?php
		//Notificación de edición / creación
		if($this->session->flashdata('creado')) echo "<div class='alert alert-success autoHide'>El registro ha sido creado exitosamente. <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
		if($this->session->flashdata('actualizado')) echo "<div class='alert alert-success autoHide'>El registro ha sido creado actualizado. <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
		
		//Mostrar vista cargada
		echo $vista;
		
		//Mostrar formulario de edición / creación
		echo $formularioTorneo;
		?>
	</div>
</div>
<!-- Formulario de partidos por cancha -->
<div class="modal fade" id="modalFormularioPartidosCancha">
  <div class="modal-dialog" id="modalFormularioPartidosCanchaContenido">
		<form method="get" action="<?=base()?>torneo/print_partidos_cancha/imprimir<?=suffix()?>" class="modal-content" target="_new">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Generar lista de partidos por cancha</h4>
				</div>
				<div class="modal-body">					
					<div class="row">
						<div class="col-xs-4">
							<div class="form-group marginBottom0">
								<label for="fechaInicial">Fecha inicial</label>
								<input type="text" class="form-control date" id="fechaInicial" name="fechaInicial" data-minlength="10" data-error="Especifique la fecha inicial" readonly required>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="form-group marginBottom0">
								<label for="fechaFinal">Fecha final</label>
								<input type="text" class="form-control date" id="fechaFinal" name="fechaFinal" data-minlength="10" data-error="Especifique la fecha final" readonly required>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="form-group marginBottom0">
								<label for="tipoCancha">Tipo de cancha</label>
								<select id="tipoCancha" name="tipoCancha" class="form-control" data-minlength="1" data-error="Elija una opción" required>
									<option value="">Seleccione</option>
									<option value="1">Chica</option>
									<option value="2">Profesional</option>
								</select>
								<div class="help-block with-errors"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
					<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Generar</button>
				</div>
			</div>
		</form>
	</div>
</div>