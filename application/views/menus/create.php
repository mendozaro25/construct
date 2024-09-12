<form id="frmModal" name="frmModal" autocomplete="off" action="javascript:;">
	<input name="id" id="id" value="<?= $id ?>" type="hidden"  />
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label class="form-label">Nombre Menú <span class="text-red">*</span></label>
				<input autofocus required class="form-control conf-menu" name="display_name" id="display_name" placeholder="Nombre Menú" value="<?= $display_name ?>">
			</div>        
		</div>
		<div class="col-md-6">
			<div  class="form-group">
				<label class="form-label">Pariente <span class="text-red">*</span></label>
				<?php
				form_dropdown_array("parent_id",
					$parientes,
					" class='form-control select2-show-search'"
					, $parent_id, OPTION_DEFAULT_TEXT, "id", "display_name");
				?>
			</div>            
		</div>
	</div><div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label class="form-label">Controlador </label>
				<input class="form-control conf-menu" name="controller" id="controller" placeholder="Controlador" value="<?= $controller ?>">
			</div>        
		</div>
		<div class="col-md-6">
			<div  class="form-group">
				<label class="form-label">Método </label>
				<input class="form-control conf-menu" name="main_method" id="main_method" placeholder="Método" value="<?= $main_method ?>">
			</div>            
		</div>
	</div></div><div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label class="form-label">Icono </label>
				<input class="form-control conf-menu" name="icon" id="icon" placeholder="Icono" value="<?= $icon ?>">
			</div>        
		</div>
		<div class="col-md-6">
			<div  class="form-group">
				<label class="form-label">Orden <span class="text-red">*</span></label>
				<input required type="number" class="form-control" name="order" id="order" placeholder="Orden" value="<?= $order ?>">
			</div>            
		</div>
	</div>
	<div class="form-group">
		<label class="form-label">Descripción </label>
		<?php if($id > 0){ ?><input type="text" class="form-control conf-menu" name="descripcion" id="descripcion" placeholder="Descripcion" value="<?= $descripcion ?>"> 
		<?php }else{ ?><textarea class="form-control conf-menu mb-4" rows="2" name="descripcion" id="descripcion" placeholder="Descripcion"></textarea><?php } ?>
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
