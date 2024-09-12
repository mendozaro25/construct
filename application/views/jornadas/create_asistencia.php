<?php
	$detalle_jornada_id = $asistencias[0]["detalle_jornada_id"];
	$personal_id = gw("detalle_jornada", ["id" => $detalle_jornada_id])->row()->personal_id;
	$obra_id = gw("detalle_jornada", ["id" => $detalle_jornada_id])->row()->obra_id;
	$sueldo_horas_extras_jornada = gw("detalle_jornada", ["id" => $detalle_jornada_id])->row()->sueldo_horas_extras_jornada;
	$sueldo_personal_semana_jornada = gw("detalle_jornada", ["id" => $detalle_jornada_id])->row()->sueldo_personal_semana_jornada;
	$sueldo_total_asistencias_jornada = gw("detalle_jornada", ["id" => $detalle_jornada_id])->row()->sueldo_total_asistencias_jornada;
	$sueldo_total_horas_extras_jornada = gw("detalle_jornada", ["id" => $detalle_jornada_id])->row()->sueldo_total_horas_extras_jornada;
?>
<form name="frmModal"  autocomplete="off" action="javascript:;">
	<input class="form-control" name="detalle_jornada_id" value="<?= $detalle_jornada_id ?>" type="hidden">
	<input class="form-control" name="sueldo_horas_extras_jornada" value="<?= $sueldo_horas_extras_jornada ?? .0?>" type="hidden">
	<input class="form-control" name="sueldo_personal_semana_jornada" value="<?= $sueldo_personal_semana_jornada ?? .0?>" type="hidden">
	<input class="form-control" name="sueldo_total_asistencias_jornada" value="<?= $sueldo_total_asistencias_jornada ?? .0?>" type="hidden">
	<input class="form-control" name="sueldo_total_horas_extras_jornada" value="<?= $sueldo_total_horas_extras_jornada ?? .0?>" type="hidden">
	<input class="form-control" name="obra_id" value="<?= $obra_id ?>" type="hidden">
	<input class="form-control" name="costo_obra" value="<?= gw("obra", ["id" => $obra_id])->row()->costo_obra ?>" type="hidden">
	<!-- Row -->
    <div class="row">
        <div class="col-lg-8 col-sm-12">
            <div class="card mb-4">   
                <div class="card-body">
                    <h6 class="page-title mb-0">Registro Asistencia</h4>
                    <hr class="my-0" /><br>
					<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>Día</th>
								<th>¿Asistió?</th>
								<th>Horas Extras</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($asistencias as $index => $asistencia) : ?>
								<tr>
									<input class="form-control" name="id[]" value="<?= $asistencia['id'] ?>" type="hidden">
									<td>
										<input type="text" class="form-control" id="dia[]" name="dia[]" readonly value="<?= $asistencia['dia'] ?>">
									</td>
									<td>
										<div class="custom-control custom-checkbox" style="margin-left: 22px;">
											<input type="checkbox" class="custom-control-input asistencia-checkbox" id="estado[]" name="estado[<?= $index ?>]" value="<?= $asistencia['estado'] ?>" <?= $asistencia['estado'] == 1 ? 'checked' : '' ?>>
											<label class="custom-control-label"></label>
										</div>
									</td>
									<td>
										<input type="number" class="form-control horas-extras-input" id="horas_extras[]" name="horas_extras[<?= $index ?>]" value="<?= $asistencia['horas_extras'] ?>">
									</td>
								</tr>
							<?php endforeach; ?>
							<tr>
								<td><strong>TOTAL: </strong></td>
								<td> <input type="text" class="form-control" id="t_asistencia" name="t_asistencia" value="<?= $t_asistencia ?? 0 ?>" readonly></td>
								<td> <input type="text" class="form-control" id="t_horas_extras" name="t_horas_extras" value="<?= $t_horas_extras ?? 0 ?>" readonly></td>
							</tr>
						</tbody>
					</table>
                    </div>
                </div>
            </div> 
        </div>
        <div class="col-lg-4 col-sm-12">
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="page-title mb-0">Datos Personal</h4>
                    <hr class="my-0" /><br>
                    <div class="form-group">
                        <label class="form-label">Sueldo Personal: </label>
                        <input readonly class="form-control" name="sueldo_personal" id="sueldo_personal" 
						value="<?= gw("personal", ["id" => $personal_id])->row()->sueldo ?>"/>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sueldo Fijo: </label>
                        <input readonly class="form-control" name="sueldo_fijo" id="sueldo_fijo" value="<?= $sueldo_fijo ?? 0 ?>"/>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sueldo Horas Extra: </label>
                        <input readonly class="form-control" name="sueldo_horas_extras" id="sueldo_horas_extras" value="<?= $sueldo_horas_extras ?? 0 ?>"/>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sueldo Total: </label>
                        <input readonly class="form-control" name="sueldo_total" id="sueldo_total" value="<?= $sueldo_total ?? 0 ?>"/>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</form>

