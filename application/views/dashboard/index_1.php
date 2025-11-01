   <!-- meta -->
   <?php $this->load->view('dashboard/template/meta')?>
   <!-- end meta -->
   <div class="wrapper">

       <!-- navbar -->
       <?php $this->load->view('dashboard/template/navbar')?>
       <!-- end navbar -->
       <!-- sidebar -->
       <?php $this->load->view('dashboard/template/sidebar')?>
       <!-- end sidebar -->
       <div class="content-wrapper">

           <div class="content-header">
               <div class="container-fluid">
                   <div class="row mb-2">
                       <div class="col-sm-6">
                           <h1 class="m-0">Dashboard</h1>
                       </div>
                       <div class="col-sm-6">
                           <ol class="breadcrumb float-sm-right">
                               <li class="breadcrumb-item"><a href="#">Home</a></li>
                               <li class="breadcrumb-item active">Dashboard v1</li>
                           </ol>
                       </div>
                   </div>
               </div>
           </div>
           <section class="content">
                  <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                              <div class="card-header border-0">
                                <div class="d-flex justify-content-between">
                                <h2 class="card-title">Grafik Peminjaman</h2>
                                </div>
                              </div>
                            </div>
                            <div class="card-body">
                                <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                                 <script src="<?php echo base_url('assets/plugins/canvas/') ?>assets/script/jquery.canvasjs.min.js""></script>
                            </div>
							<div class="card">
									<div class="card-header border-transparent">
										<h2 class="card-title">Transaksi Terakhir</h2>
									</div>
									<!-- /.card-header -->
									<div class="card-body p-0">
										<div class="table-responsive">
										<table class="table m-0">
											<thead>
											<tr>
												<th>Nama</th>
												<th>NIM</th>
												<th>Event</th>
												<th>Tanggal</th>
												<th>Status</th>
											</tr>
											</thead>
											<tbody>
												<?php foreach ($transaksi as $value) {?>
													<tr>
															<td><?php echo $value->nama ?></td>
															<td><?php echo $value->nim ?></td>
															<td><?php echo $value->event ?></td>
															<td><?php echo $value->tgl_pinjam ?></td>
															<td><span class="badge badge-success">Berhasil</span></td>
														</tr>
												<?php }?>

											</tbody>
										</table>
										</div>
										<!-- /.table-responsive -->
									</div>
									<!-- /.card-body -->
									<div class="card-footer clearfix">
										<a href="<?php echo site_url('admin/transaksi_list') ?>" class="btn btn-sm btn-secondary float-right">View All Orders</a>
									</div>
									<!-- /.card-footer -->
								</div>
                        </div>
         
                    </div>
               </div>
           </section>

       </div>
       <!-- footer -->
       <?php $this->load->view('dashboard/template/footer')?>
       <!-- end footer -->
   </div>
   <!-- js -->
   <?php $this->load->view('dashboard/template/js')?>
   <!-- end js -->
   </body>

   </html>

   <script type="text/javascript">
window.onload = function () {

  $.ajax({
	  type: "method",
	  url: "<?php echo base_url('admin/dashboard/getPeminjam') ?>",
	  dataType: "json",
	  success: function (response) {
		  console.log(response);

		  if(response)
		  {
			var data = [];
			for (var i = 0; i < response.data.length; i++) {
				data[i] = {
					x: new Date(response.data[i].tgl_pinjam),
					y: parseInt(response.data[i].jumlah),
				};
			}

			console.log(data);

			var chart = new CanvasJS.Chart("chartContainer", {
			animationEnabled: true,
			theme: "light2",
			title:{
				text: "Grafik Peminjaman"
			},
			axisX:{
				valueFormatString: "DD MMM",
				crosshair: {
					enabled: true,
					snapToDataPoint: true
				}
			},
			axisY: {
				title: "Jumlah Peminjam",
				includeZero: true,
				crosshair: {
					enabled: true
				}
			},
			toolTip:{
				shared:true
			},
			legend:{
				cursor:"pointer",
				verticalAlign: "bottom",
				horizontalAlign: "left",
				dockInsidePlotArea: true,
				itemclick: toogleDataSeries
			},
			data: [{
				type: "line",
				showInLegend: true,
				name: "Total Visit",
				markerType: "square",
				xValueFormatString: "DD MMM, YYYY",
				color: "#F08080",
				dataPoints:data
			}]
		});
		chart.render();
		  }

	  }
  });





function toogleDataSeries(e){
	if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
		e.dataSeries.visible = false;
	} else{
		e.dataSeries.visible = true;
	}
	chart.render();
}

}
</script>