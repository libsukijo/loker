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
                       <h1 class="m-0"> Tanggungan Peminjaman Kunci Loker</h1>
                   </div>
                   <div class="col-sm-6">
                       <ol class="breadcrumb float-sm-right">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item"><a href="#">Loker</a></li>
                           <li class="breadcrumb-item active">Tanggungan</li>
                       </ol>
                   </div>
               </div>
           </div>
       </div>

       <section class="content">
           <div class="card">
               <div class="card-header">
                   <h3 class="card-title">Jumlah Tanggungan Kunci Loker : <?php echo $getTotalTransaksiTanggungan ?>
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
                           </tr>
                       </thead>
                       <tbody>
                           <?php $i=1; foreach ($getTransaksiTanggungan as $value) { ?>
                           <tr>
                               <td><?php echo $i ?></td>
                               <td><?php echo $value['nim'] ?></td>
                               <td><?php echo $value['nama'] ?></td>
                               <td><?php echo $value['no_loker'] ?></td>
                               <td><?php echo $value['tgl_pinjam'] ?></td>
                               <td><?php echo $value['created_by'] ?></td>
                               <td><?php echo $value['tgl_kembali'] ?></td>
                               <td><?php echo $value['updated_by'] ?></td>
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


});