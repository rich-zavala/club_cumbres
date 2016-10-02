<ol class="breadcrumb">
  <li><a href="<?=base()?>torneos/listado<?=suffix()?>">Torneos</a></li>
	<li><a href="<?=base()?>torneo/partidos/listado/<?=$torneo?>/<?=$categoria->ID_CatTorn?><?=suffix()?>"><?=$categoria->NomCat?></a></li>
	<li class="active">Gestión de grupo: <b><?=$breadcrumb_titulo?></b></li>
</ol>

<h2>Gestión de grupo</h2>
<hr>
<?php
if($this->session->flashdata('grupo_creado')) echo "<div class='alert alert-success autoHide'>El registro ha sido creado exitosamente. <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
?>

<form action="<?=base()?>torneo/partidos/submit<?=suffix()?>" method="post" id="grupoForm" class="marginBottom10">
	<div class="row">
		<div class="col-xs-6">
			<div class="form-group margin0">
				<label for="formJugadores">Nombre o número del grupo</label>
				<input type="text" class="form-control input-sm" id="formDenomVG" name="DenomVG" value="<?=@$grupo_actual->DenomVG?>" data-error="Especifique el nombre del grupo" required autofocus>
				<div class="help-block with-errors"></div>
			</div>
		</div>
		<div class="col-xs-6">
			<div class="form-group margin0">
				<label for="formJugadores">Tipo</label>
				<select class="form-control input-sm" id="formDenomVG" name="Es_Public" data-error="Especifique el tipo de grupo" required>
					<option value="1" <?=selected((int)@$grupo_actual->Es_Public, 1)?>>Público</option>
					<option value="0" <?=selected((int)@$grupo_actual->Es_Public, 0)?>>Privado</option>
				</select>
				<div class="help-block with-errors"></div>
			</div>
		</div>
	</div>
	<hr>
	<div class="form-group">
		<label>Equipos que integran este grupo</label>
	</div>
	<?php
	foreach(array_chunk($info->equipos[$categoria->ID_CatTorn], 3) as $equipos)
	{
		echo '<div class="row marginBottom10">';
		foreach($equipos as $v)
		{
			//Identificar si el equipo ya está dentro de este grupo
			$checked = '';
			if(isset($grupo_actual->equipos) and count($grupo_actual->equipos) > 0)
			{
				foreach($grupo_actual->equipos as $ea)
				{
					if($ea->ID_Equipo == $v->ID_Equipo)
					{
						$checked = 'checked';
						break;
					}
				}
			}
	?>
	<div class="col-sm-4">
		<label class="btn btn-sm btn-checkbox">
			<input type="checkbox" autocomplete="off" name="equipos[]" value="<?=$v->ID_Equipo?>" <?=$checked?>> <?=$v->NomEquipo?>
		</label>
	</div>
	<?php
		}
		echo '</div>';
	}
	?>
	<input type="hidden" id="grupoFormTorneo" name="torneo" value="<?=@(int)$info->ID_Torneo?>" />
	<input type="hidden" id="grupoFormCat" name="ID_CatTorn" value="<?=@(int)$categoria->ID_CatTorn?>" />
	<input type="hidden" id="grupoFormId" name="id" value="<?=@(int)$grupo_actual->ID_VueltaGpo?>" />
	<hr>
	<div class="alert alert-danger form-error"></div>
	<div class="alert alert-info form-wait"><i class="fa fa-spinner fa-spin"></i> Registrando información...</div>
	<div class="alert alert-success form-success"><i class="fa fa-check"></i> La actualización se ejecutó exitosamente.</div>
	<div class="form-botones">
		<a href="<?=base()?>torneo/partidos/listado/<?=$torneo?>/<?=$categoria->ID_CatTorn?><?=(@(int)$grupo_actual->ID_VueltaGpo > 0) ? '/' . $grupo_actual->ID_VueltaGpo : ''?><?=suffix()?>" class="btn btn-default pull-right"><i class="fa fa-times"></i> Cancelar</a>
		<button type="submit" class="btn btn-primary" id="formCrearSubmit"><i class="fa fa-save"></i> Registrar grupo</button>
	</div>
</form>