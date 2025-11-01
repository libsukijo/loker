   <!-- meta -->
   <?php $this->load->view('dashboard/template/meta') ?>
   <!-- end meta -->

   <!-- navbar -->
   <?php $this->load->view('dashboard/template/navbar') ?>
   <!-- end navbar -->
   <!-- sidebar -->
   <?php $this->load->view('dashboard/template/sidebar') ?>
   <!-- end sidebar -->
   <style>
       table {
           width: 100%;
       }

       tr {
           margin-bottom: 10px;
       }

       h2 {
           display: block;
           font-size: 24px;
           margin-block-start: 0.83em;
           margin-block-end: 0.83em;
           margin-inline-start: 0px;
           margin-inline-end: 0px;
           font-weight: bold;
           margin: 0px 0px 0px 0px;
           padding: 0px;
       }
   </style>
   <div class="content-wrapper">
       <div class="content-header">
           <div class="container-fluid">
               <div class="row mb-2">
                   <div class="col-sm-6">
                       <h1 class="m-0">REALTIME TRANSAKSI LOKER</h1> <span>Realtime akan diload setiap 2 detik sekali</span>
                   </div>
                   <div class="col-sm-6">
                       <ol class="breadcrumb float-sm-right">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item">Transaksi</li>
                           <li class="breadcrumb-item"><?php echo $page ?></li>
                       </ol>
                   </div>
               </div>
           </div>
       </div>

       <section class="content">
           <div class="container-fluid">
               <div class="row">
                   <div class="col-12">
                       <div class="card">
                           <!-- /.card-header -->
                           <div class="card-body">
                               <input type="hidden" id="total_data">
                               <table id="newTable" class="table table-striped">
                                   <tbody>
                                   </tbody>
                               </table>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </section>
   </div>



   <!-- footer -->
   <?php $this->load->view('dashboard/template/footer') ?>
   <!-- end footer -->
   </div>
   <!-- js -->
   <?php $this->load->view('dashboard/template/js') ?>
   <!-- end js -->

   <script type="text/javascript">
       $(document).ready(function() {
           $.ajax({
               type: "GET",
               url: "<?php echo base_url('admin/transaksi/getLog/') ?>",
               dataType: "JSON",
               success: function(response) {
                   $("#total_data").val(response.total_row);
                   var data = response.data;
                   for (i = 0; i <= response.data.length - 1; i++) {
                       $('#newTable > tbody:last').after(data[i]);
                   }
               },
           });

           setInterval(checker, '2000');

           function checker() {
               $.ajax({
                   type: "POST",
                   url: "<?php echo base_url('admin/transaksi/getLog') ?>",
                   dataType: "JSON",
                   success: function(response) {
                       var current_total = $('#total_data').val();
                       if (response.total_row != current_total) {
                           $('#newTable').children('tr').remove();
                           var data = response.data;
                           for (i = 0; i <= data.length - 1; i++) {
                               $('#newTable > tbody:last').after(data[i]);
                           }

                       }

                   },
               });
           }


       });
   </script>