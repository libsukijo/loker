   <!-- meta -->
   <?php $this->load->view('dashboard/template/meta') ?>
   <!-- end meta -->
   <div class="wrapper">

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
                           <h1 class="m-0">Anggota</h1>
                       </div>
                       <div class="col-sm-6">
                           <ol class="breadcrumb float-sm-right">
                               <li class="breadcrumb-item"><a href="#">Home</a></li>
                               <li class="breadcrumb-item active">Anggota</li>
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
                               <div class="card-header">
                                   <h3 class="card-title">DataTable with minimal features & hover style</h3>
                               </div>
                               <!-- /.card-header -->
                               <div class="card-body">
                                   <br />
                                   <button class="btn btn-success" onclick="add_person()"><i class="fas fa-plus"></i> Add</button>
                                   <button class="btn btn-default" id="btn-reset"><i class="fas fa-redo"></i> Reload</button>
                                   <button class="btn btn-danger" onclick="bulk_delete()"><i class="far fa-trash-alt"></i> Delete</button>
                                   <br />
                                   <br />
                                   <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                       <thead>
                                           <tr>
                                               <th>No Mhs</th>
                                               <th>Nama</th>
                                               <th>Angkatan</th>
                                               <th style="width:150px;">Action</th>
                                           </tr>
                                       </thead>
                                       <tbody>
                                       </tbody>

                                       <tfoot>
                                           <tr>
                                               <th>No Mhs</th>
                                               <th>Nama</th>
                                               <th>Angkatan</th>
                                               <th>Action</th>
                                           </tr>
                                       </tfoot>
                                   </table>
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
       <?php $this->load->view('dashboard/template/footer') ?>
       <!-- end footer -->
   </div>
   <!-- js -->
   <?php $this->load->view('dashboard/template/js') ?>
   <!-- end js -->

   <script type="text/javascript">
       $(document).ready(function() {
           var save_method; //for save method string
           var table;
           var base_url = '<?php echo base_url(); ?>';


           //datatables
           table = $('#table').DataTable({

               "processing": true, //Feature control the processing indicator.
               "serverSide": true, //Feature control DataTables' server-side processing mode.
               "order": [], //Initial no order.

               // Load data for the table's content from an Ajax source
               "ajax": {
                   "url": "<?php echo site_url('admin/anggota/ajax_list') ?>",
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

           $('#btn-reset').click(function() { //button reset event click
               $('#table').DataTable().ajax.reload();
           });

       });

       function add_person() {
           save_method = 'add';
           $('#form')[0].reset(); // reset form on modals
           $('input').removeClass('is-invalid'); // clear error class
           $('select').removeClass('is-invalid'); // clear error class
           $('.error').empty(); // clear error string
           $('#modal_form').modal('show'); // show bootstrap modal
           $('.modal-title').text('Add Anggota'); // Set Title to Bootstrap modal title
       }

       function edit_anggota(no_mhs) {
           save_method = 'update';

           $('#form')[0].reset(); // reset form on modals
           $('input').removeClass('is-invalid'); // clear error class
           $('select').removeClass('is-invalid'); // clear error class

           //Ajax Load data from ajax
           $.ajax({
               url: "<?php echo site_url('admin/anggota/ajax_edit') ?>/" + no_mhs,
               type: "GET",
               dataType: "JSON",
               success: function(data) {

                   $('[name="nama"]').val(data.nama);
                   $('[name="no_mhs"]').val(data.no_mhs);
                   $('[name="angkatan"]').val(data.angkatan);
                   $('[name="fakultas"]').val(data.kd_fakultas);
                   $('[name="status"]').val(data.status);
                   $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                   $('.modal-title').text('Edit Anggota'); // Set title to Bootstrap modal title


               },
               error: function(jqXHR, textStatus, errorThrown) {
                   alert('Error get data from ajax');
               }
           });

       }

       function delete_anggota(no_mhs) {
           if (confirm('are you sure delete this data')) {
               //ajax delete data to database
               $.ajax({
                   url: "<?php echo site_url('admin/anggota/ajax_delete') ?>/" + no_mhs,
                   type: "POST",
                   dataType: "JSON",
                   success: function(data) {
                       //if success reload ajax table
                       $('#modal_form').modal('hide');
                       $('#table').DataTable().ajax.reload();
                   },
                   error: function(jqXHR, textStatus, errorThrown) {
                       alert('Error deleting data');
                   }
               });
           }
       }


       function save() {
           $('#btnSave').text('saving...'); //change button text
           $('#btnSave').attr('disabled', true); //set button disable 
           var url;

           if (save_method == 'add') {
               url = "<?php echo site_url('admin/anggota/ajax_add') ?>";
           } else {
               url = "<?php echo site_url('admin/anggota/ajax_update') ?>";
           }

           // ajax adding data to database
           var formData = new FormData($('#form')[0]);
           $.ajax({
               url: url,
               type: "POST",
               data: formData,
               contentType: false,
               processData: false,
               dataType: "JSON",
               success: function(data) {

                   if (data.status) {
                       $('#modal_form').modal('hide');
                       $('#table').DataTable().ajax.reload();
                       $('#btnSave').text('save'); //change button text
                       $('#btnSave').attr('disabled', false); //set button enable 

                   } else {
                       for (var i = 0; i < data.input_error.length; i++) {
                           $('[name="' + data.input_error[i] + '"]').addClass('is-invalid'); //select parent twice to select div form-group class and add has-error class
                           $('[name="' + data.input_error[i] + '"]').next().addClass('invalid-feedback').text(data.error_string[i]); //select span help-block class set text error string
                       }

                   }

                   $('#btnSave').text('save'); //change button text
                   $('#btnSave').attr('disabled', false); //set button enable 

               },
               error: function(jqXHR, textStatus, errorThrown) {
                   alert('Error adding / update data');
                   $('#btnSave').text('save'); //change button text
                   $('#btnSave').attr('disabled', false); //set button enable 


               }
           });



       }
   </script>

   <!-- Bootstrap modal -->
   <div class="modal fade" id="modal_form" role="dialog">
       <div class="modal-dialog">
           <div class="modal-content">
               <div class="modal-header">

                   <h3 class="modal-title">Form Anggota</h3>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               </div>
               <div class="modal-body form">
                   <form action="#" id="form" class="form-horizontal">
                       <input type="hidden" value="" name="id" />
                       <div class="form-body">
                           <div class="form-group">
                               <label for="No Mahasiswa">No Mahasiswa</label>
                               <input type="number" name="no_mhs" class="form-control" id="no_mhs" placeholder="">
                               <span class="error"></span>
                           </div>
                           <div class="form-group">
                               <label for="nama">Nama</label>
                               <input type="text" name="nama" class="form-control" id="nama" placeholder="">
                               <span class="error"></span>
                           </div>
                           <div class="form-group">
                               <label for="fakultas">Fakultas</label>
                               <select name="fakultas" class="form-control" id="fakultas">
                                   <option value="">--Select Fakultas--</option>
                                   <?php foreach ($fakultas as $fak) { ?>
                                       <option value="<?php echo $fak->kd_fakultas ?>"><?php echo $fak->fakultas ?> - <?php echo $fak->jurusan ?></option>
                                   <?php } ?>

                               </select>
                               <span class="error"></span>
                           </div>
                           <div class="form-group">
                               <label for="angkatan">angkatan</label>
                               <select name="angkatan" class="form-control" id="angkatan">
                                   <option value="">--Select Angkatan--</option>
                                   <?php for ($i = 2022; $i > 1989; $i--) { ?>
                                       <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                   <?php } ?>

                               </select>
                               <span class="error"></span>
                           </div>
                           <div class="form-group">
                               <label for="angkatan">Status</label>
                               <select name="status" class="form-control" id="status">
                                   <option value="">--Select Status--</option>
                                   <option value="A">Aktif</option>
                                   <option value="P">Pasif</option>
                                   <option value="BP">Bebas Pustaka</option>

                               </select>
                               <span class="error"></span>
                           </div>
                       </div>
                   </form>
               </div>
               <div class="modal-footer">
                   <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                   <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
               </div>
           </div><!-- /.modal-content -->
       </div><!-- /.modal-dialog -->
   </div><!-- /.modal -->
   <!-- End Bootstrap modal -->