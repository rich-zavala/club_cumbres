<div class="modal fade" id="modalFormulario">
	<div class="modal-dialog">
		<form action="<?=base()?>torneos/submit<?=suffix()?>" method="post" id="torneoForm" class="modal-content">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="formNombre">Nombre</label>
						<input type="text" class="form-control" id="formNombre" name="nombre" data-minlength="4" data-error="Especifique el nombre del torneo" value="<?=@htmlentities($NomTorneo)?>" required>
						<div class="help-block with-errors"></div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="formAgno">Año</label>
								<input type="number" class="form-control" id="formAgno" name="agno" data-minlength="4" data-error="Especifique el año del torneo" value="<?=@$YrTorn?>" required>
								<div class="help-block with-errors"></div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="formTipo">Tipo</label>
								<select class="form-control" id="formTipo" name="tipo" required>
									<option value="1" <?=selected(1, @(int)$TipoTorn)?>>Futbol Rapido</option>
									<option value="2" <?=selected(2, @(int)$TipoTorn)?>>Futbol soccer</option>
								</select>
							</div>
						</div>
					</div>

					<hr>
					<h4>Sueldos de árbitros por partido</h4>
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label for="sueldo1">Árbitro 1</label>
								<input type="number" class="form-control" id="sueldo1" name="sueldo1" step="any" data-minlength="2" data-error="Especifique este valor" value="<?=@$arbitros_sueldos->sueldo1?>" required>
								<div class="help-block with-errors"></div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for="sueldo2">Árbitro 2</label>
								<input type="number" class="form-control" id="sueldo2" name="sueldo2" step="any" data-minlength="2" data-error="Especifique este valor" value="<?=@$arbitros_sueldos->sueldo2?>" required>
								<div class="help-block with-errors"></div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for="sueldo3">Crono</label>
								<input type="number" class="form-control" id="sueldo3" name="sueldo3" step="any" data-minlength="2" data-error="Especifique este valor" value="<?=@$arbitros_sueldos->sueldo3?>" required>
								<div class="help-block with-errors"></div>
							</div>
						</div>
					</div>
					<div class="row">
					
						<div class="col-sm-4">
							<div class="form-group">
								<label for="sueldo3">Mesa</label>
								<input type="number" class="form-control" id="sueldo4" name="sueldo4" step="any" data-minlength="2" data-error="Especifique este valor" value="<?=@$arbitros_sueldos->sueldo4?>" required>
								<div class="help-block with-errors"></div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for="sueldo3">Servicio de arbitraje</label>
								<input type="number" class="form-control" id="sueldo5" name="sueldo5" step="any" data-minlength="2" data-error="Especifique este valor" value="<?=@$arbitros_sueldos->sueldo5?>" required>
								<div class="help-block with-errors"></div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for="sueldo3">Descuento por errores</label>
								<input type="number" class="form-control" id="descuento" name="descuento" step="any" data-minlength="2" data-error="Especifique este valor" value="<?=@$arbitros_sueldos->descuento?>" required>
								<div class="help-block with-errors"></div>
							</div>
						</div>
					</div>
					<input type="hidden" id="formId" name="id" value="<?=@(int)$ID_Torneo?>" />
					<input type="hidden" name="referer" value="<?=current_url()?>" />
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
						<button type="submit" class="btn btn-primary formNuevoSubmit"><i class="fa fa-save"></i> Registrar torneo</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
