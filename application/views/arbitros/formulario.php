<div class="modal fade" id="modalFormulario">
  <div class="modal-dialog">
		<form action="<?=base()?>arbitros/submit<?=suffix()?>" method="post" id="arbitroForm" class="modal-content">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="formNombre">Nombre</label>
						<input type="text" class="form-control" id="formNombre" name="nombre" data-minlength="4" data-error="Especifique el nombre del árbitro" value="<?=@$nombre?>" required>
						<div class="help-block with-errors"></div>
					</div>
					<div class="form-group">
						<label for="formTelefono">Teléfono</label>
						<input type="text" class="form-control" id="formTelefono" name="telefono" value="<?=@$telefono?>">
					</div>
					<input type="hidden" id="formId" name="id" />
					<input type="hidden" name="referer" value="<?=current_url()?>" />
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
						<button type="submit" class="btn btn-primary formNuevoSubmit"><i class="fa fa-save"></i> Registrar árbitro</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>