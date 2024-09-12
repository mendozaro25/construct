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
                <li class="breadcrumb-item active" aria-current="page"><a href="<?= base_url() ?>compras/detalle_compra?num=<?= $obra_id ?>">Detalle Compra</a></li>
            </ol>
        </div>
        <div class="page-rightheader">
            <div class="btn btn-list">
                <a type="button" data-toggle="tooltip" data-placement="left" class="btn btn-primary" title="Volver" href="<?= base_url() ?>compras/compra">
                    <i class="fe fe-arrow-left mr-1" ></i>Volver
                </a>
            </div>
        </div>
    </div>
    <!--End Page header-->

    <!-- Row -->
    <div class="row">
        <div class="col-lg-9 col-sm-12">
            <div class="page-rightheader" style="margin-top: 7px;">
                <div class="btn-list">
                    <input disabled style="width: 500px;" class="form-control" value="<?= $nombre?>">
                </div>
            </div> 
        </div>
        <div class="col-lg-3 col-sm-12">
            <div class="page-rightheader">
                <div class="btn btn-list">
                    <a type="button" data-toggle="tooltip" data-placement="left" class="btn btn-info" title="Resumen" 
                        href="<?= base_url() ?>reports/report_compra?oID=<?= $obra_id ?>">
                        Ver Resumen
                    </a>
                    <!-- button type="button" class="btn btn-success" onclick="openExcelUploadModal()">
                        <i class="fe fe-upload mr-1"></i>Importar Jornada
                    </button -->
                </div>
            </div>
        </div>
    </div>  
    <!-- End Row -->

    <!-- Row -->
    <div class="row">
        <div class="col-lg-9 col-sm-12">
            <div class="card mb-4">   
                <div class="card-body">                   
                    <div class="pull-right" style="width: 17em;">
                        <label for="tipo_rubro" > Selec. Rubro :</label>
                        <?php
                        form_dropdown_array("tipo_rubro",
                            $tipo_rubros,
                            " id='tipo_rubro' class='form-control select2-show-search' "
                            , $tipo_rubro, OPTION_DEFAULT_ALL, "codigo", "valor");
                        ?>
                        &nbsp;
                    </div>
                    <h4 class="page-title mb-0">Compras
                        <div class="btn btn-list">
                            <a style="padding: 0px 0px 0px 3px;" data-toggle="tooltip" title="Agregar Compra" class="btn btn-icon  btn-danger btnEdit"
                                href="<?= base_url() ?>compras/detalle_compra_create?shopID=<?= $compra_id ?? 0?>&siteID=<?= $obra_id ?>">
                                <i class="fe fe-plus mr-1" ></i>
                            </a>
                        </div>
                    </h4>
                    <br><br>
                    <hr class="my-0" /><br>
                    <div class="">
                        <table id="table1" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>id</th>
                                <th>fecha compra</th>
                                <th>encar.</th>
                                <th>prove.</th>
                                <th>tipo comp.</th>
                                <th>serie / nro</th>
                                <th>rubro</th>
                                <th>total</th>
                                <th>estado</th>
                                <th width="75px">acc.</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div> 
        </div>
        <div class="col-lg-3 col-sm-12">
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="page-title mb-0">Datos de la obra (Compra)</h4>
                    <hr class="my-0" /><br>
                    <div class="form-group">
                        <label class="form-label">Fecha Alta: </label>
                        <input readonly class="form-control" value="<?= $fecha_inicio ?>"/>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Fecha Final: </label>
                        <input readonly class="form-control" value="<?= $fecha_final?>"/>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Productos Registrados: </label>
                        <input readonly class="form-control" value="<?= $prod_reg ?> PRODUCTOS REGISTRADOS"/>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Importe Total Materiales: </label>
                        <input readonly class="form-control" value="S/. <?= $mat["materiales"] ?? .0?>"/>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Importe Total Herramientas: </label>
                        <input readonly class="form-control" value="S/. <?= $her["herramientas"] ?? .0?>"/>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Importe Total Equipos: </label>
                        <input readonly class="form-control" value="S/. <?= $eqp["equipos"] ?? .0?>"/>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Importe Total Compra: </label>
                        <input readonly class="form-control" value="S/. <?= $result_["total"] ?? .0?>"/>
                    </div>
                </div>
            </div>
        </div>
    </div>  
    <!-- End Row -->

<!-- /page content -->
<?php require_once  __DIR__.'/../include/footer.php'; ?>

<script type="text/javascript">
    var table1 = undefined;

    function refreshTable(data) {
        table1.ajax.reload();
    }

    $(function(){

        $("#tipo_rubro").on("change", function() {
            table1.draw();
        });

        table1 = $('#table1').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?=base_url()?>/compras/detalle_compra_list?obra_id=<?=$obra_id?>",
                "type": "POST",
                "data":
                function(data) {        
                    data.tipo_rubro = $('#tipo_rubro').val();      
                },
            },
            "aaSorting": [[0, "desc"]],
            "columns": [
                { "data": "id" },
                { "data": "fecha_compra" },
                { "data": "comprador" },
                { "data": "proveedor" },
                { "data": "tipo_comprobante" },
                { "data": "serie_numero" },
                { "data": "tipo_rubro" },
                { "data": "t_importe_total" },
                { "data": "status" },
                { "data": null },
            ],
            "columnDefs": [
                {
                    "targets": 0,
                    "visible": true,
                    "searchable": false
                },
                {
                    "targets": 9,
                    "render": function( data, type, row) {
                        var h = Mustache.render($("#tplDtColumnDetalleCompra").html(),
                                {
                                    row_id:  data.id,
                                    uri_remove: '<?= base_url() ?>compras/detalle_compra_delete?obra_id=<?= $obra_id ?>',
                                    callback_rem: 'refreshTable'
                                }
                            );
                        return h;
                    },
                    "bSortable": false,
                },
            ],
            "createdRow": function (row, data, index) {
                var status = data.status;
                if (status === '<?= RECORD_STATUS_ACTIVE_TEXT ?>') {
                    $(row).find('td:eq(8)').html('<span class="mb-0 text-success fs-13 font-weight-semibold">ACTIVO</span>');
                } else if (status === '<?= RECORD_STATUS_INACTIVE_TEXT ?>') {
                    $(row).find('td:eq(8)').html('<span class="mb-0 text-danger fs-13 font-weight-semibold">INACTIVO</span>');
                }
            },        
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
            }
    
        } );

    });

</script>

<!-- Style. Juanciño -->
<style>
	.select2-selection__rendered {
		color: #9cc3b4 !important;
	}
</style>