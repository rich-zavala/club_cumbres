<ol class="breadcrumb">
  <li><a href="<?=base()?>torneos/listado<?=suffix()?>">Torneos</a></li>
	<li class="active">Sanciones</li>
</ol>

<h2><i class="fa fa-bullhorn"></i> Sanciones</h2>

<div class="row">
	<div class="col-lg-12">
		<div class="input-group">
			<span class="input-group-addon" id="basic-addon1"><i class="fa fa-plus"></i> Registrar nueva sanción</span>
			<input type="text" class="form-control" id="jugadorNombre" placeholder="Escriba el nombre del jugador sancionado" data-provide="typeahead" data-torneo="<?=$torneo?>" autocomplete="off">
		</div>
	</div>
</div>

<div id="tablaListadoContenedor">
	<div id="tablaListado">
		<?php
		if(count($sanciones) > 0)
		{
		?>
		<table class="table table-border-top table-striped table-hover table-condensed table-middle marginTop20">
			<thead>
				<tr>
					<th>Jugador</th>
					<th>Equipo</th>
					<th class="paddingRight20" width="1">Sanciones</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($sanciones as $r){ ?>
				<tr>
					<td><?=$r->name?></td>
					<td><?=$r->NomEquipo?></td>
					<td class="text-center paddingRight20"><?=$r->sanciones?></td>
					<td class="text-center paddingLeft20" width="1" nowrap>
						<div class="btn-group-circle" role="group">
							<a href="<?=base()?>torneo/sanciones/registro/<?=$torneo?>/<?=$r->ID_Jugador.suffix()?>" class="btn btn-circle btn-danger marginRight10" title="Sancionar"><i class="fa fa-bullhorn"></i></a>
							<a href="#" class="btn btn-circle btn-warning btn-eliminar" title="Remover sanciones" data-id="<?=$r->ID_Jugador?>" data-tabla="sancionados"><i class="fa fa-remove"></i></a>
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
		else echo "<div class='alert alert-warning marginTop20'><i class='fa fa-info-circle'></i> No hay registros de sanciones actualmente</div>";
		?>
	</div>
</div>