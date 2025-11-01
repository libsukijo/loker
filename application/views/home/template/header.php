<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('assets/images/logo-perpus.png') ?>" />
    <title><?php echo $page ?></title>

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
            width: 30%;
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

<body class="hold-transition sidebar-mini layout-fixed" onload="startTime()" style="overflow: hidden;">
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="<?php echo base_url('assets/images/logo-perpus.png') ?>" alt="AdminLTELogo" height="100" width="100">
    </div>

    <div class="wrapper">
        <aside class="main-sidebar" style="width: 400px;background-color:black">
            <div class="logo-perpus">
                <a href="<?php echo base_url('home') ?>">
                    <img src="<?php echo base_url('assets/images/logo-perpus.png') ?>">
                </a>
            </div>

            <div class="foto-anggota">
                <div id="data-anggota">
                </div>
            </div>
            <div class="information-key">
                <div class="text-center">
                    <h2>KETERSEDIAAN</h2>
                    <p>KUNCI PUTRA</p>
                    <p> <a style="color:aqua"><?php echo $sisa_loker_pria ?> </a> | <a style="color:yellow"><?php echo $total_loker_pria ?> </a> </p>
                    <p>KUNCI PUTRI</p>
                    <p> <a style="color:aqua"><?php echo $sisa_loker_wanita ?> </a> | <a style="color:yellow"><?php echo $total_loker_wanita ?> </a> </p>

                </div>
            </div>
        </aside>
        <div class="content-wrapper" style="margin-left: 390px; background-color:#0f0f0f;color:#fff;padding:20px;font-size:50px">
            <div class="content-header" style="margin-bottom: 30px;">
                <div class="container-fluid">
                    <div class="row mb-2" style="padding: 2px;">
                        <div class="col-sm-8" style="font-size: 20px;">
                            <h1 class="m-0" style="font-size: 40px;"><?php echo $title ?></h1>
                        </div>
                        <div class="col-sm-2" style="padding: 2px;color:#8c745b;font-family:monospace;">
                            <h1 style="border:1px solid black;background-color:black;height:50px" class="text-center">
                                <div id="date"></div>
                            </h1>
                        </div>
                        <div class="col-sm-2" style="padding: 2px;color:#8c745b;font-family:monospace">
                            <h1 style="border:1px solid black;background-color:black;height:50px" class="text-center">
                                <div id="clock"></div>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>


            <script type="text/javascript">
                function startTime() {
                    const today = new Date();
                    let h = today.getHours();
                    let m = today.getMinutes();
                    let s = today.getSeconds();
                    m = checkTime(m);
                    s = checkTime(s);
                    $('#clock').text(h + ':' + m + ':' + s);
                    $('#date').text(today.getFullYear() + '/' + (today.getMonth() + 1) + '/' + today.getDate());
                    setTimeout(startTime, 1000);
                }

                function checkTime(i) {
                    if (i < 10) {
                        i = "0" + i
                    }; // add zero in front of numbers <script 10
                    return i;
                }
            </script>