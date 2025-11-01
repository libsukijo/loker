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
                           <li class="breadcrumb-item">Transaksi Tas</li>
                           <li class="breadcrumb-item">Pengembalian</li>
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
                               <h2>Form Pengembalian Tas</h2>
                           </div><!-- /.card-header -->
                           <div class="card-body">
                               <div id="info_message">
                                   <div class="alert alert-danger" role="alert" style="display:none">
                                   </div>
                                   <div class="alert alert-success" role="alert" style="display:none">
                                   </div>
                               </div>
                               <form action="#" id="form" class="form-horizontal">
                                   <div class=" form-group row">
                                       <label for="noloker" class="col-sm-2 col-form-label">No Barcode</label>
                                       <div class="col-sm-10" class="label1">
                                           <input type="text" class="form-control" id="barcode_tas" name="barcode_tas"
                                               placeholder="Masukan Barcode Tas">
                                       </div>
                                   </div>
                                   <div class="form-group row">
                                       <div class="offset-sm-2 col-sm-10">
                                           <button type="button" onclick="save()"
                                               class="btn btn-primary">Submit</button>
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
$(document).ready(function() {
    $('#barcode_tas').focus();
    $("#barcode_tas").keyup(function() {
       // $(this).attr('type', 'password');
        $(this).val($(this).val().toUpperCase());
    });

    $('#barcode_tas').keypress((e) => {
        // Enter key corresponds to number 13
        if (e.which === 13) {
            e.preventDefault();
            var barcode_tas = $('#barcode_tas').val();
            if (barcode_tas != '') {
                save();
            }
        }
    });

});

function save() {
    var barcode_tas = $("#barcode_tas").val();
    if (barcode_tas.length == "") {
        $('.alert-danger').show().html('No Barcode Harus Diisi');
        setTimeout(function() {
            $('.alert-danger').html('');
            $('.alert-danger').hide();
        }, 2000);
        $('#barcode_tas').focus();
        return false;
    }
    // ajax adding data to database
    var formData = new FormData($('#form')[0]);


    $.ajax({
        type: "POST",
        url: "<?php echo base_url('admin/transaksi_tas/save_pengembalian') ?>",
        data: {
            "barcode_tas": barcode_tas
        },
        dataType: "JSON",
        success: function(data) {
            if (data.status == 1) {
                $('.alert-success').show().html(data.message);
                setTimeout(function() {
                    $('.alert-success').html('');
                    $('.alert-success').hide();
                    $("#barcode_tas").val('');

                }, 4000);
            } else {
                $('.alert-danger').show().html(data.message);
                setTimeout(function() {
                    $('.alert-danger').html('');
                    $('.alert-danger').hide();
                    $("#barcode_tas").val('');
                }, 2000);

            }
        },
    });
}
   </script>