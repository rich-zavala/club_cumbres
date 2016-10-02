<div class="row">
	<div class="col-xs-4">
		<div class="form-group">
			<label for="formJugadorNombre">Nombre</label>
			<input type="text" class="form-control input-sm" id="formJugadorNombre" name="NomJug" data-minlength="4" data-error="Especifique el nombre del jugador" value="<?=$info->NomJug?>" required>
			<div class="help-block with-errors"></div>
		</div>
	</div>
	<div class="col-xs-4">
		<div class="form-group">
			<label for="formJugadorNombre">Apellido</label>
			<input type="text" class="form-control input-sm" id="ApeJug" name="ApeJug" data-minlength="4" data-error="Especifique el apellido del jugador" value="<?=$info->ApeJug?>" required>
			<div class="help-block with-errors"></div>
		</div>
	</div>
	<div class="col-xs-4">
		<div class="form-group">
			<label for="formJugadorFNac">Fecha de nacimiento</label>
			<input type="text" class="form-control input-sm date" id="formJugadorFNac" name="FNacJug" value="<?=$info->FNacJug?>">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-6">
		<div class="form-group">
			<label for="formJugadorTelCasa">Teléfono de casa</label>
			<input type="text" class="form-control input-sm" id="formJugadorTelCasa" name="TelCasaJug" value="<?=$info->TelCasaJug?>">
		</div>
	</div>
	<div class="col-xs-6">
		<div class="form-group">
			<label for="formJugadorTel">Teléfono celular</label>
			<input type="text" class="form-control input-sm" id="formJugadorTel" name="TelJug" value="<?=$info->TelJug?>">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-6">
		<div class="form-group">
			<label for="formJugadorEmail">Email</label>
			<input type="email" class="form-control input-sm" id="formJugadorEmail" name="EmailJug" value="<?=$info->EmailJug?>">
		</div>
	</div>
	<div class="col-xs-6">
		<div class="form-group">
			<label for="formJugadorNumAfil">Número de afiliación</label>
			<input type="number" class="form-control input-sm" id="formJugadorNumAfil" name="NumAfiJug" value="<?=$info->NumAfiJug?>">
		</div>
	</div>
</div>
<input type="hidden"name="ID_Jugador" value="<?=@(int)$info->ID_Jugador?>" />