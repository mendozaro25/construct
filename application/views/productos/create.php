<form id="frmModal" name="frmModal" autocomplete="off" action="javascript:;">
	<input name="id" id="id" value="<?= $id ?>" type="hidden"  />
	<div class="form-group">
		<label class="form-label">Nombre <span class="text-red">*</span></label>
		<input autofocus required class="form-control" name="nombre" id="nombre" placeholder="Nombre" value="<?= $nombre ?>">
	</div>
	<div class="row">
		<div class="col-md-6">
			<div  class="form-group">
				<label class="form-label">Und. Medida <span class="text-red">*</span></label>
				<?php
				form_dropdown_array("unidad_medida_id",
					$undmedidas,
					" class='form-control select2-show-search'"
					, $unidad_medida_id, OPTION_DEFAULT_TEXT, "id", "nombre");
				?>
			</div>            
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label class="form-label">Precio Unitario <span class="text-red">*</span></label>
				<input required type="number" class="form-control" name="precio_unitario" id="precio_unitario" placeholder="0.00" value="<?= $precio_unitario ?>">
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="form-label">Descripción </label>
		<?php if($id > 0){ ?><input type="text" class="form-control" name="descripcion" id="descripcion" placeholder="Descripcion" value="<?= $descripcion ?>"> 
		<?php }else{ ?><textarea class="form-control mb-4" rows="2" name="descripcion" id="descripcion" placeholder="Descripcion"></textarea><?php } ?>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label class="form-label">Categoria </label>
				<input class="form-control" name="categoria" id="categoria" placeholder="Categoria" value="<?= $categoria ?>">
			</div> 
		</div>
		<div class="col-md-6">
			<div class="form-group">
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

		var noArray = <?=  json_encode($noArray) ?> || [];
		$('#categoria').autocomplete({
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
