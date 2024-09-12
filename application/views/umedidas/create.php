<form id="frmModal" name="frmModal" autocomplete="off" action="javascript:;">
	<input name="id" id="id" value="<?= $id ?>" type="hidden"  />
	<div class="form-group">
		<label class="form-label">Simbolo <span class="text-red">*</span></label>
		<input autofocus required class="form-control" name="simbolo" id="simbolo" placeholder="Simbolo" value="<?= $simbolo ?>">
	</div>
	<div class="form-group">
		<label class="form-label">Nombre <span class="text-red">*</span></label>
		<input required class="form-control" name="nombre" id="nombre" placeholder="Nombre" value="<?= $nombre ?>">
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
