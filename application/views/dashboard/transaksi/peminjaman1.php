   <!-- meta -->
   <?php $this->load->view('dashboard/template/meta') ?>
   <!-- end meta -->

   <!-- navbar -->
   <?php $this->load->view('dashboard/template/navbar') ?>
   <!-- end navbar -->
   <!-- sidebar -->
   <?php $this->load->view('dashboard/template/sidebar') ?>
   <!-- end sidebar -->
   <div class="content-wrapper">

       <div class="content-header">
           <div class="container-fluid">
               <div class="row mb-2">
                   <div class="col-sm-6">
                       <h1 class="m-0">Form Peminjaman</h1>
                   </div>
                   <div class="col-sm-6">
                       <ol class="breadcrumb float-sm-right">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item">Transaksi</li>
                           <li class="breadcrumb-item">Peminjaman</li>
                       </ol>
                   </div>
               </div>
           </div>
       </div>

       <section class="content">
           <div class="container-fluid">
               <div class="row">
                   <div class="col-md-6">
                       <!-- general form elements -->
                       <div class="card card-primary">
                           <div class="card-header">
                               <h3 class="card-title"></h3>
                           </div>
                           <!-- /.card-header -->
                           <!-- form start -->
                           <form action="#" id="form" method="POST">
                               <div class="card-body">
                                   <div class="form-group">
                                       <label for="nim"> Nim / Barcode </label>
                                       <input type="text" class="form-control" id="nim" name="nim" placeholder="Nim / Barcode" autocomplete="off">
                                       <span class="error"></span>
                                   </div>
                                   <div class="form-group">
                                       <label for="loker">No Loker</label>
                                       <input type="text" class="form-control" name="no_loker" id="loker" placeholder="Loker">
                                       <span class="error"></span>
                                   </div>
                               </div>
                               <!-- /.card-body -->

                               <div class="card-footer">
                                   <button type="button" onclick="save()" class="btn btn-primary">Submit</button>
                               </div>
                           </form>
                       </div>
                       <!-- /.card -->


                   </div>
                   <div class="col-md-6">
                       <div id="info">
                           <!-- Profile Image -->
                           <div class="card card-success">
                               <div class="card-header">
                                   <h3 class="card-title"></h3>
                               </div>
                               <div class="card-body box-profile">
                                   <div class="text-center">
                                       <img class="profile-user-img img-fluid img-circle" src="<?php echo base_url('assets/images/') ?>user/user4-128x128.jpg" alt="User profile picture">
                                   </div>
                                   <br />
                                   <ul class="list-group list-group-unbordered mb-3">
                                       <li class="list-group-item">
                                           <b>NAMA :</b> <a class="float-right">Prayuda Wirawan</a>
                                       </li>
                                       <li class="list-group-item">
                                           <b>NIM :</b> <a class="float-right">5432232323</a>
                                       </li>
                                       <li class="list-group-item">
                                           <b>FAKULTAS</b> <a class="float-right">13,287</a>
                                       </li>
                                   </ul>
                               </div>
                               <!-- /.card-body -->
                           </div>
                           <!-- /.card -->
                       </div>

                       <!-- /.card -->
                   </div>

                   <!-- /.col -->
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
           //    $('#info').hide();
       });

       function save() {
           // ajax adding data to database
           var formData = new FormData($('#form')[0]);
           $.ajax({
               type: "POST",
               url: "<?php echo base_url('admin/transaksi/save_peminjaman') ?>",
               data: formData,
               contentType: false,
               processData: false,
               dataType: "JSON",
               success: function(data) {
                   console.log(data);
                   if (data.status) {
                       $('#info').show();
                       //    $('#modal_form').modal('hide');
                       //    $('#table').DataTable().ajax.reload();
                       //    $('#btnSave').text('save'); //change button text
                       //    $('#btnSave').attr('disabled', false); //set button enable 

                   } else {
                       for (var i = 0; i < data.input_error.length; i++) {
                           $('[name="' + data.input_error[i] + '"]').addClass('is-invalid'); //select parent twice to select div form-group class and add has-error class
                           $('[name="' + data.input_error[i] + '"]').next().addClass('invalid-feedback').text(data.error_string[i]); //select span help-block class set text error string

                       }

                   }

                   //    $('#btnSave').text('save'); //change button text
                   //    $('#btnSave').attr('disabled', false); //set button enable 
               },
               error: function(jqXHR, textStatus, errorThrown) {
                   alert('Error adding / update data');
                   //    $('#btnSave').text('save'); //change button text
                   //    $('#btnSave').attr('disabled', false); //set button enable 

               }
           });

       }


       function pageRedirect() {
           window.location.href = "<?php echo base_url('pinjam') ?>";
       }
   </script>