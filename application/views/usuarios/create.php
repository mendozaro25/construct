<form name="frmModal"  autocomplete="off" action="javascript:;">
	<input name="id" id="id" value="<?= $id ?>" type="hidden"  />
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label class="form-label">Nombres <span class="text-red">*</span></label>
				<input autofocus required class="form-control" name="name" id="name" placeholder="Nombres" value="<?= $name ?>">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label class="form-label">Apellidos <span class="text-red">*</span></label>
				<input required class="form-control" name="lastname" id="lastname" placeholder="Apellidos" value="<?= $lastname ?>">
			</div>
		</div>
	</div><div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label class="form-label">Usuario <span class="text-red">*</span></label>
				<input required class="form-control" name="username" id="username" placeholder="Usuario" value="<?= $username ?>">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label class="form-label">Clave <span class="text-red">*</span></label>
				<input required disabled type="password" class="form-control input-password" name="password" id="password" placeholder="Clave" value="<?= $password ?>"/>
				<a href="javascript:void(0);" onclick="enableOrDisableInput();" class="btn btn-sm" ><i class="fa fa-undo"></i> 
					<?php if($id > 0) { ?> Cambiar clave <?php } else { echo 'Crear clave'; } ?>
				</a>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				<label class="form-label">Email </label>
				<input type="email" class="form-control" name="email" id="email" placeholder="Email" value="<?= $email ?>">
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label class="form-label">DNI </label>
				<input maxlength="8" class="form-control" name="dni" id="dni" placeholder="DNI" value="<?= $dni ?>"/>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label class="form-label">Telefono </label>
				<input maxlength="9" class="form-control" name="phone" id="phone" placeholder="Telefono" value="<?= $phone ?>"/>
			</div>
		</div>
	</div>
	<div  class="form-group">
		<label class="form-label" >Estado <span class="text-red">*</span></label>
		<?php
		form_dropdown_array("status",
			getConstante(ID_CONST_REG_STATUS, TRUE),
			["class" => "form-control"], $status, FALSE, "codigo", "valor");
		?>
	</div>  
</form>

<script type="text/javascript">

	function enableOrDisableInput() {
    let input = document.getElementById("password");
        if (input.disabled === true) {
            input.disabled = false;
            input.value = "";
            input.focus();
        } else {
            input.disabled = true;
            input.value = this.value;
        }
    }

</script>