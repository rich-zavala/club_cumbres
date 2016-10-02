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
					<label for="formNombre">Nombre</label>
					<input type="text" class="form-control" id="formNombre" name="nombre" data-minlength="4" data-error="Especifique el Nombre de Acceso" value="" required>
					<div class="help-block with-errors"></div>
				</div>
                <div class="form-group">
					<label for="formUsuario">Usuario</label>
					<input type="text" class="form-control" id="formUsuario" name="usuario" data-minlength="4" data-error="Especifique el nombre de usuario de acceso" value="" required>
					<div class="help-block with-errors"></div>
				</div>
                <div class="form-group">
					<label for="formPass">Contrase&ntilde;a</label>
					<input type="password" class="form-control" id="formPass" name="pass" data-minlength="4" data-error="Especifique la contrase&ntilde;a del usuario" value="" required>
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