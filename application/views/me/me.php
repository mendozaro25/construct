<?php require_once  __DIR__.'/../include/header.php'; ?>
<?php require_once  __DIR__.'/../include/sidebar.php'; ?>
<?php include('view_perfil.php'); ?>
<!-- page content -->
<!--Page header-->
<div class="page-header">
	<div class="page-leftheader">
		<h4 class="page-title mb-0"><?= $page_title ?></h4>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url() ?>"><i class="fe fe-layers mr-2 fs-14"></i>Inicio</a></li>
			<li class="breadcrumb-item active" aria-current="page"><a href="<?= base_url() ?>me/me_view"><?= $title ?></a></li>
		</ol>
	</div>
</div>
<!--End Page header-->
<!-- Row -->
<form method="post" enctype="multipart/form-data" action="me_save" onsubmit="return confirm('¿Estás seguro de actualizar tu información?')">
    <div class="row">
        <div class="col-lg-6 col-sm-12">
            <div class="card mb-4">                 
                <!-- Account -->
                <div class="card-body">
                    <div class="d-flex align-items-end align-items-sm-center gap-4">
                        <?php if($photo == UPLOAD_DEFAULT_NOT_IMAGE) { ?>
                            <img
                            src="<?= base_url() ?><?= UPLOAD_PATH_USERS ?><?= $photo?>"
                            alt="user-avatar"
                            class="d-block rounded"
                            height="100"
                            width="100"
                            id="uploadedAvatar"
                            />
                        <?php } else { ?>	
                            <img
                            src="<?= base_url() ?><?= UPLOAD_PATH_USERS ?><?= $id?>/<?= $photo?>"
                            alt="user-avatar"
                            class="d-block rounded gallery-item"
                            height="100"
                            width="100"
                            id="uploadedAvatar"
                            data-toggle="modal"
                            data-target="#imageModal"
                            />
                        <?php } ?>

                        <div class="button-wrapper" style="margin-left: 15px;">
                        <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                            <span class="d-none d-sm-block">Elegir foto</span>
                            <i class="bx bx-upload d-block d-sm-none"></i>
                            <input
                            type="file"
                            id="upload"
                            name="photo"
                            class="account-file-input"
                            hidden
                            accept="image/png, image/jpeg, image/jpg"
                            />
                        </label>
                        <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                            <i class="bx bx-reset d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Eliminar</span>
                        </button>
                        <p class="text-muted mb-0">Permitido imágenes en formato JPG, JPEG o PNG.</p>
                        </div>
                    </div>
                </div>
                <hr class="my-0" />
                <div class="card-body">
                    <div class="row">                                
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Usuario</label>
                            <input
                            type="text"
                            class="form-control"
                            id="username"
                            name="username"
                            value="<?= $username ?>"
                            />
                        </div>                            
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Clave</label>
                            <input 
                                type="password"
                                class="form-control input-password"
                                disabled
                                name="password" 
                                id="password" 
                                value="<?= $password ?>"/>                                                
                            <a href="javascript:void(0);" onclick="enableOrDisableInput();" class="btn btn-sm" ><i class="fa fa-undo"></i> Cambiar clave </a>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
        <div class="col-lg-6 col-sm-12">
            <div class="card mb-4">
                <div class="card-body">
                    <input type="hidden" value="<?= $id ?>" name="id">
                    <div class="row">                                     
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Email</label>
                            <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            value="<?= $email ?>"
                            />
                        </div>                            
                        <div class="mb-3 col-md-4">
                            <label class="form-label">DNI</label>
                            <input
                            type="text"
                            class="form-control"
                            id="dni"
                            name="dni"
                            value="<?= $dni ?>"
                            maxlength="8"
                            />
                        </div>                                
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Celular</label>
                            <input
                            type="text"
                            class="form-control"
                            id="phone"
                            name="phone"
                            value="<?= $phone ?>"
                            maxlength="9"
                            />
                        </div>
                    </div>

                    <div class="row"> 
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Nombres</label>
                            <input
                            class="form-control"
                            type="text"
                            id="name"
                            name="name"
                            value="<?= $name ?>"
                            />
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Apellidos</label>
                            <input 
                            class="form-control" 
                            type="text" 
                            name="lastname" 
                            id="lastname" 
                            value="<?= $lastname ?>" 
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>                                    
    <div class="mt-2">
        <div>
            <button type="submit" class="btn btn-primary waves-effect waves-light">
                Guardar
            </button>
            <button type="reset" class="btn btn-info waves-effect m-l-5">
                Limpiar
            </button>
            <a onclick="window.location.href='<?= base_url() ?>dashboard'" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </div>
</form>
<!-- End Row-->
<!-- /page content -->
<?php require_once  __DIR__.'/../include/footer.php'; ?>

<script type="text/javascript" >
    document.addEventListener('DOMContentLoaded', function (e) {
        (function () {
            const deactivateAcc = document.querySelector('#formAccountDeactivation');

            // Update/reset user image of account page
            let accountUserImage = document.getElementById('uploadedAvatar');
            const fileInput = document.querySelector('.account-file-input'),
            resetFileInput = document.querySelector('.account-image-reset');

            if (accountUserImage) {
            const resetImage = accountUserImage.src;
            fileInput.onchange = () => {
                if (fileInput.files[0]) {
                accountUserImage.src = window.URL.createObjectURL(fileInput.files[0]);
                }
            };
            resetFileInput.onclick = () => {
                fileInput.value = '';
                accountUserImage.src = resetImage;
            };
            }
        })();
    });

    function enableOrDisableInput() {
    let input = document.getElementById("password");
        if (input.disabled === true) {
            input.disabled = false;
            input.value = "";
            input.focus();
        } else {
            input.disabled = true;
            input.value = this.value;
        }
    }

    $(document).ready(function() {
        $('.gallery-item').magnificPopup({
            type: 'image',
            gallery: {
            enabled: true
            }
        });
    });

</script>