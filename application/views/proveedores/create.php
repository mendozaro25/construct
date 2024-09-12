<form id="frmModal" name="frmModal" autocomplete="off" action="javascript:;">
	<input name="id" id="id" value="<?= $id ?>" type="hidden"  />
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label class="form-label" >Tipo Documento <span class="text-red">*</span></label>
				<?php
				form_dropdown_array("tipo_documento",
					getConstante(ID_CONST_REG_TDOC, TRUE),
					["class" => "form-control"], $tipo_documento, FALSE, "codigo", "valor");
				?>
			</div>           
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label class="form-label">Num. Documento </label>
				<input autofocus maxlength="11" class="form-control" name="num_documento" id="num_documento" placeholder="00000000000" value="<?= $num_documento ?>">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label class="form-label">Nombre <span class="text-red">*</span></label>
				<input autofocus required class="form-control" name="nombre" id="nombre" placeholder="Nombre" value="<?= $nombre ?>">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label class="form-label" >Tipo Proveedor <span class="text-red">*</span></label>
				<?php
				form_dropdown_array("tipo_proveedor",
					getConstante(ID_CONST_REG_TPROVEEDOR, TRUE),
					["class" => "form-control"], $tipo_proveedor, FALSE, "codigo", "valor");
				?>
			</div>           
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				<label class="form-label">Direccion </label>
				<input class="form-control" name="direccion" id="direccion" placeholder="Direccion" value="<?= $direccion ?>">
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label class="form-label">Telefono </label>
				<input class="form-control" maxlength="9" name="telefono" id="telefono" placeholder="Telefono" value="<?= $telefono ?>">
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label class="form-label">Correo </label>
				<input class="form-control" type="email" name="correo" id="correo" placeholder="Correo" value="<?= $correo ?>">
			</div>
		</div>
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

<script type="text/javascript">
	var tipoDocumento = document.getElementById('tipo_documento');
	var numDocumento = document.getElementById('num_documento');
	tipoDocumento.addEventListener('change', function() {
		var tipoDocumentoValue = tipoDocumento.value;
		// console.log(tipoDocumentoValue);
		if (tipoDocumentoValue === "S/D") {
			numDocumento.disabled = true;
			numDocumento.value = "";
		} else {
			numDocumento.disabled = false;
		}
	});
</script>