<ol class="breadcrumb">
  <li><a href="<?=base()?>torneos/listado<?=suffix()?>">Torneos</a></li>
  <li><a href="<?=base()?>torneo/sanciones/listado/<?=$torneo.suffix()?>">Sanciones</a></li>
	<li class="active">Nueva sanción</li>
</ol>

<h2><i class="fa fa-bullhorn"></i> Registro de nueva sansión</h2>
<hr>

<form class="marginBottom20" id="sancionForm">
	
	<h4 class="text-warning">Información del jugador</h4>
	<div class="row">
		<div class="col-sm-5">
			<div class="form-group">
				<label>Jugador</label>
				<div class="form-control-static"><?=$jugador[0]->name?></div>
			</div>
		</div>
		<div class="col-sm-5">
			<div class="form-group">
				<label>Equipo</label>
				<div class="form-control-static"><?=$jugador[0]->NomEquipo?></div>
			</div>
		</div>
		<div class="col-sm-2">
			<div class="form-group">
				<label>Goles</label>
				<div class="form-control-static"><?=$jugador[0]->NumGoles?></div>
			</div>
		</div>
	</div>
	
	<hr class="marginTop0">
	
	<div id="sancionesContenedor">
		<div id="sancionesContenido">
			<h4 class="text-warning">Sanciones</h4>
			<?php if(!$conSanciones){ ?>
			<div class="alert alert-info"><i class="fa fa-info-circle"></i> El jugador no tiene sanciones actualmente</div>
			<?php } else { ?>
			<table class="table table-border-top table-striped table-hover table-middle wAuto">
				<thead>
					<tr>
						<th></th>
						<th>Juegos</th>
						<th>Jornadas</th>
						<th>Fecha de registro</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach($jugador as $k => $sancion)
					{
						if($sancion->En_Listado == 1)
						{
							$trColor = (@$this->session->flashdata('nueva_sancion') == $sancion->id) ? 'success' : '';
					?>
					<tr class="<?=$trColor?>">
						<td class="paddingRight20"><div class="badge"><?=$k+1?></div></td>
						<td><?=$sancion->PartSan?></td>
						<td><?=$sancion->JorSan?></td>
						<td><?=$sancion->fecha?></td>
						<td class="paddingLeft20"><a href="#" class="btn btn-circle btn-warning btn-eliminar-sancion" title="Eliminar sanción" data-id="<?=$sancion->id?>"><i class="fa fa-remove"></i></a></td>
					</tr>
					<?php
						}
					}
					?>
				</tbody>
			</table>
			<?php } ?>
		</div>
	</div>
	
	<hr>
	<h4 class="text-warning">Registro de nueva sanción</h4>
  
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<label for="PartSan">Número de partidos</label>
				<input type="text" class="form-control" id="PartSan" name="PartSan" data-error="Especifique el número de partidos." required>
				<div class="help-block with-errors"></div>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group">
				<label for="JorSan">Jornadas</label>
				<input type="text" class="form-control" id="JorSan" name="JorSan" data-error="Especifique el número de jornadas." required>
				<div class="help-block with-errors"></div>
			</div>
		</div>
	</div>
	
	<input type="hidden" id="sancionJugador" name="jugador" value="<?=@(int)$jugador[0]->ID_Jugador?>" />
	
	<div class="alert alert-danger form-error"></div>
	<div class="alert alert-info form-wait"><i class="fa fa-spinner fa-spin"></i> Registrando información...</div>
	<div class="alert alert-success form-success"><i class="fa fa-check"></i> La actualización se ejecutó exitosamente.</div>
	<div class="form-botones">
		<a href="<?=base()?>torneo/sanciones/listado/<?=$torneo.suffix()?>" class="btn btn-default pull-right"><i class="fa fa-times"></i> Cancelar</a>
		<button type="submit" class="btn btn-primary" id="formCrearSubmit"><i class="fa fa-save"></i> Registrar sanción</button>
	</div>
</form>