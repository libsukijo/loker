<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- STACKED BAR CHART -->
                <div class="card" style="background-color:#0f0f0f">
                    <div class="card-body chart_header">
                        <div id="chart_element"></div>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</section>

</div>
</div>
</body>
<script src=" <?php echo base_url('assets/plugins') ?>/jquery/jquery.min.js"></script>
<script src="<?php echo base_url('assets/plugins') ?>/jquery-ui/jquery-ui.min.js"></script>
<script src="<?php echo base_url('assets/plugins') ?>/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url('assets/template/') ?>dist/js/adminlte.js?v=3.2.0"></script>

<script src="<?php echo base_url('assets/template/') ?>dist/js/pages/dashboard.js"></script>
<script src="<?php echo base_url('assets/plugins') ?>/chart.js/Chart.min.js"></script>

<!-- AdminLTE App -->
<script src="<?php echo base_url('assets/template/') ?>dist/js/adminlte.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/plugins/canvas/') ?>assets/script/jquery.canvasjs.min.js"></script>
<script>


    $(document).ready(function() {
        setInterval("getStatistik()", 1000);
    });

    function getStatistik() {
        //pengumuman()
        $.ajax({
            type: "get",
            url: "<?php echo base_url('home/getStatistik') ?>",
            dataType: "json",
            success: function(response) {

                if ($('#chart1').length) {
                    $('#chart1').remove();
            }
                if (response) {
                    var data = [];
                    for (var i = 0; i < response.data.length; i++) {
                        data[i] = {
                            label: response.data[i].fakultas,
                            y: response.data[i].jumlah,
                            color: response.data[i].color,
                            indexLabel: "{y}",
                            indexLabelFontColor: "white",
                            indexLabelBackgroundColor: "black" 
                        };
                    }

                    //Better to construct options first and then pass it as a parameter
                    var options = {
                        title: {
                            text: "STATISTIK PEMINJAMAN PER HARI INI",
                            fontColor: "white",
                            fontFamily: "sans-serif",
                            fontWeight: "bold",
                            horizontalAlign: "center",

                        },
                        axisX: {
                            labelMaxWidth: 85,
                            labelFontColor: "white",
                            fontSize: 30,
                            fontFamily: "sans-serif",
                            fontWeight: "bold",
                            horizontalAlign: "center",
                        },
                        axisY: {
                            labelFontColor: "white",
                            fontSize: 30,
                            fontFamily: "sans-serif",
                            fontWeight: "bold",

                        },
                        backgroundColor: "#0f0f0f",
                        data: [{
                            // Change type to "doughnut", "line", "splineArea", etc.
                            type: "column",
                            dataPoints: data
                        }]
                    };

                    $(".chart_header").append('<div id="chart_element"><div class="chart"><div id="chartContainer" style="height: 450px; width: 100%; color:white "></div></div></div>');
                    $("#chartContainer").CanvasJSChart(options);
                }    }
        });

    }

    function pengumuman() {

        var today = new Date();
        var hr = today.getHours();
        var min = today.getMinutes();
        var d = checkTime(hr) + ':' + checkTime(min);
        var hr_ini = today.getDay();

        //sabtu tidak ada istirahat
        if (hr_ini !== 6) {
            var awal = '12:10';
            var akhir = '12:50';

            //jam istirahat hari jumat
            if (hr_ini == 5) {
                var awal = '11:30';
                var akhir = '12:50';
            }

            if (awal < d && akhir > d) {
                window.location = "<?php echo base_url('pengumuman') ?>";
            }

        }




        }


</script>