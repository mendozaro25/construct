<?php require_once  __DIR__.'/../include/header.php'; ?>
<!-- page content -->

<body class="h-100vh bg-dark">

    <div class="box">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>

    <div class="page">
        <div class="page-content">
            <div class="container text-center">
                <div class="row">
                    <div class="col-md-6">
                        <div class="">
                            <div class="text-white">
                                <div style="margin-top: 75px;" class="display-1 mb-5 font-weight-bold error-text">¡Oh no!</div>
                                <h1 class="h3  mb-3 font-weight-bold">Al parecer usted no tiene acceso a esta vista.</h1>
                                <p class="h5 font-weight-normal mb-7 leading-normal">Si cree que esto es un error, ponganse en contacto con el administrador.</p>
                                <a class="btn btn-danger" href="<?= base_url() ?>"><i class="fe fe-arrow-left-circle mr-1"></i>Volver al inicio</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-none d-md-flex align-items-center">
                        <img src="<?= base_url() ?>assets/upload/images/error-403.png" alt="error_403">
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- /page content -->
<?php require_once  __DIR__.'/../include/footer.php'; ?>