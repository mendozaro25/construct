<form id="frmModal" name="frmModal" autocomplete="off" action="javascript:;">
	<input name="id" id="id" value="<?= $id ?>" type="hidden"  />
	<div class="form-group">
		<label class="form-label">Nombre Área <span class="text-red">*</span></label>
		<input autofocus required class="form-control" name="nombre" id="nombre" placeholder="Nombre Area" value="<?= $nombre ?>">
	</div>
	<div class="form-group">
		<label class="form-label">Descripción </label>
		<?php if($id > 0){ ?><input type="text" class="form-control" name="descripcion" id="descripcion" placeholder="Descripcion" value="<?= $descripcion ?>"> 
		<?php }else{ ?><textarea class="form-control mb-4" rows="2" name="descripcion" id="descripcion" placeholder="Descripcion"></textarea><?php } ?>
	</div>
	<div class="form-group">
		<label class="form-label" >Estado <span class="text-red">*</span></label>
		<?php
		form_dropdown_array("status",
			getConstante(ID_CONST_REG_STATUS, TRUE),
			["class" => "form-control"], $status, FALSE, "codigo", "valor");
		?>
	</div>
</form>
