<ol class="breadcrumb">
  <li><a href="<?=base()?>torneos/listado<?=suffix()?>">Torneos</a></li>
	<li><a href="<?=base()?>torneo/partidos/listado/<?=$torneo?>/<?=$categoria->ID_CatTorn?><?=suffix()?>"><?=$categoria->NomCat?></a></li>
	<li><a href="<?=base()?>torneo/partidos/listado/<?=$torneo?>/<?=$categoria->ID_CatTorn?>/<?=$grupo->id?><?=suffix()?>">Grupo <i><?=$grupo->nombre?></i></a></li>
	<li class="active">Puntaje de partido</li>
</ol>

<h2>Puntaje de partido</h2>
<hr>
<?php
if($this->session->flashdata('grupo_creado')) echo "<div class='alert alert-success autoHide'>El registro ha sido creado exitosamente. <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
// p($info);
?>

<form action="<?=base()?>torneo/partidos/submit<?=suffix()?>" method="post" id="puntajeForm" class="marginBottom10">
	
	<div class="alert alert-warning">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<table>
			<tr>
				<td class="top paddingRight10" width="1"><i class="fa fa-info-circle"></i></td>
				<td class="ln1">Establezca los goles anotados por cada jugador para definir el equipo ganador.<br>
				En caso de que el partido haya sido ganado por shoot outs indíquelo y señale al equipo que resultó ganador.
				</td>
			</tr>
		</table>
	</div>
	
	<div class="row">
		<div class="col-xs-12">
			<div class="row marginBottom20">
				<div class="col-xs-6"><?=$partido->fecha?>, <?=$partido->hora?> hrs. <?=canchaTxt($partido->TipoCancha)?>.</div>				
				<div class="col-xs-6 text-right">
					<label>
						<input id="shootouts" name="shootouts" value="1" type="checkbox" <?=($partido->ganoSO > 0) ? 'checked' : ''?>> Partido ganado con <i>shoot outs</i>
					</label>
				</div>
			</div>
			
			<table class="table table-condensed table-hover table-middle margin0 w100">
				<thead>
					<tr class="info">
						<th width="50%" class="text-center" colspan="2">
							<label class="margin0">
								<?=$partido->equipos[0]['nombre']?> <input type="radio" class="marginLeft10 shootEquipo" name="shootEquipo" value="<?=$partido->equipos[0]['id']?>" data-toggle="tooltip" data-placement="top" <?=($partido->equipos[0]['id'] == $partido->ganoSO) ? 'checked' : ''?> title="Ganó por shoot outs">
							</label>
						</th>
						<th>V.S.</th>
						<th width="50%" class="text-center" colspan="2">
							<label class="margin0">
								<input type="radio" class="marginRight10 shootEquipo" name="shootEquipo" value="<?=$partido->equipos[1]['id']?>" data-toggle="tooltip" data-placement="top" <?=($partido->equipos[1]['id'] == $partido->ganoSO) ? 'checked' : ''?> title="Ganó por shoot outs"> <?=$partido->equipos[1]['nombre']?>
							</label>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr class="active">
						<td class="text-center" colspan="2"><input type="number" class="form-control input-sm text-center inputSmall puntajeSum" name="sumatoria[<?=$partido->equipos[0]['id']?>]" id="sumatoria1" readonly></td>
						<td></td>
						<td class="text-center" colspan="2"><input type="number" class="form-control input-sm text-center inputSmall puntajeSum" name="sumatoria[<?=$partido->equipos[1]['id']?>]" id="sumatoria2" readonly></td>
					</tr>
				</tbody>
				<tbody>
					<?php
					//Obtener cantidad máxima de jugadores
					$max = max(array( count($partido->equipos[0]['jugadores']), count($partido->equipos[1]['jugadores']) ));
					
					$i = 0;
					while($i <= $max)
					{
					?>
					<tr>
						<?php
						if(isset($partido->equipos[0]['jugadores'][$i]))
							jugadorGoles($partido->equipos[0]['jugadores'][$i], false, 0);
						else
							echo "<td colspan='2'></td>";
						?>
						<td></td>
						<?php
						if(isset($partido->equipos[1]['jugadores'][$i]))
							jugadorGoles($partido->equipos[1]['jugadores'][$i], true, 1);
						else
							echo "<td colspan='2'></td>";
						?>
					</tr>
					<?php
						$i++;
					}
					?>
				</tbody>
			</table>
		</div>
	</div>

	<input type="hidden" id="partidoFormTorneo" name="torneo" value="<?=@(int)$info->ID_Torneo?>" />
	<input type="hidden" id="partidoFormCat" name="categoria" value="<?=@(int)$categoria->ID_CatTorn?>" />
	<input type="hidden" id="partidoFormGrupo" name="grupo" value="<?=@(int)$partido->ID_VueltaGpo?>" />
	<input type="hidden" id="partidoFormJornada" name="jornada" value="<?=@(int)$partido->ID_Jornada?>" />
	<input type="hidden" id="partidoFormPartido" name="partido" value="<?=@(int)$partido->ID_Partido?>" />
	
	<div class="alert alert-danger form-error"></div>
	<div class="alert alert-info form-wait"><i class="fa fa-fw fa-spinner fa-spin"></i> Registrando información...</div>
	<div class="alert alert-success form-success"><i class="fa fa-fw fa-check"></i> La actualización se ejecutó exitosamente.</div>
	<div class="form-botones">
		<a href="<?=base()?>torneo/partidos/listado/<?=$torneo?>/<?=$categoria->ID_CatTorn?><?=suffix()?>" class="btn btn-default pull-right"><i class="fa fa-times"></i> Cancelar</a>
		<button type="submit" class="btn btn-primary" id="formCrearSubmit"><i class="fa fa-save"></i> Registrar puntajes</button>
	</div>
</form>