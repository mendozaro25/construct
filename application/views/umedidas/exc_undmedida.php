<div class="modal fade" id="excelUploadModal" tabindex="-1" role="dialog" aria-labelledby="excelUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="excelUploadModalLabel">Importar Unidad Medida</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="margin-bottom: -35px !important;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <a href="<?= base_url().UPLOAD_PATH_UNDMEDIDA_FORMAT ?>" target="_blank"><button class="btn btn-light">Excel Und. Medida .xlsx</button></a>
                        </div>
                    </div>
                </div>
            </div>
            <form id="excelUploadForm" action="<?= base_url() ?>umedidas/upload_excel" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Archivo Excel (.xlsx) <span class="text-red">*</span></label>
                                <input required type="file" class="dropify" id="excelFile" name="excelFile" accept=".xlsx, .xls">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="excelUploadButton">Aceptar</button>
                </div>
            </form>
        </div>
    </div>
</div>
