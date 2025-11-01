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
                           <h1 class="m-0">Kunci</h1>
                       </div>
                       <div class="col-sm-6">
                           <ol class="breadcrumb float-sm-right">
                               <li class="breadcrumb-item"><a href="#">Home</a></li>
                               <li class="breadcrumb-item active">Kunci</li>
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
                                   <br />
                                   <button class="btn btn-success" onclick="add_kunci()"><i class="fas fa-plus"></i> Add</button>
                                   <button class="btn btn-default" id="btn-reset"><i class="fas fa-redo"></i> Reload</button>
                                   <br />
                                   <br />
                                   <table id="table" class="table table-bordered table-striped" cellspacing="0" width="100%">
                                       <thead>
                                           <tr>
                                               <th>Nomer Loker</th>
                                               <th style="width:150px;">Action</th>
                                           </tr>
                                       </thead>
                                       <tbody>
                                       </tbody>

                                       <tfoot>
                                           <tr>
                                               <th>Nomer Loker</th>
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


           //datatables
           table = $('#table').DataTable({

               "processing": true, //Feature control the processing indicator.
               "serverSide": true, //Feature control DataTables' server-side processing mode.
               "order": [], //Initial no order.

               // Load data for the table's content from an Ajax source
               "ajax": {
                   "url": "<?php echo site_url('admin/kunci/ajax_list') ?>",
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


       function add_kunci() {
           save_method = 'add';
           $('#form')[0].reset(); // reset form on modals
           $('input').removeClass('is-invalid'); // clear error class
           $('select').removeClass('is-invalid'); // clear error class
           $('.error').empty(); // clear error string
           $('#modal_form').modal('show'); // show bootstrap modal
           $('.modal-title').text('Add Kunci'); // Set Title to Bootstrap modal title
       }

       function edit_kunci(id) {
           save_method = 'update';

           $('#form')[0].reset(); // reset form on modals
           $('input').removeClass('is-invalid'); // clear error class
           $('select').removeClass('is-invalid'); // clear error class

           //Ajax Load data from ajax
           $.ajax({
               url: "<?php echo site_url('admin/kunci/ajax_edit') ?>/" + id,
               type: "GET",
               dataType: "JSON",
               success: function(data) {

                   $('[name="id"]').val(data.id);
                   $('[name="no_loker"]').val(data.no_loker);
                   $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                   $('.modal-title').text('Edit Kunci'); // Set title to Bootstrap modal title


               },
               error: function(jqXHR, textStatus, errorThrown) {
                   alert('Error get data from ajax');
               }
           });

       }

       function cetak_barcode(id) {
        if (confirm('apakah anda yakin ingin mencetak Barcode')) {

            window.open("<?php echo base_url('admin/kunci/cetakBarcode') ?>/"+id, '_blank');

           }
       }


       function remove_kunci(id) {
           if (confirm('apakah anda yakin ingin menghapus data ini')) {
               //ajax delete data to database
               $.ajax({
                   url: "<?php echo site_url('admin/kunci/ajax_delete') ?>/" + id,
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
               url = "<?php echo site_url('admin/kunci/ajax_add') ?>";
           } else {
               url = "<?php echo site_url('admin/kunci/ajax_update') ?>";
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

                   <h3 class="modal-title">Form Kunci</h3>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               </div>
               <div class="modal-body form">
                   <form action="#" id="form" class="form-horizontal">
                       <input type="hidden" value="" name="id" />
                       <div class="form-body">
                           <div class="form-group">
                               <label for="mahasiswa">Nomer Loker</label>
                               <input class="form-control" name="no_loker" id="no_loker">
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
       $('.select2bs4').select2({
           theme: 'bootstrap4'
       })

       $('.select2bs4a').select2({
           theme: 'bootstrap4'
       })
   </script>