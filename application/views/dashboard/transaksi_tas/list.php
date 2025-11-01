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
                       <h1 class="m-0"> List Transaksi Peminjaman Tas</h1>
                   </div>
                   <div class="col-sm-6">
                       <ol class="breadcrumb float-sm-right">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item active">Transaksi List</li>
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
                                                                       <label>NAMA</label>
                                                                       <input type="text" class="form-control"
                                                                           placeholder="Input Nama" id="nama">
                                                                   </div>
                                                                   <div class="form-group">
                                                                       <label>NIM</label>
                                                                       <input type="text" class="form-control"
                                                                           placeholder="Input Nim" id="nim">
                                                                   </div>
                                                               </div>
                                                               <div class="col-md-6">
                                                                   <div class="form-group">
                                                                       <label>FAKULTAS</label>
                                                                       <select class="form-control" id="fakultas">
                                                                           <option value="">- Pilih Fakultas -</option>
                                                                           <?php foreach ($fakultas as $fak) {?>
                                                                           <option
                                                                               value="<?php echo $fak['fakultas'] ?>">
                                                                               <?php echo $fak['fakultas'] ?></option>
                                                                           <?php }?>
                                                                       </select>
                                                                   </div>
                                                                   <!-- Date range -->
                                                                   <div class="form-group">
                                                                       <label>TANGGAL:</label>

                                                                       <div class="input-group">
                                                                           <div class="input-group-prepend">
                                                                               <span class="input-group-text">
                                                                                   <i class="far fa-calendar-alt"></i>
                                                                               </span>
                                                                           </div>
                                                                           <input type="text" class="form-control"
                                                                               id="tanggal" autocomplete="off" value="">
                                                                       </div>
                                                                       <!-- /.input group -->
                                                                   </div>
                                                               </div>
                                                           </div>
                                                       </form>
                                                       <div class="card-footer">
                                                           <button type="button" id="btn-filter"
                                                               class="btn btn-primary"><i class="fas fa-search"></i>
                                                               Filter</button>
                                                           <button type="button" id="btn-reset"
                                                               class="btn btn-default">Reset</button>
                                                       </div>

                                                   </div>
                                               </div>

                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                           <!-- /.card-header -->
                           <div class="card-body">
                               <table id="table" class="table table-bordered table-striped" cellspacing="0"
                                   width="100%">
                                   <thead>
                                       <tr>
                                           <th>Nama</th>
                                           <th>NIM</th>
                                           <th>No Tas</th>
                                           <th>Fakultas</th>
                                           <th>Tanggal Pinjam</th>
                                           <th>Operator Pinjam</th>
                                           <th>Tanggal Kembali</th>
                                           <th>Operator Kembali</th>
                                       </tr>
                                   </thead>
                                   <tbody>
                                   </tbody>

                                   <tfoot>
                                       <tr>
                                           <th>Nama</th>
                                           <th>NIM</th>
                                           <th>No Tas</th>
                                           <th>Fakultas</th>
                                           <th>Tanggal Pinjam</th>
                                           <th>Operator Pinjam</th>
                                           <th>Tanggal Kembali</th>
                                           <th>Operator Kembali</th>
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
   <?php $this->load->view('dashboard/template/footer')?>
   <!-- end footer -->
   </div>
   <!-- js -->
   <?php $this->load->view('dashboard/template/js')?>
   <!-- end js -->

   <script type="text/javascript">
$(document).ready(function() {
    $('#tanggal').val('');
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
            "url": "<?php echo site_url('admin/transaksi_tas/ajax_list') ?>",
            'data': function(data) {
                data.searchNama = $('#nama').val();
                data.searchNim = $('#nim').val();
                data.searchFakultas = $('#fakultas').val();
                data.searchTanggal = $('#tanggal').val();
            },
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [{
                "targets": [-1], //first column
                "orderable": false, //set not orderable
            },
            {
                "targets": [-1], //last column
                "orderable": false, //set not orderable
            },

        ],

    });

    $('#btn-filter').click(function() { //button filter event click
        table.ajax.reload(); //just reload table
    });

    $('#btn-reset').click(function() { //button reset event click
        $('#table').DataTable().ajax.reload();
    });

    $('#btn-reset').click(function() { //button reset event click
        $('#form-filter')[0].reset();
        table.ajax.reload(); //just reload table
    });

    $('#btn-excel').click(function() { //button rxcel event click
        var nama = $('#nama').val();
        var nim = $('#nim').val();
        var fakultas = $('#fakultas').val();
        var tanggal = $("#tanggal").val();
        location.href = "<?=base_url()?>admin/laporan/export_excel?nama=" + nama + "&nim=" + nim +
            "&fakultas=" + fakultas + "&tanggal=" + tanggal;
    });

});

//Date range picker
$('#tanggal').daterangepicker()
   </script>