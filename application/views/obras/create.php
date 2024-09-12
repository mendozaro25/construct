<form name="frmModal"  autocomplete="off" action="javascript:;">
	<input name="id" id="id" value="<?= $id ?>" type="hidden"  />
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label class="form-label">Nombre Obra <span class="text-red">*</span></label>
				<input autofocus required class="form-control" name="nombre" id="nombre" placeholder="Nombre Obra" value="<?= $nombre ?>">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label class="form-label">Ubicación </label>
				<input class="form-control" name="ubicacion" id="ubicacion" placeholder="Ubicacion" value="<?= $ubicacion ?>">
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
				<label class="form-label">Fecha Inicio <span class="text-red">*</span></label>
				<input required type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?= $fecha_inicio ?? date('Y-m-d') ?>"/>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label class="form-label">Fecha Final <span class="text-red">*</span></label>
				<input required type="date" class="form-control" name="fecha_final" id="fecha_final" value="<?= $fecha_final ?>"/>
			</div>
		</div>
	</div>
	<div class="row">
		<?php if ($id > 0) {?>
		<div class="col-md-6">
			<div class="form-group">
				<label class="form-label">Costo de la Obra </label>
				<input readonly class="form-control" name="costo_obra" id="costo_obra" value="<?= $costo_obra ?>">
			</div>
		</div>
		<?php } ?>
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