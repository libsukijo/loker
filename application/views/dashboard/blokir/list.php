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
                           <h1 class="m-0">Blokir</h1>
                       </div>
                       <div class="col-sm-6">
                           <ol class="breadcrumb float-sm-right">
                               <li class="breadcrumb-item"><a href="#">Home</a></li>
                               <li class="breadcrumb-item active">Blokir</li>
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
                                   <br />
                                   <button class="btn btn-success" onclick="add_blokir()"><i class="fas fa-plus"></i> Add Blokir</button>
                                   <button class="btn btn-default" id="btn-reset"><i class="fas fa-redo"></i> Reload</button>
                                   <br />
                                   <br />
                                   <table id="table" class="table table-bordered table-striped" cellspacing="0" width="100%">
                                       <thead>
                                           <tr>
                                               <th>NIM</th>
                                               <th>Tanggal</th>
                                               <th>Status</th>
                                               <th style="width:150px;">Action</th>
                                           </tr>
                                       </thead>
                                       <tbody>
                                       </tbody>

                                       <tfoot>
                                           <tr>
                                               <th>NIM</th>
                                               <th>Tanggal</th>
                                               <th>Status</th>
                                               <th style="width:150px;">Action</th>
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


           $('.select2bs4').select2({
               theme: 'bootstrap4',
           })

           //datatables
           table = $('#table').DataTable({

               "processing": true, //Feature control the processing indicator.
               "serverSide": true, //Feature control DataTables' server-side processing mode.
               "order": [], //Initial no order.

               // Load data for the table's content from an Ajax source
               "ajax": {
                   "url": "<?php echo site_url('admin/blokir/ajax_list') ?>",
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


       function add_blokir() {
           save_method = 'add';
           $('#form')[0].reset(); // reset form on modals
           $('input').removeClass('is-invalid'); // clear error class
           $('select').removeClass('is-invalid'); // clear error class
           $('.error').empty(); // clear error string
           $('#modal_form').modal('show'); // show bootstrap modal
           $('.modal-title').text('Add Blokir'); // Set Title to Bootstrap modal title
       }

       function remove_blokir(no_mhs) {
           if (confirm('are you sure remove blokir')) {
               //ajax delete data to database
               $.ajax({
                   url: "<?php echo site_url('admin/blokir/ajax_delete') ?>/" + no_mhs,
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
               url = "<?php echo site_url('admin/blokir/ajax_add') ?>";
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
                   console.log(data);

                   if (data.status) {
                       $('#modal_form').modal('hide');
                       $('#table').DataTable().ajax.reload();
                       $('#btnSave').text('save'); //change button text
                       $('#btnSave').attr('disabled', false); //set button enable 

                   } else {
                       for (var i = 0; i < data.input_error.length; i++) {
                           $('[name="' + data.input_error[i] + '"]').addClass('is-invalid'); //select parent twice to select div form-group class and add has-error class
                           $('[name="' + data.input_error[i] + '"]').next().next().addClass('invalid-feedback').text(data.error_string[i]); //select span help-block class set text error string

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

                   <h3 class="modal-title">Form Blokir</h3>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               </div>
               <div class="modal-body form">
                   <form action="#" id="form" class="form-horizontal">
                       <input type="hidden" value="" name="id" />
                       <div class="form-body">
                           <div class="form-group">
                               <label for="angkatan">Angkatan</label>
                               <select name="angkatan" class="form-control select2bs4a" id="angkatan" onchange="angkatan1()">
                                   <option value="">--Angkatan--</option>
                                   <?php for ($i = 2022; $i > 1998; $i--) { ?>
                                       <option value="<?php echo $i ?>"> <?php echo $i ?></option>
                                   <?php } ?>
                               </select>

                           </div>
                           <div class="form-group">
                               <label for="mahasiswa">Mahasiswa</label>
                               <select name="mahasiswa" class="form-control select2bs4" id="mahasiswa">
                                   <option value="">--Select Mahasiswa--</option>


                               </select>
                               <span class="error"></span>
                           </div>
                           <div class="form-group">
                               <label for="angkatan">Status</label>
                               <select name="status" class="form-control select2bs4a" id="status">
                                   <option value="">--Select Status--</option>
                                   <option value="bebas">Bebas Pustaka</option>
                                   <option value="kunci">kunci</option>

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

   <script>
       function angkatan1() {

           $('#mahasiswa').val('');
           var angkatan = $("#angkatan").val();
           if ($("#angkatan").val()) {
               $.ajax({
                   type: "get",
                   url: '<?php echo base_url('admin/blokir/anggotaList/') ?>' + angkatan,
                   dataType: "JSON",
                   success: function(response) {
                       if (response) {
                           $('.select2bs4').select2({
                               theme: 'bootstrap4',
                               data: response.data
                           })
                       }
                   }
               });
           } else {
               $('.select2bs4').val(null).trigger('change');


           }
       }
       $('.select2bs4a').select2({
           theme: 'bootstrap4',
       })
   </script>