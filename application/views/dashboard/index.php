   <!-- meta -->
   <?php $this->load->view('dashboard/template/meta')?>
   <!-- end meta -->
       <!-- navbar -->
       <?php $this->load->view('dashboard/template/navbar')?>
       <!-- end navbar -->
       <!-- sidebar -->
       <?php $this->load->view('dashboard/template/sidebar')?>

     <!-- Content Wrapper. Contains page content -->

      <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                       <!-- Content Header (Page header) -->
             <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">Dashboard</h1>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Dashboard</li>
                                </ol>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
						<div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Statistik Pengunjung</h5>

                                    <div class="card-tools">

                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="text-center">
                                                <strong></strong>
                                            </p>

                                            <div class="chart">
                                                <!-- Sales Chart Canvas -->
                                                <canvas id="salesChart1" height="180" style="height: 180px"></canvas>
                                            </div>
                                            <!-- /.chart-responsive -->
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-4">
											<div class="table-responsive">
                                        <table class="table m-0">
                                            <thead>
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <th>Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody id="jumlah">
                                            </tbody>
                                        </table>
                                    </div>


                                        </div>
                                        <!-- /.col -->
                                    </div>
                                    <!-- /.row -->
                                    <div>
                                    <div class="row">
                                     <div class="col-12">
                                          <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Peminjaman Locker</h3>
                                            </div>
                                            <div class="card-body">
                                                <table id="example2" class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Nama Mahasiswa</th>
                                                        <th>Nim</th>
                                                        <th>No Loker</th>
                                                        <th>tgl pinjam</th>
                                                        <th>tgl kembali</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                     </div>
                                  </div>
                                    </div>
                                </div>
                                <!-- ./card-body -->
                                <div class="card-footer">

                                </div>
                                <!-- /.card-footer -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                    </div>
                    <!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->
           </section>
            <!-- /.row -->
          </div>
          <!--/. container-fluid -->
        </section>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->

      <!-- footer -->
        <?php $this->load->view('dashboard/template/footer')?>
       <!-- end footer -->


    <!-- js -->
   <?php $this->load->view('dashboard/template/js')?>
   <!-- end js -->

   <script src="<?php echo base_url('assets/plugins/canvas/') ?>assets/script/jquery.canvasjs.min.js"></script>
   <script type="text/javascript">

         //datatables
         table = $('#example2').DataTable({

            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            "searching":false,

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('admin/dashboard/transaksi_kembali_list') ?>",
                "type": "POST"
            },

            //Set column definition initialisation properties.
            "columnDefs": [{
                    "targets": [0], //first column
                    "orderable": false, //set not orderable
                },
                {
                    "targets": [-1], //last column
                    "orderable": false, //set not orderable
                },

            ],

            });

  window.onload = function () {


const month = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Augustus","September","Oktober","November","Desember"];


$.ajax({
	type: "get",
     url: "<?php echo base_url('admin/dashboard/getPeminjam') ?>",
    dataType: "json",
	success: function (response) {


		if(response){
			var data1 =[];
			var tes=[];
			for (var i = 0; i < response.data.length; i++) {
				//data untuk cart
				data1[i] = parseInt(response.data[i].jumlah);
				var date2=new Date(response.data[i].tgl);
				tes[i] = date2.getDate()+' '+month[date2.getMonth()];
				//end

				$('#jumlah').append('<tr><td>'+date2.getDate()+' '+month[date2.getMonth()]+'</td><td>'+parseInt(response.data[i].jumlah)+'</td></tr>')
			}
			var salesChartCanvas=$('#salesChart1').get(0).getContext('2d');
		    var salesChartData={
				labels:tes,
				datasets:[{
					label:'Pengunjung',
					backgroundColor:'rgba(60,141,188,0.9)',
					borderColor:'rgba(60,141,188,0.8)',
					pointRadius:true,
					pointColor:'#3b8bba',
					pointStrokeColor:'yellow',
					pointHighlightFill:'#fff',
					pointHighlightStroke:'rgba(60,141,188,1)',
					data:data1
					}],
					options: {
					plugins: {
						filler: {
							propagate: true
						}
					}
				}

					}

			var salesChartOptions={maintainAspectRatio:false,responsive:true,legend:{display:true},scales:{xAxes:[{gridLines:{display:false}}],yAxes:[{gridLines:{display:false}}]}}
			var salesChart=new Chart(salesChartCanvas,{type:'line',data:salesChartData,options:salesChartOptions})
					}

	}
});

}
</script>
