<div class="modal fade" id="excelUploadModal" tabindex="-1" role="dialog" aria-labelledby="excelUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="excelUploadModalLabel">Importar Jornada</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="excelUploadForm" action="<?= base_url() ?>jornadas/upload_excel?num=<?= $obra_id ?>" method="post" enctype="multipart/form-data">
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
