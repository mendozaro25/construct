<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="margin-left: 5px;font-weight: 700;text-transform: uppercase;">
                <h5 class="modal-title" id="imageModalLabel">Foto de Perfil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="row">     
                        <div class="col-md-6 col-lg-12">
                            <div class="card overflow-hidden">
                            <img  src="<?= base_url() ?><?= UPLOAD_PATH_USERS ?><?= $id?>/<?= $photo?>" alt="user-avatar" class="card-image1 ">
                                <div class="card-body">
                                    <h5 class="card-title">Descargar Imagen
                                        <a href="<?= base_url() ?><?= UPLOAD_PATH_USERS ?><?= $id?>/<?= $photo?>" download="<?= date('YmdHis').$id.$photo ?>" class="btn btn-danger">
                                            <i class="fa fa-cloud-download"></i>
                                        </a>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>