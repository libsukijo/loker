   <!-- meta -->
   <?php $this->load->view('dashboard/template/meta')?>
   <!-- end meta -->

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
                       <h1 class="m-0"> Monitoring Peminjaman loker Hari Ini</h1>
                   </div>
                   <div class="col-sm-6">
                       <ol class="breadcrumb float-sm-right">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">loker</a></li>
                           <li class="breadcrumb-item active">Monitoring</li>
                       </ol>
                   </div>
               </div>
           </div>
       </div>

       <section class="content">
           <div class="card">
               <div class="card-header">
                   <h3 class="card-title">Total Kunci Loker Yang Dipinjam : <?php echo $getTotalTransaksiHariIni ?></h3>
                   <br />
                   <h3 class="card-title">Total Kunci Loker Yang Belum Dikembalikan :
                       <?php echo $getTransaksiHariIniBelumKembali ?>
                   </h3>
               </div>

               <div class="card-body">
                   <table class="table table-bordered">
                       <thead>
                           <tr>
                               <th style="width: 10px">#</th>
                               <th>NIM</th>
                               <th>Nama</th>
                               <th>No Barcode</th>
                               <th>Tgl Pinjam</th>
                               <th>OP Pinjam</th>
                               <th>Tgl Kembali</th>
                               <th>OP Kembali</th>
                               <th style="width: 40px">Status</th>
                           </tr>
                       </thead>
                       <tbody>
                           <?php $i=1; foreach ($getTransaksiHariIni as $value) { ?>
                           <tr>
                               <td><?php echo $i ?></td>
                               <td><?php echo $value['nim'] ?></td>
                               <td><?php echo $value['nama'] ?></td>
                               <td><?php echo $value['no_loker'] ?></td>
                               <td><?php echo $value['tgl_pinjam'] ?></td>
                               <td><?php echo $value['created_by'] ?></td>
                               <td><?php echo $value['tgl_kembali'] ?></td>
                               <td><?php echo $value['updated_by'] ?></td>
                               <?php if ($value['tgl_kembali'] == NULL) { ?>
                               <td><span class="badge bg-danger">Belum Kembali</span></td>
                               <?php }else{ ?>
                               <td><span class="badge bg-success">Sudah Kembali</span></td>
                               <?php } ?>

                           </tr>
                           <?php $i++; } ?>
                       </tbody>
                   </table>
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

   <script type="text/javascript">
$(document).ready(function() {

   //setTimeout(function() {
    //console.log('tes');
    //location.reload();
       // }, 60000);
});

</script>