<form id="frmModal" name="frmModal" autocomplete="off" action="javascript:;">
	<input name="id" id="id" value="<?= $id ?>" type="hidden"  />
	<div class="row">
		<div class="col-md-6">
			<div  class="form-group">
				<label class="form-label">Especialidad <span class="text-red">*</span></label>
				<?php
				form_dropdown_array("especialidad_id",
					$especialidades,
					" class='form-control select2-show-search'"
					, $especialidad_id, OPTION_DEFAULT_TEXT, "id", "nombre");
				?>
			</div>            
		</div>
		<div class="col-md-6">
			<div  class="form-group">
				<label class="form-label">Área <span class="text-red">*</span></label>
				<?php
				form_dropdown_array("area_id",
					$areas,
					" class='form-control select2-show-search'"
					, $area_id, OPTION_DEFAULT_TEXT, "id", "nombre");
				?>
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
				<label class="form-label">Apellidos <span class="text-red">*</span></label>
				<input required class="form-control" name="apellidos" id="apellidos" placeholder="Apellidos" value="<?= $apellidos ?>">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				<label class="form-label">DNI <span class="text-red">*</span></label>
				<input required maxlength="8" class="form-control" name="dni" id="dni" placeholder="DNI" value="<?= $dni ?>">
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label class="form-label">Dirección </label>
				<input class="form-control" name="direccion" id="direccion" placeholder="Direccion" value="<?= $direccion ?>">
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label class="form-label">Telefono </label>
				<input maxlength="9" class="form-control" name="telefono" id="telefono" placeholder="Telefono" value="<?= $telefono ?>">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				<label class="form-label">Sueldo / Por Día</label>
				<input class="form-control" name="sueldo" id="sueldo" placeholder="Sueldo / Por Día" value="<?= $sueldo ?>">
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label class="form-label">Banco </label>
				<input class="form-control" name="banco" id="banco" placeholder="Banco" value="<?= $banco ?>">
			</div> 
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label class="form-label">Nro. Cuenta </label>
				<input maxlength="16" class="form-control" name="num_cuenta" id="num_cuenta" placeholder="Nro. Cuenta" value="<?= $num_cuenta ?>">
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

	$(document).ready(function() {
		$('.select2-show-search').select2({
		language: {
			noResults: function() {
				return "No se encontraron resultados";        
			},
			searching: function() {
				return "Buscando...";
			}
		}
		});

		var noArray = <?=  json_encode($noArray) ?> || [];
		$('#banco').autocomplete({
			lookup:  noArray,
			minChars: 1,
			triggerSelectOnValidInput: false,
		});
    });

</script>

<style>
	/*Style-Juanciño*/
	.select2-container {
		width: 370px !important;
	}
	.select2-selection__rendered {
		color: #9cc3b4 !important;
	}
	.autocomplete-suggestions {
		background-color: #f4f4f4;
		color: #73879c;
		z-index: 9999999 !important;
		font-size: 15px;
		letter-spacing: 0.3px;
	}
	.autocomplete-suggestions .autocomplete-selected{
    	background-color: #d3d3d3;
	}
</style>
