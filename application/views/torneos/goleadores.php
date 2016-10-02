<ol class="breadcrumb">
  <li><a href="<?=base()?>torneos/listado<?=suffix()?>">Torneos</a></li>
  <li class="active"><a href="<?=base()?>torneos/partidos/listado/<?=$torneo?>/<?=$categoria->ID_CatTorn?><?=suffix()?>"><?=$categoria->NomCat?></a></li>
</ol>

<div class="btn-group pull-right" role="group">
  <div class="btn-group" role="group">
    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<i class="fa fa-users marginRight10"></i> <?=$equipo['NomEquipo']?>
			<span class="caret"></span>
    </button>
		<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
			<?php
			foreach($equipos as $e)
			{
				if($e->ID_Equipo != $equipo['ID_Equipo'])
				{
			?>
			<li><a href="<?=base()?>torneo/jugadores/goleadores/<?=$info->ID_Torneo?>/<?=$categoria->ID_CatTorn?>/<?=$limit?>/<?=$e->ID_Equipo?><?=suffix()?>"><?=$e->NomEquipo?></a></li>
			<?php
				}
			}
			
			if($equipo['ID_Equipo'] > 0){
			?>
			<li role="separator" class="divider"></li>
			<li><a href="<?=base()?>torneo/jugadores/goleadores/<?=$info->ID_Torneo?>/<?=$categoria->ID_CatTorn?>/<?=$limit?><?=suffix()?>">Todos los equipos</a></li>
			<?php } ?>
		</ul>
  </div>

  <div class="btn-group" role="group">
    <button class="btn btn-default dropdown-toggle " type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
			<i class="fa fa-eye marginRight10"></i> Mostrando <?=$limit?> jugadores
			<span class="caret"></span>
		</button>
    </button>
			<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
				<li><a href="<?=base()?>torneo/jugadores/goleadores/<?=$info->ID_Torneo?>/<?=$categoria->ID_CatTorn?>/10<?=suffix()?>">Mostrar 10</a></li>
				<li><a href="<?=base()?>torneo/jugadores/goleadores/<?=$info->ID_Torneo?>/<?=$categoria->ID_CatTorn?>/20<?=suffix()?>">Mostrar 20</a></li>
				<li><a href="<?=base()?>torneo/jugadores/goleadores/<?=$info->ID_Torneo?>/<?=$categoria->ID_CatTorn?>/50<?=suffix()?>">Mostrar 50</a></li>
				<li><a href="<?=base()?>torneo/jugadores/goleadores/<?=$info->ID_Torneo?>/<?=$categoria->ID_CatTorn?>/0<?=suffix()?>">Mostrar todos</a></li>
			</ul>
  </div>
</div>

<h2>Goleadores del torneo</h2>

<div id="tablaListadoContenedor">
	<div id="tablaListado">
		<?php
		if(count($goleadores) > 0)
		{
		?>
		<table class="table table-border-top table-striped table-hover table-condensed table-middle">
			<thead>
				<tr>
					<th></th>
					<th>Jugador</th>
					<th>Equipo</th>
					<th>Goles</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach($goleadores as $k => $r)
				{
				?>
				<tr>
					<td><?=$k + 1?></td>
					<td><?=$r->NomJug?> <?=$r->ApeJug?></td>
					<td><?=$r->NomEquipo?></td>
					<td><?=$r->NumGoles?></td>
				</tr>
				<?php
				}
				?>
			</tbody>
		</table>
		<?php
		}
		else echo "<div class='alert alert-warning marginTop20'><i class='fa fa-info-circle'></i> No hay registros actualmente</div>";
		?>
	</div>
</div>