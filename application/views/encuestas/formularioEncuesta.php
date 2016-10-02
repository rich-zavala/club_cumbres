<div class="modal fade" id="modalFormulario">
  <div class="modal-dialog">
		<form action="<?=base()?>usuario/submit<?=suffix()?>" method="post" id="usuarioForm" class="modal-content">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
				<div class="form-group">
					<label for="formPregunta">Pregunta</label>
					<input type="text" class="form-control" id="formPregunta" name="pregunta" data-minlength="4" data-error="Especifique la pregunta de la nueva encuesta" value="" required>
					<div class="help-block with-errors"></div>
				</div>
                <div class="form-group">
					<label for="formResp1">Respuesta uno</label>
					<input type="text" class="form-control" id="formResp1" name="resp1" data-minlength="4" data-error="Especifique la primera respuesta" value="" required>
					<div class="help-block with-errors"></div>
				</div>
                <div class="form-group">
					<label for="formResp2">Respuesta dos</label>
					<input type="text" class="form-control" id="formResp2" name="resp2" data-minlength="4" data-error="Especifique la segunda respuesta" value="" required>
					<div class="help-block with-errors"></div>
				</div>
                 <div class="form-group">
					<label for="formResp3">Respuesta tres</label>
					<input type="text" class="form-control" id="formResp3" name="resp3" data-minlength="4" data-error="Especifique la tercera respuesta" value="" >
					<div class="help-block with-errors"></div>
				</div>
                <div class="form-group">
					<label for="formResp4">Respuesta cuatro</label>
					<input type="text" class="form-control" id="formResp3" name="resp3" data-minlength="4" data-error="Especifique la cuarta respuesta" value="" >
					<div class="help-block with-errors"></div>
				</div>
				<input type="hidden" id="formId" name="id" value="0" />
                <input type="hidden" name="referer" value="<?=current_url()?>" />
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary" id="formNuevoSubmit">Guardar</button>
      </div>
    </form>
  </div>
</div>