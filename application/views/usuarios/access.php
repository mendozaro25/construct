<?php require_once  __DIR__.'/../include/header.php'; ?>
<?php require_once  __DIR__.'/../include/sidebar.php'; ?>
<!-- page content -->

    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0"><?= $page_title ?></h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><i class="fe fe-layers mr-2 fs-14"></i>Inicio</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="<?= base_url() ?>usuarios/usuario">Usuarios</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="<?= base_url() ?>usuarios/usuario_create_access?userID=<?= $id ?>">Acceso Usuario</a></li>
            </ol>
        </div>
        <div class="page-rightheader">
            <div class="btn btn-list">
                <a type="button" data-toggle="tooltip" data-placement="left" class="btn btn-primary" title="Volver" 
                    href="<?= base_url() ?>usuarios/usuario">
                    <i class="fe fe-arrow-left mr-1" ></i>Volver
                </a>
            </div>
        </div>
    </div>
    <!--End Page header-->

    <!-- Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        Accesos Usuario : <?= $name.' '.$lastname ?>
                    </div>
                </div>
                <div class="card-body">
                    <div id="tree"></div>
                    <div class="btn btn-list">
                        <button id="btnSave" class="btn btn-ligth mt-3">Guardar</button>
                        <button id="btnReset" class="btn btn-ligth mt-3">Limpiar</button>
                        <button id="btnSelectAll" class="btn btn-ligth mt-3">Seleccionar todos</button>
                        <button id="btnViewSave" class="btn btn-ligth mt-3">Restablecer valores</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Row -->

<!-- /page content -->
<?php require_once  __DIR__.'/../include/footer.php'; ?>

<script type="text/javascript">
    $(document).ready(function () {
        var dataSource = <?php echo json_encode($dataSource) ?>;
        var savedNodes = <?php echo json_encode($savedNodes) ?>;

        var tree = $('#tree').tree({
            primaryKey: 'id',
            uiLibrary: 'bootstrap4',
            dataSource: dataSource,
            checkboxes: true
        });

        tree.on('dataBound', function() {
            tree.expandAll();
        });

        var nodeIds = savedNodes.map(function(node) {
            return node.id;
        });

        for (var i = 0; i < nodeIds.length; i++) {
            var nodeId = nodeIds[i];
            var node = tree.getNodeById(nodeId);

            if (node) {
                tree.check(node);
            } else {
                console.log('El nodo con ID ' + nodeId + ' no se encontró en el árbol.');
            }
        }
    
        $('#btnSave').on('click', function () {
            var checkedIds = tree.getCheckedNodes();
            
            if (confirm("¿Deseas guardar los cambios?")) {
                $.ajax({
                    url: '<?= base_url() ?>usuarios/usuario_save_access?userID=<?= $id ?>',
                    data: { checkedIds: checkedIds },
                    method: 'POST'
                }).done(function (response) {
                    if (response.success) {
                        notif({
                            type: "success",
                            msg: "CAMBIO GUARDADO CON ÉXITO",
                            position: "right",
                            timeout: 3000
                        });
                    } else {
                        notif({
                            type: "error",
                            msg: "HUBO UN ERROR AL GUARDAR",
                            position: "right",
                            timeout: 3000
                        });
                    }
                }).fail(function () {
                    notif({
                        type: "error",
                        msg: "SELECCIONA AL MENOS UN ELEMENTO DE LA LISTA",
                        position: "right",
                        timeout: 3000
                    });
                });
            }
        });

        $('#btnReset').on('click', function () {
            tree.uncheckAll();
        });

        $('#btnSelectAll').on('click', function () {
            tree.checkAll();
        });

        $('#btnViewSave').on('click', function () {    
            tree.uncheckAll();
                        
            var nodeIds = savedNodes.map(function(node) {
                return node.id;
            });

            for (var i = 0; i < nodeIds.length; i++) {
                var nodeId = nodeIds[i];
                var node = tree.getNodeById(nodeId);

                if (node) {
                    tree.check(node);
                } else {
                    console.log('El nodo con ID ' + nodeId + ' no se encontró en el árbol.');
                }
            }
        });

    });
</script>

<style>
    .row {
        margin: 10px;
    }
</style>