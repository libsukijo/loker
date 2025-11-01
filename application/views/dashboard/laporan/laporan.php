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
                       <h1 class="m-0"> Laporan Jumlah Pengujung</h1>
                   </div>
                   <div class="col-sm-6">
                       <ol class="breadcrumb float-sm-right">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item active">Laporan Pengujung</li>
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
                           <div class="card-body">
                               <div class="panel panel-default">
                                   <div class="panel-body">
                                       <div class="row">
                                           <div class="col-md-12">
                                               <div class="card card-primary">
                                                   <div class="card-header">
                                                       <h3 class="card-title">Search</h3>
                                                   </div>

                                                   <div class="card-body">
                                                       <form id="form-filter">
                                                           <div class="row">
                                                               <div class="col-md-6">
                                                               <div class="form-group">
                                                                <label>Tanggal:</label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">
                                                                            <i class="far fa-calendar-alt"></i>
                                                                            </span>
                                                                        </div>
                                                                    <input type="text" class="form-control float-right" id="reservation" fdprocessedid="zj90m">
                                                                </div>
                                                                </div>
                                                               </div>

                                                           </div>
                                                       </form>
                                                       <div class="card-footer">
                                                           <button type="button" id="btn-filter" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
                                                           <button type="button" id="btn-reset" class="btn btn-default">Reset</button>
                                                           <button type="button" id="btn-excel" class="btn btn-success"><i class="fas fa-file-excel"></i> Export Execl</button>
                                                       </div>

                                                   </div>
                                               </div>

                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                           <!-- /.card-header -->
                           <div class="card-body" id="table">
                           </div>
                           <!-- /.card-body -->
                       </div>
                       <!-- /.card -->

                   </div>
                   <!-- /.col -->
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
           $("#btn-filter").click(function() {

               if ($('#data_report').length) {
                   $('#data_report').remove();
               }

               if($('#reservation').val() == ''){
                   alert('Tanggal Harus Diisi');
                   return false;
               }

               var tanggal = $('#reservation').val();

               $.ajax({
                   type: "post",
                   url: "<?php echo base_url('admin/laporan/getData') ?>",
                   data:{tanggal:tanggal},
                   success: function(response) {
                       $('#table').append(response);
                   }
               });
           });

       });

       

       $('#btn-reset').click(function() { //button reset event click
               $('#form-filter')[0].reset();
        });

        $('#btn-excel').click(function() { //button reset event click
            if($('#reservation').val() == ''){
                   alert('Tanggal Harus Diisi');
                   return false;
            }
            var tanggal = $("#reservation").val();
            location.href = "<?= base_url() ?>admin/laporan/export_excel?tanggal="+ tanggal;
        });


         //Date picker

     $('#reservation').daterangepicker();
     
  
 
   </script>