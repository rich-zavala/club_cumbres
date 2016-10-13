<ol class="breadcrumb">
  <li><a href="<?=base()?>torneos/listado<?=suffix()?>">Torneos</a></li>
	<li class="active"><?=$categoria->NomCat?></li>
</ol>

<div class="pull-right">
	<div class="btn-group">
		<?php if(!$sinGrupo) { ?>
		<div class="btn-group" role="group">
			<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
				Grupo <b>&quot;<?=$grupo_actual->DenomVG?>&quot;</b>
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
				<li role="presentation" class="dropdown-header">Cambiar de grupo</li>
				<?php foreach($grupos as $grupo){ ?>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="<?=base()?>torneo/partidos/listado/<?=$torneo?>/<?=$categoria->ID_CatTorn?>/<?=$grupo->ID_VueltaGpo.suffix()?>"><i class="fa fa-fw fa-arrow-circle-right"></i>  <b><?=$grupo->DenomVG?></b></a></li>
				<?php } ?>
				<li role="presentation" class="divider"></li>
				<li role="presentation" class="dropdown-header">Acciones para este grupo</li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="#" class="btn-jornada-crear"><i class="fa fa-fw fa-calendar"></i> Crear jornada</a></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="<?=base()?>torneo/partidos/grupo_formulario/<?=$torneo?>/<?=$categoria->ID_CatTorn?>/<?=$grupo_actual->ID_VueltaGpo.suffix()?>"><i class="fa fa-fw fa-pencil"></i> Editar</a></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="#" class="btn-eliminar" data-tabla="vueltas_gpos" data-id="<?=$grupo_actual->ID_VueltaGpo?>" data-location="<?=base()?>torneo/partidos/listado/<?=$torneo?>/<?=$categoria->ID_CatTorn.suffix()?>"><i class="fa fa-fw fa-times"></i> Eliminar</a></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="<?=base()?>torneo/jugadores/goleadores/<?=$torneo?>/<?=$categoria->ID_CatTorn.suffix()?>"><i class="fa fa-fw fa-futbol-o"></i> Lista de goleadores</a></li>
				<li role="presentation" class="dropdown-header marginTop10">Acciones para esta categoría</li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="#" id="tablaPosiciones" data-categoria="<?=$categoria->ID_CatTorn?>"><i class="fa fa-fw fa-table"></i> Tabla de posiciones</a></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="#" class="btn-email-masivo" data-destino="categoria" data-destino-valor="<?=$categoria->ID_CatTorn?>"><i class="fa fa-fw fa-envelope"></i> Enviar e-mail masivo</a></li>


				<!--<li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:alert('En construcción...')"><i class="fa fa-archive"></i> Guardar historial</a></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:alert('En construcción...')"><i class="fa fa-power-off"></i> Limpiar puntuaciones totales</a></li>-->
			</ul>
		</div>
		<?php
		}

		if(!$sinEquipos)
		{
		?>
		<a href="<?=base()?>torneo/partidos/grupo_formulario/<?=$torneo?>/<?=$categoria->ID_CatTorn.suffix()?>" class="btn btn-default"><i class="fa fa-plus"></i> Crear grupo</a>
		<?php
		}
		?>
	</div>
	<?php if(isset($grupo_actual)){ ?><div class="marginBottom-15 marginTop5 font12 text-right text-muted">Este grupo es <?=$grupo_actual->Es_Public == 1 ? 'público' : 'privado'?></div><?php } ?>
</div>

<h2 class="marginBottom0"><i class="fa fa-futbol-o"></i> Partidos</h2>
<div class="clearfix"></div>
<hr>