<script type="text/javascript">
	$(document).ready(function() {
		// Obtener todos los checkboxes de estado
		var checkboxes = $("input[id^='estado']");

		// Iterar sobre cada checkbox
		checkboxes.each(function() {
			// Obtener el estado actual del checkbox
			var estadoCheckbox = $(this);
			
			// Obtener el campo de horas extras asociado al checkbox
			var horasExtrasInput = estadoCheckbox
				.closest("tr")
				.find("input[id^='horas_extras']");
			
			// Desactivar el campo de horas extras inicialmente si el checkbox no está marcado
			if (!estadoCheckbox.is(":checked")) {
				horasExtrasInput.prop("disabled", true);
				horasExtrasInput.val(""); // Limpiar el valor del input
			}
			
			// Agregar un controlador de evento para el cambio del checkbox
			estadoCheckbox.on("change", function() {
				if (estadoCheckbox.is(":checked")) {
					horasExtrasInput.prop("disabled", false);
				} else {
					horasExtrasInput.prop("disabled", true);
					horasExtrasInput.val(""); // Limpiar el valor del input
				}
				
				// Calcular los sueldos cuando haya cambios en los checkboxes
				calcularSueldos();
			});
		});

		// Función para calcular los sueldos
		function calcularSueldos() {
		var totalAsistencias = parseInt($("#t_asistencia").val());
		var sueldoPersonal = parseFloat($("#sueldo_personal").val()).toFixed(2);
		var totalHorasExtras = 0;

		// Calcular el total de horas extras sumando los valores de los inputs
		$("input[id^='horas_extras']:enabled").each(function() {
			var horasExtras = parseFloat($(this).val());
			if (!isNaN(horasExtras)) {
			totalHorasExtras += horasExtras;
			}
		});

		// Calcular sueldo fijo
		var sueldoFijo = sueldoPersonal * totalAsistencias;

		// Calcular sueldo por horas extra
		var sueldoHorasExtra = sueldoPersonal * totalHorasExtras;

		// Calcular sueldo total
		var sueldoTotal = sueldoFijo + sueldoHorasExtra;

		// Actualizar los valores en los campos correspondientes
		$("#sueldo_fijo").val(sueldoFijo.toFixed(2));
		$("#sueldo_horas_extras").val(sueldoHorasExtra.toFixed(2));
		$("#sueldo_total").val(sueldoTotal.toFixed(2));
		$("#sueldo_personal").val(sueldoPersonal);

		// Actualizar el valor del checkbox al cambiar el estado
		var checkboxes = $("input[id^='estado']");
		checkboxes.on("change", function() {
			var estadoCheckbox = $(this);
			var estadoValue = estadoCheckbox.is(":checked") ? 1 : 0;
			estadoCheckbox.val(estadoValue);
		});
		}

		// Escuchar los cambios en los totales de asistencias y horas extras
		$("input[id^='horas_extras']").on("input", calcularSueldos);

		// Calcular los sueldos al cargar la página
		calcularSueldos();

	});

	
	// Función para actualizar el total de horas extras y checkboxes activos
	function actualizarTotales() {
		let totalHorasExtras = 0;
		let checkboxesActivos = 0;

		// Recorrer los checkboxes y sumar las horas extras
		document.querySelectorAll('.asistencia-checkbox').forEach(function(checkbox) {
		if (checkbox.checked) {
			checkboxesActivos++;
			let horasExtrasInput = checkbox.closest('tr').querySelector('.horas-extras-input');
			totalHorasExtras += parseInt(horasExtrasInput.value || 0);
		}
		});

		// Actualizar los valores en los inputs correspondientes
		document.getElementById('t_asistencia').value = checkboxesActivos;
		document.getElementById('t_horas_extras').value = totalHorasExtras;
	}

	// Escuchar los cambios en los checkboxes y horas extras
	document.querySelectorAll('.asistencia-checkbox, .horas-extras-input').forEach(function(element) {
		element.addEventListener('change', actualizarTotales);
	});

	// Actualizar los totales al cargar la página
	actualizarTotales();
</script>

<style>
	
</style>