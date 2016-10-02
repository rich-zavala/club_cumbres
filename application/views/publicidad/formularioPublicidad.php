<div class="modal fade" id="modalFormulario">
  <div class="modal-dialog">
		<form action="<?=base()?>publicidad/submit<?=suffix()?>" method="post" enctype="multipart/form-data" id="formArchivo" class="modal-content">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
				<div class="form-group">
					<input type="file" name="userfile" id="formArchivo" multiple="multiple" data-minlength="4" data-error="Especifique un archivo" value="" required  />
					<div class="help-block with-errors"></div>
				</div>
                <div class="form-group">
					<label for="formTipo">Tipo:</label>
                    <select class="form-control" id="formArchivo" name="tipo" >
                    <option value="" data-error="Especifique un tipo de archivo" required>[Opciones]</option>
                    
                    <?php
                    foreach($reg as $i)
		{
                    ?>
                     <option value="<?=$i->tipo?>" data-error="Especifique un tipo de archivo" required><?=$i->nombreAnuncio?></option>
                    
                    
                    <?php
		}//Fin de foreach
					?>
                    </select>
                    
					
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