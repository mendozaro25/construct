<?php require_once  __DIR__.'/../include/header.php'; ?>
<?php require_once  __DIR__.'/../include/sidebar.php'; ?>
<!-- page content -->

    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0"><?= $page_title ?></h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><i class="fe fe-layers mr-2 fs-14"></i>Inicio</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="<?= base_url() ?>compras/compra"><?= $page_title ?></a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="<?= base_url() ?>compras/detalle_compra?num=<?= $records[0]["id"] ?? $rs[0]["obra_id"] ?>">Detalle Compra</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="<?= base_url() ?>compras/detalle_compra_create?shopID=<?= $rs[0]["compra_id"] ?>&siteID=<?= $records[0]["id"] ?? $rs[0]["obra_id"]?>">Agregar Compra</a></li>
            </ol>
        </div>
        <div class="page-rightheader">
            <div class="btn btn-list">
                <a type="button" data-toggle="tooltip" data-placement="left" class="btn btn-primary" title="Volver" href="<?= base_url() ?>compras/detalle_compra?num=<?= $records[0]["id"] ?? $rs[0]["obra_id"] ?>">
                    <i class="fe fe-arrow-left mr-1" ></i>Volver
                </a>
            </div>
        </div>
    </div>
    <!--End Page header-->

    <!-- Row -->
    <form name="frmCompra" id="frmCompra" action="<?= base_url() ?>/compras/detalle_compra_save" method="post" onsubmit="return confirm('¿Estás seguro de guardar la compra?')">
        <input name="obra_id" id="obra_id" value="<?= $records[0]["id"] ?? $rs[0]["obra_id"] ?>" type="hidden" />
        <input name="compra_id" id="compra_id" value="<?= $rs[0]["compra_id"] ?>" type="hidden" />
        <input name="producto_count" id="producto_count" value="<?= $pcount["producto_count"] ?? 0 ?>" type="hidden" >
        <div class="row">
            <div class="col-lg-8 col-sm-12">
                <div class="card mb-6">
                    <div class="card-body">
                        <h3 class="page-title mb-0">Productos / Servicios
                            <div class="btn btn-list">
                                <button style="padding: 0px 0px 0px 3px;" type="button" data-toggle="tooltip" data-placement="right" class="btn btn-danger" title="Agregar Producto" onclick="addProducto()">
                                    <i class="fe fe-plus mr-1"></i>
                                </button>
                            </div>
                        </h3>
                        <hr class="my-1" />
                        <!-- Productos -->
                        <?php if(!empty($rs[0]["compra_id"])): ?>
                            <?php foreach ($data_prod_dcomp as $row_pd) : ?>
                                <div class="row" style="margin-bottom: 1em;">
                                    <input type="hidden" class="form-control producto-id" name="items[detalle_compra_id][]" value="<?= $row_pd["detalle_compra_id"] ?>">
                                    <div class="col-md-4">
                                        <label class="form-label">Producto <span class="text-red">*</span> </label>
                                        <select class="form-control select-producto" name="items[producto_id][]" readonly>
                                            <option value="<?= $row_pd["producto_id"] ?>"><?= $row_pd["nombre"].' ('.$row_pd["categoria"].')' ?></option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label" >UM </label>
                                        <input type="text" class="form-control producto-undMedida" name="items[unidad_medida][]" value="<?= $row_pd["simbolo"] ?>" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label" >Cant </label>
                                        <div class="input-group">
                                            <!-- span class="input-group-btn">
                                                <button type="button" class="btn btn-light border-0 br-0 minus">
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                            </span -->
                                            <input type="text" class="form-control text-center qty" name="items[cantidad][]" value="<?= $row_pd["cantidad"] ?>" readonly>
                                            <!-- span class="input-group-btn">
                                                <button type="button" class="btn btn-light border-0 br-0 add" >
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </span -->
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label" >Prec. </label>
                                        <input type="text" class="form-control text-center producto-precUnitario" name="items[precio_unitario][]" value="<?= $row_pd["precio_unitario"] ?>" readonly>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label" >SubTot </label>
                                        <input type="text" class="form-control text-center subtotal" name="items[subtotal][]" value="<?= number_format($row_pd["subtotal"],2) ?>" readonly>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label" >IGV </label>
                                        <input type="text" class="form-control text-center igv" name="items[igv][]" value="<?= number_format($row_pd["igv"],2) ?>" readonly>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label" >Total </label>
                                        <input type="text" class="form-control text-center total" name="items[total][]" value="<?= number_format($row_pd["total"],2) ?>" readonly>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label" >&nbsp;</label>
                                        <a data-toggle="tooltip" title="Eliminar" class="btn btn-icon btn-danger" onclick="deleteProducto(this)"><i class="fe fe-trash"></i></a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <!-- Render -->
                        <div id="add_producto"></div>
                        <!-- Resumen -->
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="form-group row">
                                    <label for="subtotal" style="font-size: 15px;" class="col-sm-8 col-form-label text-right font-weight-bold">Sub Total</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control text-center" name="subtotal" id="subtotal" value="<?= number_format($rs[0]["t_subtotal"] ?? 0, 2) ?>" readonly>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="form-group row">
                                    <label for="impuestos" style="font-size: 15px;" class="col-sm-8 col-form-label text-right font-weight-bold h5">Impuestos</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control text-center" name="impuestos" id="impuestos" value="<?= number_format($rs[0]["t_impuestos"] ?? 0, 2) ?>" readonly>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="form-group row">
                                    <label for="importe_total" style="font-size: 15px;" class="col-sm-8 col-form-label text-right font-weight-bold h5">Importe total</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control text-center" name="importe_total" id="importe_total" value="<?= number_format($rs[0]["t_importe_total"] ?? 0, 2) ?>" readonly>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-12">
                <div class="card mb-6">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div  class="form-group">
                                    <label class="form-label" >F. Compra </label>
                                    <input type="text" class="form-control" value="<?= $rs[0]["fecha_compra"] ?? date('Y-m-d') ?>"/>
                                </div>              
                            </div>
                            <div class="col-md-6">
                                <div  class="form-group">
                                    <label class="form-label" >Comprador </label>
                                    <input type="text" class="form-control" value="<?= $rs[0]["comprador"] ?? $this->session->userdata("fullName") ?>">
                                </div>               
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div  class="form-group">
                                    <label class="form-label" >Comp. <span class="text-red">*</span></label>
                                    <?php
                                    form_dropdown_array("tipo_comprobante",
                                        getConstante(ID_CONST_REG_TCOMPRAB, TRUE),
                                        ["class" => "form-control"], $rs[0]["tipo_comprobante"], FALSE, "codigo", "valor");
                                    ?>
                                </div>              
                            </div>
                            <div class="col-md-4">
                                <div  class="form-group">
                                    <label class="form-label" >Ser/Num </label>
                                    <input type="text" class="form-control" name="serie_numero" id="serie_numero" value="<?= $rs[0]["serie_numero"] ?>">
                                </div>               
                            </div>
                            <div class="col-md-4">
                                <div  class="form-group">
                                    <label class="form-label" >F. Comprobante </label>
                                    <input type="date" class="form-control" name="fecha_comprobante" id="fecha_comprobante" value="<?= $rs[0]["fecha_comprobante"] ?>"/>
                                </div>
                            </div>  
                        </div>     
                        <div class="row">
                            <div class="col-md-6">                 
                                <div  class="form-group">
                                    <label class="form-label" >Rubro <span class="text-red">*</span></label>
                                    <?php
                                    form_dropdown_array("tipo_rubro",
                                        getConstante(ID_CONST_REG_TRUBRO, TRUE),
                                        ["class" => "form-control"], $rs[0]["tipo_rubro"], FALSE, "codigo", "valor");
                                    ?>
                                </div>            
                            </div>
                            <div class="col-md-6">
                                <div  class="form-group">
                                    <label class="form-label" >Estado <span class="text-red">*</span></label>
                                    <?php
                                    form_dropdown_array("status",
                                        getConstante(ID_CONST_REG_STATUS, TRUE),
                                        ["class" => "form-control"], $rs[0]["estado_compra"], FALSE, "codigo", "valor");
                                    ?>
                                </div>               
                            </div>
                        </div>    
                    </div>
                </div>
                <div class="card mb-6">
                    <div class="card-body">       
                        <div class="col-md-12">           
                            <div class="form-group">
                                <label class="form-label">Proveedor <span class="text-red">*</span></label>
                                <?php
                                form_dropdown_array("proveedor_id",
                                    $proveedores,
                                    "class='form-control select2-show-search'",
                                    $rs[0]["proveedor_id"], OPTION_DEFAULT_TEXT, "id", "nombre");
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-danger">Guardar</button>
            </div>
        </div>
    </form>
    <!-- End Row -->

