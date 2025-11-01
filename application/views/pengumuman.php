<!doctype html>
<html lang="en">

<head>
    <title>Break Istirahat</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo base_url('assets/template/pengumuman/') ?>css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/template/pengumuman/') ?>css/owl.theme.default.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/4.5.6/css/ionicons.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/template/pengumuman/') ?>css/style.css">
</head>

<body>
    <div class="home-slider owl-carousel js-fullheight">
        <div class="slider-item js-fullheight" style="background-image:url(<?php echo base_url('assets/template/pengumuman/') ?>images/slider-1.jpg);">
            <div class="overlay"></div>
            <div class="container">
                <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center">
                    <div class="col-md-12 ftco-animate">
                        <div class="text w-100 text-center">
                            <!-- <h2>"Takdir itu milik Allah, namun usaha dan doa adalah milik kita"</h2> -->
                            <h1 class="mb-2" style="border:2px solid black;font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif">Time For a Break</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="slider-item js-fullheight" style="background-image:url(<?php echo base_url('assets/template/pengumuman/') ?>images/slider-2.jpg);">
            <div class="overlay"></div>
            <div class="container">
                <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center">
                    <div class="col-md-12 ftco-animate">
                        <div class="text w-100 text-center">
                            <!-- <h2>Best Place to Travel</h2> -->
                            <h1 class="mb-2" style="border:2px solid black;font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif">Time For a Break</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="slider-item js-fullheight" style="background-image:url(<?php echo base_url('assets/template/pengumuman/') ?>images/slider-2.jpg);">
            <div class="overlay"></div>
            <div class="container">
                <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center">
                    <div class="col-md-12 ftco-animate">
                        <div class="text w-100 text-center">
                            <h1 class="mb-2" style="border:2px solid black;font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif">Time For a Break</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url('assets/template/pengumuman/') ?>js/jquery.min.js"></script>
    <script src="<?php echo base_url('assets/template/pengumuman/') ?>js/popper.js"></script>
    <script src="<?php echo base_url('assets/template/pengumuman/') ?>js/bootstrap.min.js"></script>
    <script src="<?php echo base_url('assets/template/pengumuman/') ?>js/owl.carousel.min.js"></script>
    <script src="<?php echo base_url('assets/template/pengumuman/') ?>js/main.js"></script>
</body>

</html>

<script type="text/javascript">
    setInterval("pengumuman()", 1000);

    function pengumuman() {
        var today = new Date();
        var hr = today.getHours();
        var min = today.getMinutes();
        console.log('t');
        var d = checkTime(hr) + ':' + checkTime(min);
        // var awal = '11:55';
        var akhir = '12:50';
        if (akhir < d) {
            window.location = "<?php echo base_url() ?>";
        }

    }

    function checkTime(i) {
        if (i < 10) {
            i = "0" + i;
        }
        return i;
    }
</script>