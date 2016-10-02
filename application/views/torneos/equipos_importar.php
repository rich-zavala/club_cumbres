<ol class="breadcrumb">
  <li><a href="<?=base()?>torneos/listado<?=suffix()?>">Torneos</a></li>
  <li><a href="<?=base()?>torneo/partidos/listado/<?=$torneo?>/<?=$categoria->ID_CatTorn.suffix()?>"><?=$categoria->NomCat?></a></li>
  <li><a href="<?=base()?>torneo/equipos/listado/<?=$torneo?>/<?=$categoria->ID_CatTorn.suffix()?>">Equipos</a></li>
</ol>


<h2>Importar equipo de otro torneo</h2>
<hr>

<div class="alert alert-info">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
	<i class="fa fa-info-circle"></i> Seleccione el equipo que desee importar a este torneo. Sólamente se enlistan los equipos de esta misma categoría.
</div>

<div class="row">
	<div  id="tablaListadoContenedor" class="col-xs-6">
		<label>Equipos que están actualmente en este torneo</label>
		<div id="tablaListado">
			<?php
			if(count($equipos) > 0)
			{
			?>
			<ul class="list-group">
				<?php
				foreach($equipos as $r)
				{
					$trColor = (@$this->session->flashdata('equipo_manipulado') == $r->ID_Equipo) ? 'list-group-item-success' : '';
				?>
				<li class="list-group-item <?=$trColor?> paddingTop5 paddingBottom5"><?=$r->NomEquipo?></li>
				<?php
				}
				?>
			</ul>
			<?php
			}
			else echo "<div class='alert alert-warning'><i class='fa fa-info-circle'></i> No hay registros actualmente.</div>";
			?>
		</div>
	</div>
	
	<div class="col-xs-6">
		<label>Equipos de otros torneos</label>
		<?php
		if(@count($torneos) > 0)
		{
		?>
		<div class="panel-group" id="accordion">
		<?php
			foreach($torneos as $k => $torneo_info)
			{
		?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapseTorneo<?=$k?>" aria-expanded="false" class="no-click"><?=$torneo_info->NomTorneo?></a>
					</h4>
				</div>
				<div id="collapseTorneo<?=$k?>" class="panel-collapse collapse" role="tabpanel">
					<div class="panel-body">
						<ul class="list-group margin0">
							<?php foreach($torneo_info->equipos as $r){ ?>
							<li class="list-group-item paddingTop5 paddingBottom5 <?=@$trColor?>"><a href="#" class="btn btn-xs btn-success marginRight10 btn-importar" data-torneo="<?=htmlentities($torneo_info->NomTorneo)?>" data-equipo="<?=$r->NomEquipo?>" data-id="<?=$r->ID_Equipo?>"><i class="fa fa-arrow-left"></i></a> <?=$r->NomEquipo?></li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</div>
		<?php
			}
		?>
		</div>
		<?php
		}
		else echo "<div class='alert alert-warning'><i class='fa fa-info-circle'></i> No hay otros equipos disponibles.</div>";
		?>
	</div>
</div>

<div class="modal fade" id="modalFormularioImportar">
  <div class="modal-dialog modal-lg">
		<form action="<?=base()?>torneo/equipos/submit<?=suffix()?>" method="post" id="modalFormulario" class="modal-content">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body">					
					<div class="alert alert-warning"><i class="fa fa-warning"></i> Confirme la importación del equipo &quot;<b id="modalEquipoNombre"></b>&quot; del torneo &quot;<b id="modalTorneoNombre"></b>&quot;.</div>
					<div id="formWorking" class="alert alert-info margin0"><i class="fa fa-spinner fa-spin"></i> Importación en progreso...</div>
					<div id="formSuccess" class="alert alert-success margin0"><i class="fa fa-check"></i> El registro se realizó exitosamente.</div>
					<div id="formError" class="alert alert-danger"></div>
					<input type="hidden" name="torneo" value="<?=$torneo?>" />
					<input type="hidden" name="categoria" value="<?=$categoria->ID_CatTorn?>" />
					<input type="hidden" id="formEquipoId" name="equipoNuevo" />
				</div>
				<div class="modal-footer" id="formFooterCerrar">
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
				</div>
				<div class="modal-footer" id="formFooterBotones">
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
					<button type="submit" class="btn btn-primary" id="formImportarSubmit"><i class="fa fa-save"></i> Importar equipo</button>
				</div>
			</div>
    </form>
	</div>
</div>