<!-- /page content -->
<?php require_once  __DIR__.'/../include/footer.php'; ?>

<script type="text/javascript">
    var productCount = <?= $pcount["producto_count"] ?? 0 ?>;
    var selectedProducts = [];

    function addProducto() {
        productCount++;
        $('#producto_count').val(productCount);

        var template = $('#productTemplate').html();
        var rendered = Mustache.render(template, {productos: <?= json_encode($productos) ?> });
        $('#add_producto').append(rendered);

        // Inicializar Select2 para el campo de nombre del producto
        var selectProducto = $('.select-producto').last();
        selectProducto.select2({
            language: {
            noResults: function () {
                return "No se encontraron resultados";
            },
            searching: function () {
                return "Buscando...";
            }
            },
            data: <?= json_encode($productos) ?>,
            id: function (producto) { return producto.id; },
            text: function (producto) { return producto.text; }
        }).on('select2:select', function (e) {
            var selectedData = e.params.data;
            var selectedProductId = selectedData.id;

            // Verificar si el producto ya ha sido seleccionado en filas anteriores
            var isDuplicate = false;
            $('.select-producto').each(function(index) {
                if (index !== productCount - 1 && $(this).val() === selectedProductId) {
                    isDuplicate = true;
                    return false; // Romper el bucle cuando se encuentre un duplicado
                }
            });

            if (isDuplicate) {
                alert('El producto ya ha sido seleccionado en una fila anterior.');
                $(this).val(null).trigger('change');
                $(this).closest('.row').remove(); // Eliminar la fila duplicada
                productCount--;
                $('#producto_count').val(productCount);
                calcularSubTotal();
                return;
            }

            selectedProducts.push(selectedProductId);

            $(this).closest('.row').find('.producto-undMedida').val(selectedData.unidad_medida);
            $(this).closest('.row').find('.producto-precUnitario').val(selectedData.precio_unitario);

            calcularSubTotal(productCount - 1);
        });

        calcularSubTotal();
    }

    function calcularSubTotal(index) {
        var cantidad = parseFloat($('.qty').eq(index).val());
        var precioUnitario = parseFloat($('.producto-precUnitario').eq(index).val());

        var subTotal = (cantidad * precioUnitario) / 1.18;
        var total = cantidad * precioUnitario;
        var impuestos = total - subTotal;

        $('.igv').eq(index).val(impuestos.toFixed(2));
        $('.subtotal').eq(index).val(subTotal.toFixed(2));
        $('.total').eq(index).val(total.toFixed(2));

        var t_subTotal = 0;
        var impuestosTotal = 0;
        var importeTotal = 0;

        for (var i = 0; i < productCount; i++) {
            var rowIndex = i + 1;
            var cantidadProducto = parseFloat($('input[name="items[cantidad][]"]').eq(i).val());
            var precioUnitarioProducto = parseFloat($('input[name="items[precio_unitario][]"]').eq(i).val());

            var productoSubTotal = (cantidadProducto * precioUnitarioProducto) / 1.18;
            var productoImporteTotal = cantidadProducto * precioUnitarioProducto;
            var productoImpuestos = productoImporteTotal - productoSubTotal;

            $('input[name="items[subtotal][]"]').eq(i).val(productoSubTotal.toFixed(2));
            $('input[name="items[igv][]"]').eq(i).val(productoImpuestos.toFixed(2));
            $('input[name="items[total][]"]').eq(i).val(productoImporteTotal.toFixed(2));

            t_subTotal += productoSubTotal;
            impuestosTotal += productoImpuestos;
            importeTotal += productoImporteTotal;
        }

        $('#subtotal').val(t_subTotal.toFixed(2));
        $('#impuestos').val(impuestosTotal.toFixed(2));
        $('#importe_total').val(importeTotal.toFixed(2));
    }

    function deleteProducto(button) {
        var row = $(button).closest('.row');
        var productId = row.find('.producto-id').val();

        var index = $('.row').index(row);
        row.remove();

        productCount--;
        $('#producto_count').val(productCount);

        if (index === 0 && productCount > 0) {
            calcularSubTotal(0); // Recalcular los totales para la nueva primera fila
        } else if (index > 0) {
            calcularSubTotal(index - 1); // Recalcular los totales para la fila anterior a la eliminada
        } else if (productCount === 0) {
            // Si se eliminó la única fila de productos, reinicia los totales a cero
            $('.subtotal').val('0.00');
            $('.igv').val('0.00');
            $('.total').val('0.00');
            $('#subtotal').val('0.00');
            $('#impuestos').val('0.00');
            $('#importe_total').val('0.00');
        }
    }

    $(document).ready(function () {
        <?php if(empty($rs[0]["compra_id"])): ?>
            addProducto();
        <?php endif; ?>
    });

    $(document).on('click', '.add', function () {
        var inputQty = $(this).closest('.input-group').find('.qty');
        var currentQty = parseInt(inputQty.val());
        inputQty.val(currentQty + 1);

        var rowIndex = $(this).closest('.row').index('.row');
        calcularSubTotal(rowIndex);
    });

    $(document).on('click', '.minus', function () {
        var inputQty = $(this).closest('.input-group').find('.qty');
        var currentQty = parseInt(inputQty.val());
        if (currentQty > 1) {
            inputQty.val(currentQty - 1);

            var rowIndex = $(this).closest('.row').index('.row');
            calcularSubTotal(rowIndex);
        }
    });

    $(document).on('input', '.qty', function () {
        var rowIndex = $(this).closest('.row').index('.row');
        calcularSubTotal(rowIndex);
    });

    // CAMPO COMPROBANTE

    var tipoComprobante = document.getElementById("tipo_comprobante");
    var serNumInput = document.getElementById("serie_numero");
    var fechaCompInput = document.getElementById("fecha_comprobante");
    var fechaCompValue = fechaCompInput.value;

    tipoComprobante.addEventListener("change", function () {
        var selectedValue = this.value;

        if (selectedValue === "S/N") {
            serNumInput.disabled = true;
            fechaCompInput.disabled = true;
            fechaCompInput.value = "";
        } else {
            serNumInput.disabled = false;
            fechaCompInput.disabled = false;
            fechaCompInput.value = fechaCompValue;
        }
    });
</script>

<style>
	.select2-selection__rendered {
		color: #9cc3b4 !important;
	}
</style>