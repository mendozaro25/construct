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
                <li class="breadcrumb-item" aria-current="page"><a href="<?= base_url() ?>/compras/detalle_compra?num=<?= $obra_id ?>">Detalle Compra</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="<?= base_url() ?>/compras/detalle_compra?num=<?= $obra_id ?>">Reporte Compras</a></li>
            </ol>
        </div>
        <div class="page-rightheader">
            <div class="btn btn-list">
                <a type="button" data-toggle="tooltip" data-placement="left" class="btn btn-primary" title="Volver" href="<?= base_url() ?>/compras/detalle_compra?num=<?= $obra_id ?>">
                    <i class="fe fe-arrow-left mr-1" ></i>Volver
                </a>
            </div>
        </div>
    </div>
    <!--End Page header-->

<!-- Row -->
<div class="row">
    <div class="col-md-12">                 
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
        <div class="pull-right" style="width: 17em; margin-right: 1em;">
            <label for="proveedor" > Selec. Proveedor :</label>
            <?php
            form_dropdown_array("proveedor",
                $proveedores,
                " id='proveedor' class='form-control select2-show-search' "
                , $proveedor, OPTION_DEFAULT_ALL, "id", "proveedor");
            ?>
            &nbsp;
        </div>                    
        <div class="pull-right" style="width: 17em; margin-right: 1em;">
            <label for="comprador" > Selec. Comprador :</label>
            <?php
            form_dropdown_array("comprador",
                $compradores,
                " id='comprador' class='form-control select2-show-search' "
                , $comprador, OPTION_DEFAULT_ALL, "id", "comprador");
            ?>
            &nbsp;
        </div>                  
        <div class="pull-right" style="width: 17em; margin-right: 1em;">
            <label for="tipo_comprobante" > Selec. Comprobante :</label>
            <?php
            form_dropdown_array("tipo_comprobante",
                $tipo_comprobantes,
                " id='tipo_comprobante' class='form-control select2-show-search' "
                , $tipo_comprobante, OPTION_DEFAULT_ALL, "codigo", "valor");
            ?>
            &nbsp;
        </div>    
    </div>
</div>
<div class="row">
	<div class="col-md-12">        
		<div class="card">
			<div class="card-body">
				<div class="">
					<table id="table1" class="table table-striped table-bordered">
						<thead>
						<tr>
							<th>Fecha</th>
							<th>Rubro</th>
							<th>Comprador</th>
							<th>Proveedor</th>
							<th>DirecciónPro.</th>
							<th>Serie/N°.</th>
							<th>Compr.</th>
							<th>Producto</th>
							<th>Unidad</th>
							<th>Cantidad</th>
							<th>C/U</th>
							<th>Parcial</th>
							<th>IGV</th>
							<th>Total</th>
						</tr>
						</thead>
                            <tfoot>
                            <tr>
                                <td colspan="13" style="text-align: right;letter-spacing: 0.3px;"></td>
                                <td></td>
                            </tr>
                            </tfoot>
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

    $(function(){
            
        $("#tipo_rubro").on("change", function() {
            table1.draw();
        });

        $("#proveedor").on("change", function() {
            table1.draw();
        });
            
        $("#comprador").on("change", function() {
            table1.draw();
        });

        $("#tipo_comprobante").on("change", function() {
            table1.draw();
        });

        table1 = $('#table1').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= $uri["report"] ?>?oID=<?= $obra_id ?>",
                "type": "POST",
                "data":
                function(data) {      
                    data.tipo_rubro = $('#tipo_rubro').val();               
                    data.proveedor = $('#proveedor').val();
                    data.comprador = $('#comprador').val();                  
                    data.tipo_comprobante = $('#tipo_comprobante').val();
                },
            },
            "aaSorting": [[0, "desc"]],
            "columns": [
                { "data": "fecha_compra" },
                { "data": "tipo_rubro" },
                { "data": "comprador" },
                { "data": "proveedor" },
                { "data": "direccion_proveedor" },
                { "data": "serie_numero" },
                { "data": "tipo_comprobante" },
                { "data": "producto" },
                { "data": "unidad_medida" },
                { "data": "cantidad" },
                { "data": "precio_unitario" },
                { "data": "parcial" },
                { "data": "igv" },
                { "data": "total" },
            ],
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api();
                api.columns().every(function (index) {
                var column = this;
                var sum = column.data().reduce(function (a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0);				
                $(column.footer()).html("<b>" + (index <=12 ? "IMPORTE TOTAL: " : sum.toFixed(2)) + "</b>" );
                });
            },
            "columnDefs": [
                {
                    "targets": [9,10,11,12,13],
                    "render": function(d){
                        var m = parseFloat(d || .0);
                        return m.toFixed(2);
                    },
                }
            ],       
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
            },
            // Aquí agregas la opción dom
            dom: '<"mb-2"<"d-flex justify-content-between"Blf>>t<"mt-2"ip>',
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o mr-1"></i> Exportar a Excel',
                    titleAttr: 'Exportar a Excel',
                    className: 'btn btn-excel mr-2',
                    title: function(){
                        return 'Reporte de Compra';
                    },    
                    filename: function(){
                        var date = new Date().toISOString().slice(0,19).replace(/[-T]/g,'').replace(/:/g,'');
                        return 'report_' + date + '_excel';
                    },
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf-o mr-1"></i> Exportar a PDF',
                    titleAttr: 'Exportar a PDF',
                    className: 'btn btn-pdf',
                    title: function(){
                        return 'Reporte de Compra';
                    },    
                    filename: function(){
                        var date = new Date().toISOString().slice(0,19).replace(/[-T]/g,'').replace(/:/g,'');
                        return 'report_' + date + '_pdf';
                    }     
                }
            ]
        });
    });

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

<!-- Style. Juanciño -->
<style>
    .btn-pdf {
        background-color: #ff0000;
        border-color: #ff0000;
    }
    .btn-excel {
        background-color: #38cb89;
        border-color: #38cb89;
    }
    .dataTables_wrapper .dt-buttons .btn-pdf:hover {
        background-color: #ff0000a6;
    }
    .dataTables_wrapper .dt-buttons .btn-excel:hover {
        background-color: #38cb89d6;
    }
	.select2-selection__rendered {
		color: #9cc3b4 !important;
	}
</style>