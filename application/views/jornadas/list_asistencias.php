<?php require_once  __DIR__.'/../include/header.php'; ?>
<?php require_once  __DIR__.'/../include/sidebar.php'; ?>

<?php 
    $obra_id = gw("obra", ["id" => $records[0]["obra_id"]])->row()->id;
    $fecha_inicio = gw("jornada", ["id" => $records[0]["jornada_id"]])->row()->fecha_inicio;
    $fecha_final = gw("jornada", ["id" => $records[0]["jornada_id"]])->row()->fecha_final;
?>

<!-- page content -->

    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0"><?= $page_title ?></h4>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>"><i class="fe fe-layers mr-2 fs-14"></i>Inicio</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="<?= base_url() ?>jornadas/jornada"><?= $page_title ?></a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="<?= base_url() ?>/jornadas/list_jornadas?num=<?= $obra_id ?>">Detalle Jornada</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="<?= base_url() ?>jornadas/asistencia?astID=<?= $jornada_id ?>">Asistencia</a></li>
            </ol>
        </div>
        <div class="page-rightheader">
            <div class="btn btn-list">
                <a type="button" data-toggle="tooltip" data-placement="left" class="btn btn-primary" title="Volver" 
                    href="<?= base_url() ?>jornadas/list_jornadas?num=<?= $obra_id ?>">
                    <i class="fe fe-arrow-left mr-1" ></i>Volver
                </a>
            </div>
        </div>
    </div>
    <!--End Page header-->

    <!-- Row -->
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label">Fecha Inicio </label>
                <input readonly class="form-control" value="<?= $fecha_inicio ?>">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label">Fecha Final </label>
                <input readonly class="form-control" value="<?= $fecha_final ?>">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label">Sueldo Total Jornada </label>
                <input readonly class="form-control" value="S/. <?= $res["sueldo_personal_semana"] ?? .0 ?>">
            </div>
        </div>
    </div>  
    <!-- End Row -->

    <!-- Row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card mb-4">   
                <div class="card-body">
                    <hr class="my-0" /><br>
                    <div class="">
                        <table id="table1" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>id</th>
                                <th>personal</th>
                                <th>área</th>
                                <th>espec.</th>
                                <th>ubicación</th>
                                <th>banco</th>
                                <th>nro.cuenta</th>
                                <th>estado</th>
                                <th width="70px">acc.</th>
                            </tr>
                            </thead>
                        </table>
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
        jj.modalFormCreate({id:id}, '<?= base_url() ?>jornadas/asistencia_create', '<?= base_url() ?>jornadas/asistencia_save', false, refreshTable, 
            {
                title: "Asistencia",
                size: 'large'
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
                "url": "<?= base_url() ?>jornadas/asistencia_list?jornada_id=<?= $records[0]["jornada_id"] ?>",
                "type": "POST"
            },
            "aaSorting": [[0, "desc"]],
            "columns": [
                { "data": "id" },
                { "data": "personal" },
                { "data": "area" },
                { "data": "especialidad" },
                { "data": "direccion" },
                { "data": "banco" },
                { "data": "num_cuenta" },
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
                    "targets": 8,
                    "render": function( data, type, row) {
                        var h = Mustache.render($("#tplDtColumnAsistencia").html(),
                                {
                                    row_id:  data.id,
                                    uri_remove: '<?= base_url() ?>jornadas/asistencia_delete',
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
                    $(row).find('td:eq(7)').html('<span class="mb-0 text-success fs-13 font-weight-semibold">ACTIVO</span>');
                } else if (status === '<?= RECORD_STATUS_INACTIVE_TEXT ?>') {
                    $(row).find('td:eq(7)').html('<span class="mb-0 text-danger fs-13 font-weight-semibold">INACTIVO</span>');
                }
            },        
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
            }

        } );

        $("#table1").on("click", ".btnAsist", function() {
            openForm($(this).data("row-id"));
        });

    });

</script>