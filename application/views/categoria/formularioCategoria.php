<div class="modal fade" id="modalFormulario">
  <div class="modal-dialog">
		<form action="<?=base()?>categoria/submit<?=suffix()?>" method="post" id="categoriaForm" class="modal-content">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
				<div class="form-group">
					<label for="formNombre">Nombre</label>
					<input type="text" class="form-control" id="formNombre" name="nombre" data-minlength="4" data-error="Especifique el nombre de la categor&iacute;a" value="" required>
					<div class="help-block with-errors"></div>
				</div>
				<input type="hidden" id="formId" name="id" value="0" />
                <input type="hidden" name="referer" value="<?=current_url()?>" />
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary" id="formNuevoSubmit">Actualizar</button>
      </div>
    </form>
  </div>
</div>