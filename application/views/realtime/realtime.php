<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('assets/images/logo-perpus.png') ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>
<style>
    .judul {
        background-color: #000000;
        border-bottom: 5px solid #FFC107;
        color: #FFFFFF;
        font-family: Verdana, Geneva, Tahoma, sans-serif
    }

    .judul span {
        font-size: 20px;
    }

    .clock {
        margin: 0;
        position: absolute;
        top: 50%;
        -ms-transform: translateY(-50%);
        transform: translateY(-50%);
    }
</style>


<body onload="startTime()">
    <div class="container-fluid">
        <div class="row judul">
            <div class="col-md-1">
                <img src="<?php echo base_url('assets/images/logo-perpus.png') ?>" class="rounded float-right" alt="" style="height:100px ;">
            </div>
            <div class="col-md-8" id="judul">
                <h1>REALTIME</h1>
                <span>APLIKASI MONITORING PEMINJAMAN KUNCI LOKER</span>
            </div>
            <div class="col-md-3">
                <h1>
                    <div class="clock"></div>
                </h1>

            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <input type="hidden" id="total_data">
                <table id="Table" class="table">
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</body>

<script src="<?php echo base_url('assets/realtime') ?>/jquery-3.2.1.slim.min.js"></script>
<script src="<?php echo base_url('assets/realtime') ?>/popper.min.js"></script>
<script src="<?php echo base_url('assets/realtime') ?>/bootstrap.min.js"></script>
<script src="<?php echo base_url('assets/plugins') ?>/jquery/jquery.min.js"></script>

<script src="<?php echo base_url('assets/plugins') ?>/jquery-ui/jquery-ui.min.js"></script>

<script type="text/javascript">
    function startTime() {
        const today = new Date();
        let h = today.getHours();
        let m = today.getMinutes();
        let s = today.getSeconds();
        m = checkTime(m);
        s = checkTime(s);

        $('.clock').text(h + ':' + m + ':' + s);
        $('#date').text(today.getFullYear() + '/' + (today.getMonth() + 1) + '/' + today.getDate());
        setTimeout(startTime, 1000);
    }

    function checkTime(i) {
        if (i < 10) {
            i = "0" + i
        }; // add zero in front of numbers <script 10
        return i;
    }
    $(document).ready(function() {
        $.ajax({
            type: "GET",
            url: "<?php echo base_url('realtime/getLog/') ?>",
            dataType: "JSON",
            success: function(response) {
                $("#total_data").val(response.total_row);
                var data = response.data;
                for (i = 0; i <= response.data.length - 1; i++) {
                    $('#Table > tbody:last').after(data[i]);
                }
            },
        });
        setInterval(checker, '2000');

        function checker() {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('realtime/getLog/') ?>",
                dataType: "JSON",
                success: function(response) {
                    var current_total = parseInt($('#total_data').val());
                    var total_now = parseInt(response.total_row);
                    console.log(current_total);
                    console.log(total_now);
                    if (total_now !== current_total) {
                        $('#Table').children('tr').remove();
                        var data = response.data;
                        for (i = 0; i <= data.length - 1; i++) {
                            $('#Table > tbody:last').after(data[i]);
                        }
                        $("#total_data").val(response.total_row);

                    }

                },
            });
        }
    });
</script>

</html>