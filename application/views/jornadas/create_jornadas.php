<form name="frmModal"  autocomplete="off" action="javascript:;">
	<input name="jornada_id" id="jornada_id" value="<?= $rs[0]["jornada_id"] ?>" type="hidden" >
	<input name="obra_id" id="obra_id" value="<?= $rs_[0]["id"] ?>" type="hidden"  />
	<input name="personal_count" id="personal_count" value="<?= $pcount["personal_count"] ?? 0 ?>" type="hidden" >
	<div class="row">
		<div class="col-md-6">
			<div  class="form-group">
				<label class="form-label" >Fecha Inicio </label>
		        <input readonly type="text" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?= $fecha_inicio_actual[0]["fecha_inicio_actual"] ?? $rs_[0]["fecha_inicio"] ?>">
			</div>               
		</div>
		<div class="col-md-6">
			<div  class="form-group">
				<label class="form-label" >Fecha Final <span class="text-red">*</span></label>
				<?php if(!empty($rs[0]["jornada_id"])) { ?>
		        <input readonly class="form-control" name="fecha_final" id="fecha_final" value="<?= $rs[0]["fecha_final"] ?>">
				<?php } else { ?>
					<input required type="date" class="form-control" name="fecha_final" id="fecha_final">
				<?php } ?>
			</div>               
		</div>
		<div class="col-md-12">
			<div  class="form-group">
				<label class="form-label" >Nombre Jornada <span class="text-red">*</span></label>
				<input required type="text" class="form-control" name="descripcion" id="descripcion" placeholder="Nombre Jornada" value="<?= $rs[0]["jornada_nombre"] ?>">
			</div>   
		</div>
	</div>
    <div class="row">
		<div class="col-md-12">
			<div class="card mb-4">   
				<div class="card-body">
					<h3 class="page-title mb-0">Personal
						<div class="btn btn-list">
							<button style="padding: 0px 0px 0px 3px;" 
									type="button" 
									data-toggle="tooltip" 
									data-placement="right" 
									class="btn btn-danger" 
									title="Agregar Personal" 
									onclick="addPersonal()">
								<i class="fe fe-plus mr-1"></i>
							</button>
						</div>
					</h3>
                    <hr class="my-1" />
					<?php if(!empty($rs[0]["jornada_id"])): ?>
						<?php foreach ($data_pers_djorn as $row_pd) : ?>
							<div class="row" style="margin-bottom: 1em;">
								<input type="hidden" class="form-control personal-id" name="items[detalle_jornada_id][]" value="<?= $row_pd["detalle_jornada_id"] ?>">
								<div class="col-md-5"> 
									<label class="form-label">Personal </label> 
									<select class="form-control select-personal" name="items[personal_id][]" readonly>
										<option value="<?= $row_pd["personal_id"] ?>"><?= $row_pd["nombre_personal"] ?></option>
									</select>
								</div>
								<div class="col-md-3">
									<label class="form-label" >Especialidad </label>
									<input type="text" class="form-control personal-especialidad" name="items[especialidad_name][]" value="<?= $row_pd["especialidad"] ?>" readonly>
								</div> 
								<div class="col-md-3"> 
									<label class="form-label" >Area </label>
									<input type="text" class="form-control personal-area" name="items[area_name][]" value="<?= $row_pd["area"] ?>" readonly> 
								</div>
								<div class="col-md-1"> 
									<label class="form-label" >Acc. </label>
									<a data-toggle="tooltip" title="Eliminar" class="btn btn-icon  btn-danger" onclick="deletePersonal(this)"><i class="fe fe-trash"></i></a>
								</div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
					<div id="add_personal"></div>
				</div>
			</div>   
		</div>
	</div>
</form>

<script type="text/javascript">

	var personalCount = <?= $pcount["personal_count"] ?? 0 ?>;
	var selectedPersonals = [];

    function addPersonal() {
		personalCount++;
        $('#personal_count').val(personalCount);

        var template = $('#personTemplate').html();
        var rendered = Mustache.render(template, {personales: <?= json_encode($personales) ?> });
        $('#add_personal').append(rendered);

        // Inicializar Select2 para el campo de nombre del personal
        var selectpersonal = $('.select-personal').last();
        selectpersonal.select2({
            language: {
            noResults: function () {
                return "No se encontraron resultados";
            },
            searching: function () {
                return "Buscando...";
            }
            },
            data: <?= json_encode($personales) ?>,
            id: function (personal) { return personal.id; },
            text: function (personal) { return personal.text; }
        }).on('select2:select', function (e) {
            var selectedData = e.params.data;
            var selectPersonID = selectedData.id;

            selectedPersonals.push(selectPersonID);

            $(this).closest('.row').find('.personal-especialidad').val(selectedData.especialidad);
            $(this).closest('.row').find('.personal-area').val(selectedData.area);
        });
	}


	function deletePersonal(button) {
		// Obtener el elemento <div class="row"> padre del botón "Eliminar"
		var row = $(button).closest('.row');

		// Eliminar el elemento <div class="row"> de la lista
		row.remove();

		personalCount--;
		$('#personal_count').val(personalCount);
	}

	$(document).ready(function () {
        <?php if(empty($rs[0]["jornada_id"])): ?>
            addPersonal();
        <?php endif; ?>
    });

</script>

<style>
	/*Style-Juanciño*/
	.select2-container {
		width: 287px !important;
	}
	.select2-selection__rendered {
		color: #9cc3b4 !important;
	}
</style>