<div id="infoContenedor">
	<?php if($sinEquipos){ ?>
	<div class="alert alert-danger">No hay equipos en esta categoría actualmente.<br><a href="<?=base()?>torneo/equipos/listado/<?=$torneo?>/<?=$categoria->ID_CatTorn.suffix()?>">Haz click aquí para continuar</a>.</div>
	<?php } ?>

	<?php if(!$sinEquipos and $sinGrupo){ ?>
	<div class="alert alert-danger">No hay grupos en esta categoría actualmente.<br><a href="<?=base()?>torneo/partidos/grupo_formulario/<?=$torneo?>/<?=$categoria->ID_CatTorn.suffix()?>">Haz click aquí para crear uno</a>.</div>
	<?php } ?>

	<?php if(!$sinEquipos and !$sinGrupo and $sinJornadas){ ?>
	<div class="alert alert-danger">No hay jornadas en este grupo actualmente.<br><a href="#" class="btn-jornada-crear">Haz click aquí para crear una</a>.</div>
	<?php } ?>

	<?php
	if(!$sinJornadas)
	{
		foreach($grupo_actual->jornadas as $k => $jornada)
		{
	?>
	<div id="jornada-<?=$jornada->ID_Jornada?>" class="panel panel-info">
		<!--<div class="panel-heading" data-toggle="tooltip" data-placement="left" title="Jornada <?=$k+1?>">-->
		<div class="panel-heading">
			<b><?=$jornada->DenomJor?></b>
			<div class="btn-group btn-group-xs pull-right" role="group">
				<a href="#" class="btn btn-success btn-jornada-editar" data-id="<?=$jornada->ID_Jornada?>" data-nombre="<?=htmlentities($jornada->DenomJor)?>" title="Renombrar"><i class="fa fa-pencil"></i></a>
				<a href="#" class="btn btn-warning btn-jornada-eliminar" data-id="<?=$jornada->ID_Jornada?>" data-tabla="jornadas" title="Eliminar"><i class="fa fa-times"></i></a>
				<a href="#" class="btn btn-default btn-partido-crear" title="Crear partido" data-jornada="<?=$jornada->ID_Jornada?>"><i class="fa fa-futbol-o"></i> Crear partido</a>
			</div>
		</div>
		<div class="panel-body padding0">
			<?php
			if(count($jornada->partidos) > 0)
			{
			?>
			<table class="table-condensed table-striped w100">
			<?php
				foreach($jornada->partidos as $partido)
				{
					$puntaje1 = $puntaje2 = $ganador1 = $ganador2 = '';
					$editable = true;
					if($partido->Punt_Fue_Asig == 1)
					{
						$editable = false;
						$puntaje1 = (int)$partido->equipos[0]['puntaje'];
						$puntaje2 = (int)$partido->equipos[1]['puntaje'];

						if($puntaje1 > $puntaje2) $ganador1 = '<i class="fa fa-trophy marginRight10" title="Equipo ganador"></i>';
						if($puntaje2 > $puntaje1) $ganador2 = '<i class="fa fa-trophy marginLeft10" title="Equipo ganador"></i>';

						if($partido->ganoSO == $partido->equipos[0]['id']) $ganador1 = '<i class="fa fa-trophy marginRight10" title="Ganador por shoot outs"></i> <span class="font11">(S.O.)</span>';
						if($partido->ganoSO == $partido->equipos[1]['id']) $ganador2 = '<i class="fa fa-trophy marginLeft10" title="Ganador por shoot outs"></i> <span class="font11">(S.O.)</span>';
					}
					$href = base() . "torneo/partidos/puntaje/{$torneo}/{$categoria->ID_CatTorn}/{$grupo_actual->ID_VueltaGpo}/{$partido->ID_Partido}" . suffix();
			?>
				<tr>
					<td>
						<div class="row marginLeft0">
							<div class="col-xs-5 text-right">
								<div class="row">
									<div class="col-xs-10 padding0 partidoPuntaje"><a href="<?=$href?>" class="text-primary" title="Establecer puntaje"><?=$partido->equipos[0]['nombre']?></a> <?=$ganador1?></div>
									<div class="col-xs-2 padding0 negritas"><?=$puntaje1?></div>
								</div>
							</div>
							<div class="col-xs-2 text-center"> V.S.</div>
							<div class="col-xs-5">
								<div class="row">
									<div class="col-xs-2 padding0 negritas"><?=$puntaje2?></div>
									<div class="col-xs-10 padding0 partidoPuntaje"><?=$ganador2?> <a href="<?=$href?>" class="text-primary" title="Establecer puntaje"><?=$partido->equipos[1]['nombre']?></a></div>
								</div>
							</div>
						</div>
					</td>
					<td class="text-right tabla-info">
						<div class="tabla-fecha"><?=($partido->Es_Pendiente == 0 ? $partido->fecha . ", " . $partido->hora : '<i>Pendiente</i>')?></div>
						<div class="tabla-cancha text-center"><span class="label label-default" title="<?=canchaTxt($partido->TipoCancha)?>"><?=cancha($partido->TipoCancha)?></span></div>
						<div class="btn-group marginLeft10">
							<a type="button" class="btn btn-xs btn-success <?=$editable ? 'partido-editar' : 'partido-no-editable'?>" title="<?=$editable ? 'Editar partido' : 'Este partido ya tiene puntaje establecido. No se puede editar.'?>" data-info="<?=htmlentities(json_encode($partido))?>"><i class="fa fa-<?=$editable ? 'pencil' : 'ban'?>"></i></a>
							<!--<a type="button" class="btn btn-xs btn-primary partido-puntaje" title="Editar puntaje"><i class="fa fa-table"></i></a>-->
							<a href="<?=base()?>torneo/print_cedulas/imprimir/<?=$partido->ID_Partido.suffix()?>" target="_new" type="button" class="btn btn-xs btn-info" title="Imprimir cédula"><i class="fa fa-print"></i></a>
							<a type="button" class="btn btn-xs btn-warning partido-eliminar" title="Eliminar partido" data-id="<?=$partido->ID_Partido?>" data-jornada="<?=$jornada->ID_Jornada?>"><i class="fa fa-remove"></i></a>
						</div>
					</td>

				</tr>
			<?php
				}

				foreach($jornada->descansos as $descanso)
				{
			?>
				<tr>
					<td class="text-center padding0 font11 text-muted paddingLeft15"><?=$descanso->NomEquipo?></td>
					<td class="text-center padding0 font11 text-muted"><span>Descansa</span></td>
				</tr>
			<?php
				}
			?>
			</table>
			<?php
			}
			else
			{
			?>
			<div class="alert alert-danger marginTop10 marginBottom10 marginLeft10 marginRight10">Aún no hay partidos programados en esta jornada.<br><a href="#" class="btn-partido-crear" data-jornada="<?=$jornada->ID_Jornada?>">Haz click aquí para crear uno</a>.</div>
			<?php } ?>
		</div>
	</div>
	<?php
		}
	}
	?>
