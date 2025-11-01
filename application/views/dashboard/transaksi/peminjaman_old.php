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
                       <h1 class="m-0"></h1>
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
                   <div class="col-md-7">
                       <div class="card">
                           <div class="card-header p-2">
                               <h2>Form Peminjaman</h2>
                           </div><!-- /.card-header -->
                           <div class="card-body">
                               <div id="info_message">
                                   <div id="message">
                                   </div>
                               </div>
                               <form action="#" id="form" class="form-horizontal">
                                   <div class="form-group row">
                                       <label for="nim" class="col-sm-2 col-form-label"> Nim / Barcode</label>
                                       <div class="col-sm-10">
                                           <input type="text" class="form-control" id="nim" placeholder="Nim / Barcode" name="nim" autocomplete="off">
                                           <span class=" error"></span>
                                       </div>
                                   </div>
                                   <div class=" form-group row">
                                       <label for="inputEmail" class="col-sm-2 col-form-label">No Loker</label>
                                       <div class="col-sm-10">
                                           <input type="text" class="form-control" id="no_loker" name="no_loker" placeholder="No Loker">
                                           <span class="error"></span>
                                       </div>
                                   </div>
                                   <div class="form-group row">
                                       <div class="offset-sm-2 col-sm-10">
                                           <button type="button" onclick="save()" class="btn btn-primary">Submit</button>
                                       </div>
                                   </div>
                               </form>
                           </div><!-- /.card-body -->
                       </div>
                       <!-- /.card -->
                   </div>
                   <div class="col-md-5">
                       <!-- Profile Image -->
                       <div id="info_template">
                           <!-- /.card-body -->
                       </div>
                       <!-- /.card -->
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
       $(document).ready(function() {});

       function save() {
           $('.form-contro').removeClass('is-invalid');

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
                   if (data.status == 1) {
                       $("#info_message").prepend('<div id="message"><div class="alert alert-success"><strong> Success !</strong>' + data.message + '.</div></div > ');
                       setTimeout('remove_message()', 2000);
                       if (data.data_anggota) {
                           $("#info_template").prepend('  <div class="card card-primary card-outline" id="info-anggota"><div class="card-body box-profile"><div class="text-center"><img class="profile-user-img img-fluid" src="'+data.image+'" alt="User profile picture"></div><h3 class="profile-username text-center">' + data.data_anggota.nama + '</h3><ul class="list-group list-group-unbordered mb-3"><li class="list-group-item"><b>NIM</b> <a class="float-right"> ' + data.data_anggota.no_mhs + '</a></li><li class="list-group-item"><b>Fakultas</b> <a class="float-right">' + data.data_anggota.fakultas + '</a></li></ul></div> </div>');
                       }
                       setTimeout('emptyForm()', 4000);
                   } else if (data.status == 2) {
                       $("#info_message").prepend('<div id="message"><div class="alert alert-danger">' + data.message + ' . </div></div > ');
                       setTimeout('remove_message()', 2000);

                   } else {
                       for (var i = 0; i < data.input_error.length; i++) {
                           $('[name="' + data.input_error[i] + '"]').addClass('is-invalid'); //select parent twice to select div form-group class and add has-error class
                           $('[name="' + data.input_error[i] + '"]').next().addClass('invalid-feedback').text(data.error_string[i]); //select span help-block class set text error string
                       }

                   }
               },
               error: function(jqXHR, textStatus, errorThrown) {
                   alert('Error adding / update data');
               }
           });

       }


       function emptyForm() {
           $('#no_loker').val('');
           $('#nim').val('');
           $('#info-anggota').remove();
       }

       function remove_message() {
           $("#message").remove();
       }
   </script>