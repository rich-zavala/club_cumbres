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
						<input type="text" class="form-control" id="formNombre" name="nombre" data-minlength="4" data-error="Especifique el nombre del torneo" value="<?=@$NomTorneo?>" required>
						<div class="help-block with-errors"></div>
					</div>
					<div class="form-group">
						<label for="formAgno">Año</label>
						<input type="number" class="form-control" id="formAgno" name="agno" data-minlength="4" data-error="Especifique el año del torneo" value="<?=@$YrTorn?>" required>
						<div class="help-block with-errors"></div>
					</div>
					<div class="form-group">
						<label for="formTipo">Tipo</label>
						<select class="form-control" id="formTipo" name="tipo" required>
							<option value="1" <?=selected(1, @(int)$TipoTorn)?>>Futbol Rapido</option>
							<option value="2" <?=selected(2, @(int)$TipoTorn)?>>Futbol soccer</option>
						</select>
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