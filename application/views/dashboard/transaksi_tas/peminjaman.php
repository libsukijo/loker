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
                               <h2>Form Peminjaman Tas</h2>
                           </div><!-- /.card-header -->
                           <div class="card-body" style="font-size: 20px;">
                               <div id="info_message">
                                   <div class="alert alert-danger" role="alert" style="display:none">
                                   </div>
                                   <div class="alert alert-success" role="alert" style="display:none">
                                   </div>
                               </div>
                               <form action="#" id="form" class="form-horizontal">
                                   <div class="form-group row">
                                       <label for="nimPinjam" class="col-sm-2 col-form-label nimPinjam"
                                           id="nimPinjam">NIM</label>
                                       <div class="col-sm-10">
                                           <input type="text" class="form-control" id="nim" placeholder="Nim / Barcode"
                                               name="nim" autocomplete="off">
                                           <span class="form-check"><input type="checkbox" class="form-check-input"
                                                   id="inputTamu">
                                               <label class="form-check-label" for="inputTamu">Tamu</label></span>
                                       </div>
                                   </div>


                                   <div class=" form-group row">
                                       <label for="InputBarcode" class="col-sm-2 col-form-label">No Barcode</label>
                                       <div class="col-sm-10">
                                           <input type="text" class="form-control" id="barcode_tas" name="barcode_tas"
                                               placeholder="No Barcode Tas">
                                           <span class="error"></span>
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
$(document).ready(function() {
    $('#nim').focus();
    $("#barcode_tas").keyup(function() {
        ///$(this).attr('type', 'password');
         $(this).val($(this).val().toUpperCase());
    });
    $("#inputTamu").click(function() {
        $('#nim').focus();
        var text = $("#nimPinjam").text();
        console.log(text);

        if (text == 'NIM') {
            $("#nimPinjam").html('Nama Tamu');
            $("#nim").attr('placeholder', 'Masukkan Nama Tamu');
            $("#nim").val('');

        } else {
            $("#nimPinjam").html('NIM');
            $("#nim").attr('placeholder', 'Masukkan NIM');
            $("#nim").val('');
        }
    });

    $('#nim').keypress((e) => {
        // Enter key corresponds to number 13
        if (e.which === 13) {
            e.preventDefault();
            var inputNim = $('#nim').val();
            if (inputNim != '') {
                $('#barcode_tas').focus();
            }
        }
    });
    $('#barcode_tas').keypress((e) => {
        // Enter key corresponds to number 13
        if (e.which === 13) {
            e.preventDefault();
            save();
        }
    });
});

function save() {
    $('.form-control').removeClass('is-invalid');
    var nim = $("#nim").val();
    var barcode_tas = $("#barcode_tas").val();
    var tamu = false;
    var textLabel = $("#nimPinjam").text();
    var message = '';
    console.log(textLabel);

    if (nim.length == "") {

        if (textLabel == 'NIM') {
            message = 'NIM e Harus Diisi!';
        } else {
            message = 'Nama Tamu Harud Diisi!';
        }

        $('.alert-danger').show().html(message);
        setTimeout(function() {
            $('.alert-danger').html('');
            $('.alert-danger').hide();
        }, 2000);
        $('#nim').focus();
        return false;
    }
    if (barcode_tas.length == "") {
        $('.alert-danger').show().html('No Barcode Harus Diisi');
        setTimeout(function() {
            $('.alert-danger').html('');
            $('.alert-danger').hide();
        }, 2000);
        $('#barcode_tas').focus();
        return false;
    }

    //check jika centang tamu
    if (textLabel == 'Nama Tamu') {
        nim = 'TM-' + nim;
        tamu = true;
    }
    // ajax adding data to database
    var formData = new FormData($('#form')[0]);


    $.ajax({
        type: "POST",
        url: "<?php echo base_url('admin/transaksi_tas/save_peminjaman') ?>",
        data: {
            "nim": nim,
            "barcode_tas": barcode_tas,
            "tamu": tamu
        },
        dataType: "JSON",
        success: function(data) {
            console.log(data);
            if (data.status == 1) {
                $('.alert-success').show().html(data.message);
                if (data.data_anggota) {
                    $("#info_template").html(
                        '  <div class="card card-primary card-outline" id="info-anggota"><div class="card-body box-profile"><div class="text-center"><img class="profile-user-img img-fluid" src="' +
                        data.image +
                        '" alt="User profile picture"></div><h3 class="profile-username text-center">' +
                        data.data_anggota.nama +
                        '</h3><ul class="list-group list-group-unbordered mb-3"><li class="list-group-item"><b>NIM</b> <a class="float-right"> ' +
                        data.data_anggota.nim +
                        '</a></li><li class="list-group-item"><b>Fakultas</b> <a class="float-right">' +
                        data.data_anggota.fakultas + '</a></li></ul></div> </div>');
                }
                setTimeout(function() {
                    $('.alert-success').html('');
                    $('.alert-success').hide();
                    $("#info_template").html('');
                    $("#barcode_tas").val('');
                    $("#nim").val('');
                    $('#nim').focus();
                    $("#nimPinjam").text('NIM');
                    $("#Nim").attr('placeholder', 'Masukkan NIM');
                    $("#inputTamu").prop("checked", false);
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