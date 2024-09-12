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
            <li class="breadcrumb-item" aria-current="page"><a href="<?= base_url() ?>/jornadas/list_jornadas?num=<?= $obra_id ?>">Detalle Jornada</a></li>
			<li class="breadcrumb-item active" aria-current="page"><a href="<?= base_url() ?>reports/report_personal?oID=<?= $obra_id ?>"><?= $uri["title"] ?></a></li>
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
    <div class="col-md-12">                 
        <div class="pull-right" style="width: 17em;">
            <label for="jornada" > Selec. Jornada :</label>
            <?php
            form_dropdown_array("jornada",
                $jornadas,
                " id='jornada' class='form-control select2-show-search'"
                , $jornada, OPTION_DEFAULT_ALL, "id", "descripcion");
            ?>
            &nbsp;
        </div>                  
        <div class="pull-right" style="width: 17em; margin-right: 1em;">
            <label for="personal" > Selec. Personal :</label>
            <?php
            form_dropdown_array("personal",
                $personales,
                " id='personal' class='form-control select2-show-search' "
                , $personal, OPTION_DEFAULT_ALL, "id", "personal");
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
							<th>Ubicación</th>
							<th>Especialidad</th>
							<th>Personal</th>
							<th>Banco</th>
							<th>Ncuenta</th>
							<th>Jornada</th>
							<th>Tas</th>
							<th>The</th>
							<th>S</th>
							<th>Sfj</th>
							<th>She</th>
							<th>Total</th>
						</tr>
						</thead>
                            <tfoot>
                            <tr>
                                <td colspan="11" style="text-align: right;letter-spacing: 0.3px;"></td>
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
            
        $("#jornada").on("change", function() {
            table1.draw();
        });

        $("#personal").on("change", function() {
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
                    data.personal = $('#personal').val();                  
                    data.jornada = $('#jornada').val();
                },
            },
            "aaSorting": [[0, "desc"]],
            "columns": [
                { "data": "direccion" },
                { "data": "especialidad" },
                { "data": "personal" },
                { "data": "banco" },
                { "data": "num_cuenta" },
                { "data": "jornada" },
                { "data": "t_asistencia" },
                { "data": "t_horas_extras" },
                { "data": "sueldo" },
                { "data": "sueldo_fijo" },
                { "data": "sueldo_horas_extras" },
                { "data": "sueldo_total" },
            ],
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api();
                api.columns().every(function (index) {
                var column = this;
                var sum = column.data().reduce(function (a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0);				
                $(column.footer()).html("<b>" + (index <=10 ? "TOTAL SUELDO PERSONAL: " : sum.toFixed(2)) + "</b>" );
                });
            },
            "columnDefs": [
                {
                    "targets": [6,7,8,9,10,11],
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
                        return 'Reporte de Repersonal';
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
                        return 'Reporte de Personal';
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