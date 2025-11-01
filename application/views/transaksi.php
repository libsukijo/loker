<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet" href="<?php echo base_url('assets/plugins') ?>/fontawesome-free/css/all.min.css">

    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <link rel="stylesheet" href="<?php echo base_url('assets/plugins') ?>/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">

    <link rel="stylesheet" href="<?php echo base_url('assets/plugins') ?>/icheck-bootstrap/icheck-bootstrap.min.css">

    <link rel="stylesheet" href="<?php echo base_url('assets/plugins') ?>/jqvmap/jqvmap.min.css">

    <link rel="stylesheet" href="<?php echo base_url('assets/template/') ?>dist/css/adminlte.min.css?v=3.2.0">
    <style>
        .logo-perpus {
            border-bottom: 1px solid #0069D9;
            padding: 10px;
        }

        .logo-perpus img {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;

        }

        .logo-perpus a {
            height: 170px;
        }

        .foto-anggota {
            flex: 1 1 auto;
            height: 320px;
            padding: 1.25rem;
            color: #fff;
            font-size: 50px;
            border-bottom: 1px solid #0069D9;
        }

        .foto-anggota img {
            width: 40%;
            height: 100%;
        }

        .information-key {
            flex: 1 1 auto;
            height: 320px;
            padding: 1.25rem;
            color: #fff;
            font-size: 25px;

        }
    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <aside class="main-sidebar" style="width: 400px;background-color:black">
            <div class="logo-perpus">
                <a href="#">
                    <img src="<?php echo base_url('assets/images/logo-perpus.png') ?>">
                </a>
            </div>

            <div class="foto-anggota">
                <!-- <div class="text-center">
                    <img class="profile-user-img img-fluid img-box" src="https://adminlte.io/themes/v3/dist/img/user4-128x128.jpg" alt="User profile picture">
                </div>
                <h3 class="profile-username text-center">Nina Mcintire</h3>
                <h3 class="profile-username text-center">122900011</h3>
                <h3 class="profile-username text-center">SINTEK</h3> -->
            </div>
            <div class="information-key">
                <div class="text-center">
                    <h2>KETERSEDIAAN</h2>
                    <p>KUNCI PUTRA</p>
                    <p> <a style="color:aqua">5000 </a> | <a style="color:yellow">5000 </a> </p>
                    <p>KUNCI PUTRI</p>
                    <p> <a style="color:aqua">5000 </a> | <a style="color:yellow">5000 </a> </p>

                </div>
            </div>
        </aside>
        <div class="content-wrapper" style="margin-left: 390px; background-color:#0f0f0f;color:#fff;padding:20px;font-size:50px">
            <div class="content-header" style="margin-bottom: 30px;">
                <div class="container-fluid">
                    <div class="row mb-2" style="padding: 2px;">
                        <div class="col-sm-8" style="font-size: 20px;">
                            <h1 class="m-0" style="font-size: 40px;">DEALKEY KUNCI LOKER</h1>
                        </div>
                        <div class="col-sm-2" style="padding: 2px;color:#8c745b;font-family:monospace">
                            <h1 style="border:1px solid black;background-color:black" class="text-center"><?php echo date('y/m/d') ?></h1>
                        </div>
                        <div class="col-sm-2" style="padding: 2px;color:#8c745b;font-family:monospace">
                            <h1 style="border:1px solid black;background-color:black" class="text-center"><?php echo date('h:i') ?> PM</h1>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card" style="border-top: 4px solid #0069D9 ;">
                                <div class="card-body text-center" style="background-color:#0f0f0f">
                                    <button type="button" class="btn btn-warning" style="height: 300px;width:300px;font-size:50px">PINJAM</button>
                                    <button type="button" class="btn btn-danger" style="height: 300px;width:300px;font-size:50px">KEMBALI</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </section>


        </div>
    </div>
</body>

<script src="<?php echo base_url('assets/plugins') ?>/jquery/jquery.min.js"></script>

<script src="<?php echo base_url('assets/plugins') ?>/jquery-ui/jquery-ui.min.js"></script>


<script src="<?php echo base_url('assets/plugins') ?>/bootstrap/js/bootstrap.bundle.min.js"></script>


<script src="<?php echo base_url('assets/template/') ?>dist/js/adminlte.js?v=3.2.0"></script>

<script src="<?php echo base_url('assets/template/') ?>dist/js/pages/dashboard.js"></script>




<!-- AdminLTE App -->
<script src="<?php echo base_url('assets/template/') ?>dist/js/adminlte.min.js"></script>