<!doctype html>
<html lang="en">

<head>
    <title><?php echo $title; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> -->

    <link rel="stylesheet" href="<?php echo base_url('assets') ?>/plugins/fontawesome-free/css/all.min.css">

    <link rel="stylesheet" href="<?php echo base_url('assets/login/') ?>css/style.css">

</head>

<body class="img js-fullheight" style="background-image: url(<?php echo base_url('assets/login/') ?>images/bg.jpg);">
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center mb-5">
                    <div class="login-logo">
                        <img src="<?php echo base_url('assets/images/logo-perpus.png') ?>" style="max-width: 100%; height: 100px;">
                    </div>
                    <h2 class="heading-section">PERPUSTAKAAN UIN SUNAN KALIJAGA YOGYAKARTA</h2>
                </div>


            </div>
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="login-wrap p-0">
                        <h3 class="mb-4 text-center">DEALKEY KUNCI LOKER</h3>
                        <?php if ($this->session->flashdata('message')) { ?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
                                <?php echo $this->session->flashdata('message') ?>
                            </div>
                        <?php } ?>
                        <form action="<?php echo base_url('login') ?>" class="signin-form" method="post">

                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Username" name="username">
                                <?php echo form_error('email', '<p class="text-sm text-danger" style="margin-left:15px">', '</p>'); ?>
                            </div>

                            <div class="form-group">
                                <input type="password" class="form-control" placeholder="Password" name="pass">
                                <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                            </div>
                            <?php echo form_error('password', '<p class="text-sm text-danger">', '</p>'); ?>
                            <div class="form-group">
                                <button type="submit" class="form-control btn btn-primary submit px-3">Sign In</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="<?php echo base_url('assets/login/') ?>js/jquery.min.js"></script>
    <script src="<?php echo base_url('assets/login/') ?>js/popper.js"></script>
    <script src="<?php echo base_url('assets/login/') ?>js/bootstrap.min.js"></script>
    <script src="<?php echo base_url('assets/login/') ?>js/main.js"></script>

</body>

</html>