<form name="frmModal"  autocomplete="off" action="javascript:;">
	<input name="id" id="id" value="<?= $id ?>" type="hidden"  />
	<div class="row">
		<div class="col-md-6">
			<div  class="form-group">
				<label class="form-label">Selec. Obra <span class="text-red">*</span></label>
				<?php
				form_dropdown_array("obra_id",
					$obras,
					" class='form-control select2-show-search'"
					, $obra_id, OPTION_DEFAULT_TEXT, "id", "nombre");
				?>
			</div>            
		</div>
		<div class="col-md-6">
			<div  class="form-group">
				<label class="form-label" >Estado <span class="text-red">*</span></label>
				<?php
				form_dropdown_array("status",
					getConstante(ID_CONST_REG_STATUS, TRUE),
					["class" => "form-control"], $status, FALSE, "codigo", "valor");
				?>
			</div>               
		</div>
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
</style>