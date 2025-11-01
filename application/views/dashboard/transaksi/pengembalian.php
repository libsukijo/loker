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
                               <h2>Form Pengembalian</h2>
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
                                       <label for="noloker" class="col-sm-2 col-form-label">No Loker</label>
                                       <div class="col-sm-10" class="label1">
                                           <input type="text" class="form-control" id="no_loker" name="no_loker"
                                               placeholder="No Loker">
                                       </div>
                                   </div>
                                   <div class="form-group row">
                                       <div class="offset-sm-2 col-sm-10">
                                           <button type="submit" class="btn btn-primary">Submit</button>
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
    $('#no_loker').focus();
    $("#no_loker").keyup(function() {
       // $(this).attr('type', 'password');
         $(this).val($(this).val().toUpperCase());

    });

});

$("#form").submit(function(e) {
    var no_loker = $("#no_loker").val();
    if (no_loker.length == "") {
        $('.alert-danger').show().html('No Barcode Harus Diisi');
        setTimeout(function() {
            $('.alert-danger').html('');
            $('.alert-danger').hide();
        }, 2000);
        $('#no_loker').focus();
        return false;
    }
    e.preventDefault();
    var formData = new FormData($('#form')[0]);
    $.ajax({
        type: "POST",
        url: "<?php echo base_url('admin/transaksi/save_pengembalian') ?>",
        data: {
            "no_loker": no_loker
        },
        dataType: "JSON",
        success: function(data) {
            if (data.status == 1) {
                $('.alert-success').show().html(data.message);
                $("#info_template").html(
                    '<div class="card card-primary card-outline" id="info-anggota"><div class="card-body box-profile"><div class="text-center"><img class="profile-user-img img-fluid" src="' +
                    data.image +
                    '" alt="User profile picture"></div><h3 class="profile-username text-center">' +
                    data.data_anggota.nama +
                    '</h3><ul class="list-group list-group-unbordered mb-3"><li class="list-group-item"><b>NIM</b> <a class="float-right"> ' +
                    data.data_anggota.no_mhs +
                    '</a></li><li class="list-group-item"><b>Fakultas</b> <a class="float-right">' +
                    data.data_anggota.fakultas + '</a></li></ul></div> </div>');
                setTimeout(function() {
                    $('.alert-success').html('');
                    $('.alert-success').hide();
                    $('#info_template').html('');
                    $("#no_loker").val('');

                }, 4000);
            } else {
                $('.alert-danger').show().html(data.message);
                setTimeout(function() {
                    $('.alert-danger').html('');
                    $('.alert-danger').hide();
                    $("#no_loker").val('');
                }, 2000);
            }
        },

    });
});

function save() {



}


function remove_message() {
    $("#message").remove();
}

function emptyForm() {
    $('#info-anggota').remove();
    $('#no_loker').val('');
}
   </script>