</div>

<div class="modal fade" id="modalFormularioCrear">
  <div class="modal-dialog">
		<form id="jornadaForm" class="modal-content">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="formNombre">Nombre de la jornada</label>
						<input type="text" class="form-control" id="formNombre" name="DenomJor" data-minlength="1" data-error="Especifique el nombre de la jornada" maxlength="20" autofocus required>
						<div class="help-block with-errors"></div>
					</div>
					<div class="alert alert-danger form-error"></div>
					<div class="alert alert-info form-wait"><i class="fa fa-spinner fa-spin"></i> Registrando información...</div>
					<div class="alert alert-success form-success"><i class="fa fa-check"></i> El registro se realizó exitosamente.</div>
					<input type="hidden" id="formId" name="id" />
					<input type="hidden" id="formGrupoId" name="grupoId" value="<?=@(int)$grupo_actual->ID_VueltaGpo?>" />
				</div>
				<div class="modal-footer form-footer-cerrar">
					<button type="button" class="btn btn-primary btn-jornada-crear"><i class="fa fa-refresh"></i> Registrar otra jornada</button>
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
				</div>
				<div class="modal-footer form-footer-botones">
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
					<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Registrar jornada</button>
				</div>
			</div>
    </form>
	</div>
</div>

<div class="modal fade" id="modalFormularioPartido">
  <div class="modal-dialog" id="modalFormularioPartidoContenido">
		<form method="post" id="partidoForm" class="modal-content">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body">
					<?php if(count($grupo_actual->equipos) < 2){ ?>
					<div class="alert alert-warning margin0"><i class="fa fa-warning"></i> No hay equipos suficientes para crear un partido.</div>
					<?php } else { ?>
					<label for="equipo1" class="marginBottom5">Elija los equipos que se enfrentarán</label>
					<div class="row">
						<div class="col-xs-5">
							<select id="equipo1" name="eq1" data-equipo="1" class="form-control s-equipo">
								<?php foreach($grupo_actual->equipos as $equipo){ ?>
								<option value="<?=$equipo->ID_Equipo?>"><?=$equipo->NomEquipo?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-xs-2 text-center negritas paddingTop5">
							V.S.
						</div>
						<div class="col-xs-5">
							<select id="equipo2" name="eq2" data-equipo="2" class="form-control s-equipo" required>
								<?php foreach($grupo_actual->equipos as $equipo){ ?>
								<option value="<?=$equipo->ID_Equipo?>"><?=$equipo->NomEquipo?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-xs-4">
							<div class="form-group marginBottom0">
								<label for="formPartidoJornada">Jornada</label>
								<select id="formPartidoJornada" name="idjorn" class="form-control" required>
									<?php foreach($grupo_actual->jornadas as $jornada){ ?>
									<option value="<?=$jornada->ID_Jornada?>"><?=$jornada->DenomJor?></option>
									<?php } ?>
								</select>
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
						<div class="col-xs-4">
							<div class="form-group marginBottom0">
								<label for="es_pendiente">Marcar pendiente</label>
								<select id="es_pendiente" name="es_pendiente" class="form-control">
									<option value="0">No</option>
									<option value="1">Sí</option>
								</select>
							</div>
						</div>
					</div>
					<div id="formPartidoTiempos">
						<hr>
						<div class="row">
							<div class="col-xs-6">
								<div class="form-group marginBottom0">
									<label for="formFecha">Fecha</label>
									<input type="text" class="form-control date" id="formFecha" name="partidoFecha" data-minlength="10" data-error="Especifique la fecha del partido" readonly required>
								</div>
							</div>
							<div class="col-xs-6">
								<div class="form-group marginBottom0">
									<label for="formHora">Hora</label>
									<input type="text" class="form-control time" id="formHora" name="partidoHora" data-minlength="8" data-error="Especifique la hora del partido" required>
                  <div class="help-block with-errors"></div>
								</div>
							</div>
						</div>
					</div>
          <hr>
          <div class="row">
            <div class="col-xs-12">
              <div class="form-horizontal">
                <div class="form-group">
                  <label for="formArbitro1" class="col-sm-3 control-label text-left">Árbitro central</label>
                  <div class="col-sm-9">
                    <select type="text" class="form-control" id="formArbitro1" name="formArbitro1" data-error="Especifique el árbitro central" required>
              				<option value="">-- Ninguno --</option>
              				<?php foreach($arbitros as $arbitro){ ?>
              					<option value="<?=$arbitro->id?>" <?=selected($arbitro->id, @$arbitros->arbitro1)?>><?=$arbitro->nombre?></option>
              				<?php } ?>
              			</select>
              			<div class="help-block with-errors"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div class="form-horizontal">
                <div class="form-group">
                  <label for="formArbitro2" class="col-sm-3 control-label text-left">Árbitro auxiliar 1</label>
                  <div class="col-sm-9">
                    <select type="text" class="form-control" id="formArbitro2" name="formArbitro2">
              				<option value="">-- Ninguno --</option>
              				<?php foreach($arbitros as $arbitro){ ?>
              					<option value="<?=$arbitro->id?>" <?=selected($arbitro->id, @$arbitros->arbitro2)?>><?=$arbitro->nombre?></option>
              				<?php } ?>
              			</select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div class="form-horizontal">
                <div class="form-group">
                  <label for="formArbitro3" class="col-sm-3 control-label text-left">Árbitro auxiliar 2</label>
                  <div class="col-sm-9">
                    <select type="text" class="form-control" id="formArbitro3" name="formArbitro3">
                      <option value="">-- Ninguno --</option>
                      <?php foreach($arbitros as $arbitro){ ?>
                        <option value="<?=$arbitro->id?>" <?=selected($arbitro->id, @$arbitros->arbitro3)?>><?=$arbitro->nombre?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>


					<input type="hidden" id="formPartidoId" name="id" />

					<div class="alert alert-danger form-error marginTop20"></div>
					<div class="alert alert-info form-wait marginTop20"><i class="fa fa-fw fa-spinner fa-spin"></i> Registrando información...</div>
					<div class="alert alert-success form-success marginTop20"><i class="fa fa-fw fa-check"></i> El registro se realizó exitosamente.</div>
					<?php } ?>
				</div>
				<div class="modal-footer form-footer-cerrar">
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
				</div>
				<div class="modal-footer form-footer-botones">
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
					<?php if(count($equipos) >= 2){ ?>
					<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Registrar partido</button>
					<?php } ?>
				</div>
			</div>
    </form>
	</div>
</div>

<div class="modal fade" id="modalTablaPosiciones">
  <div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Tabla de posiciones</h4>
				</div>
				<div class="modal-body">
					<div id="modalTablaPosicionesContenido"></div>
					<div class="alert alert-danger marginTop20" id="modalTablaPosicionesError"><i class="fa fa-fw fa-warning"></i> Ha ocurrido un error. Intente nuevamente más tarde.</div>
					<div class="alert alert-info marginTop20" id="modalTablaPosicionesCargando"><i class="fa fa-fw fa-spinner fa-spin"></i> Solicitando información...</div>
				</div>
				<div class="modal-footer form-footer-botones">
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
				</div>
			</div>
    </div>
	</div>
</div>
