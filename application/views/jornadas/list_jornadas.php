<?php require_once  __DIR__.'/../include/header.php'; ?>
<?php require_once  __DIR__.'/../include/sidebar.php'; ?>
<!-- page content -->

    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0"><?= $page_title ?></h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><i class="fe fe-layers mr-2 fs-14"></i>Inicio</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="<?= base_url() ?>jornadas/jornada"><?= $page_title ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="<?= base_url() ?>jornadas/list_jornadas?num=<?= $obra_id ?>">Detalle Jornada</a></li>
            </ol>
        </div>
        <div class="page-rightheader">
            <div class="btn btn-list">
                <a type="button" data-toggle="tooltip" data-placement="left" class="btn btn-primary" title="Volver" href="<?= base_url() ?>jornadas/jornada">
                    <i class="fe fe-arrow-left mr-1" ></i>Volver
                </a>
            </div>
        </div>
    </div>
    <!--End Page header-->

    <?php include("exc_jornada.php"); ?>

    <!-- Row -->
    <div class="row">
        <div class="col-lg-8 col-sm-12">
            <div class="page-rightheader" style="margin-top: 7px;">
                <div class="btn-list">
                    <input disabled style="width: 500px;" class="form-control" value="<?= $nombre ?>">
                </div>
            </div> 
        </div>
        <div class="col-lg-4 col-sm-12">
            <div class="page-rightheader">
                <div class="btn btn-list">
                    <a type="button" data-toggle="tooltip" data-placement="left" class="btn btn-info" title="Resumen" 
                        href="<?= base_url() ?>reports/report_personal?oID=<?= $obra_id ?>">
                        Ver Resumen
                    </a>
                    <button type="button" class="btn btn-success" onclick="openExcelUploadModal()">
                        <i class="fe fe-upload mr-1"></i>Importar Jornada
                    </button>
                </div>
            </div>
        </div>
    </div>  
    <!-- End Row -->

    <!-- Row -->
    <div class="row">
        <div class="col-lg-8 col-sm-12">
            <div class="card mb-4">   
                <div class="card-body">
                    <h4 class="page-title mb-0">Jornadas
                        <div class="btn btn-list">
                            <button style="padding: 0px 0px 0px 3px;" type="button" data-toggle="tooltip" data-placement="right" class="btn btn-danger" title="Agregar jornada" onclick="openForm()">
                                <i class="fe fe-plus mr-1" ></i>
                            </button>
                        </div>
                    </h4>
                    <hr class="my-0" /><br>
                    <div class="">
                        <table id="table1" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>id</th>
                                <th>obra</th>
                                <th>jornada</th>
                                <th>dinicio</th>
                                <th>dfinal</th>
                                <th>estado</th>
                                <th width="110px">acc.</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div> 
        </div>
        <div class="col-lg-4 col-sm-12">
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="page-title mb-0">Datos de la obra (Personal)</h4>
                    <hr class="my-0" /><br>
                    <div class="form-group">
                        <label class="form-label">Fecha Alta: </label>
                        <input readonly class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?= $fecha_inicio ?>"/>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Periodo Actual: </label>
                        <input readonly class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?= $result__["periodo_actual"] ?? $fecha_inicio?>"/>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Personal Registrado: </label>
                        <input readonly class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?= $person_reg ?> REGISTRADOS"/>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sueldo Total Personal: </label>
                        <input readonly class="form-control" name="fecha_inicio" id="fecha_inicio" value="S/. <?= $result_["sueldo_personal_semana"] ?? .0?>"/>
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

	function openForm(id) {
	    id = id || 0;
        jj.modalFormCreate({id:id}, '<?= base_url() ?>jornadas/jornadas_personal_create?obra_id=<?= $obra_id ?>', '<?= base_url() ?>jornadas/jornadas_personal_save', false, refreshTable, 
            {
                title: (id ? "Editar" : "Nueva") + " Jornada",
            }
        );
	}

    function refreshTable(data) {
        table1.ajax.reload();
    }

    $(function(){

        table1 = $('#table1').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?=base_url()?>/jornadas/jornadas_personal_list?obra_id=<?=$obra_id?>",
                "type": "POST"
            },
            "aaSorting": [[0, "desc"]],
            "columns": [
                { "data": "id" },
                { "data": "obra" },
                { "data": "descripcion" },
                { "data": "fecha_inicio" },
                { "data": "fecha_final" },
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
                    "targets": 6,
                    "render": function( data, type, row) {
                        var h = Mustache.render($("#tplDtColumnJornadaPersonal").html(),
                                {
                                    row_id:  data.id,
                                    uri_remove: '<?= base_url() ?>jornadas/jornadas_personal_delete?obra_id=<?= $obra_id ?>',
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
                    $(row).find('td:eq(5)').html('<span class="mb-0 text-success fs-13 font-weight-semibold">ACTIVO</span>');
                } else if (status === '<?= RECORD_STATUS_INACTIVE_TEXT ?>') {
                    $(row).find('td:eq(5)').html('<span class="mb-0 text-danger fs-13 font-weight-semibold">INACTIVO</span>');
                }
            },        
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
            }
    
        } );

        $("#table1").on("click", ".btnEdit", function() {
            openForm($(this).data("row-id"));
        });

    });

    // Función para abrir el modal de carga de Excel
    function openExcelUploadModal() {
        $('#excelUploadModal').modal('show');
    }

</script>