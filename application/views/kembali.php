<?php $this->load->view('home/template/header'); ?>
<audio id="sound_tag3" src="<?php echo base_url('assets/audio/kembali/scan_loker_kembali.mp3') ?>"
    preload=" auto"></audio>
<audio id="sound_suskes" src="<?php echo base_url('assets/audio/kembali/sukses_kembali_loker.mp3') ?>"
    preload=" auto"></audio>
<audio id="sound_tag1" src="<?php echo base_url('assets/audio/error.mp3') ?>" preload=" auto"></audio>
<audio id="sound_tag4" src="<?php echo base_url('assets/audio/berhasil_loker.mp3') ?>" preload=" auto"></audio>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card" style="border: 1px solid #fff ;background-color:#0f0f0f;font-size:15px">
                    <div class="card-body" style="text-align: center;height:350px">
                        <form action="#" class="info">
                            <div id="scan_loker">
                                <div class="form-group ipt" style="text-align: center;font-size:40px;">
                                    <label>SCAN ID LOKER</label>
                                    <input type="text" class="form-control" name="id_loker" autocomplete="off"
                                        id="id_loker"
                                        style="text-align:center;color:#fff;border: 1px solid #0f0f0f;background-color:#0f0f0f;height:200px;font-size:70px;padding-left:100px;padding-right:100px;">
                                    <input type="hidden" class="form-control" id="fakultas" value="">
                                </div>
                            </div>
                            <div id="scan_tas">
                                <div class="form-group ipt" style="text-align: center;font-size:40px;">
                                    <label>SCAN BARCODE TAS</label>
                                    <input type="text" class="form-control" name="id_tas" autocomplete="off" id="id_tas"
                                        style="text-align:center;border: 1px solid #0f0f0f;background-color:#0f0f0f;color:#FBFBFB;height:200px;font-size:70px;padding-left:100px;padding-right:100px;">
                                </div>
                            </div>
                            <div id="info-message" style="font-size: 30px;">

                            </div>
                        </form>
                    </div>
                    <div class="footer"
                        style="margin-bottom:20px;margin-right:20px;font-family:Arial, Helvetica, sans-serif">
                        <a href="<?php echo base_url() ?>" class="btn btn-warning float-right" fdprocessedid="idkgqi"
                            style="font-size: 20px;width:200px"><i class="fas fa-chevron-left"></i> BACK</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-conf-kembali-tas" tabindex="-1" aria-labelledby="modal-conf-pinjam-tas"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style=" font-family:Georgia;font-size: 35px;height:300px">
            <div class="modal-header" style="background-color:#7f6003;color:#fff">
                <h1 class="modal-title" id="exampleModalLabel">Konfirmasi</h1>
            </div>
            <div class="modal-body" style="color: #0f0f0f;">
                Apakah Anda ingin Mengembalikan Tas?
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-success" data-dismiss="modal" id="kembaliTas"
                    style="width:130px;height:90px;color:#fff;font-size: 35px;"><i class="nav-icon fas fa-check"></i>
                    YA</button>
                <a href="<?php echo base_url() ?>"><button type="button" class="btn btn-danger" role="button"
                        style="width:190px;height:90px;color:#fff;font-size: 34px;"><i
                            class="nav-icon fas fa-times"></i>
                        TIDAK</button></a>

            </div>
        </div>
    </div>
</div>

<?php $this->load->view('home/template/footer') ?>


<script type="text/javascript">
function myfunction() {
    alert("Image is loaded");
}

$("#kembaliTas").click(function() {
    $('#modal-conf-kembali-tas').modal('toggle');
    $("#scan_loker").hide();
    $("#scan_tas").show();
    $('#id_tas').focus();
});

$("#id_loker").keyup(function() {
    $(this).attr('type', 'password');
});

$('#scan_loker').keypress((e) => {
    // Enter key corresponds to number 13
    if (e.which === 13) {

        e.preventDefault();
        var id_loker = $('#id_loker').val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('kembali/transaksi') ?>",
            data: {
                id_loker: id_loker,
            },
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    if (response.data.anggota == 'mahasiswa') {
                        $("#data-anggota").html(
                            '<div class="text-center"><img class="profile-user-img img-fluid img-box" src="' +
                            response.data.img +
                            '" alt="User profile picture"></div><h3 class="profile-username text-center">' +
                            response.data.nama +
                            '</h3><h3 class="profile-username text-center">' + response.data
                            .nim + '</h3><h3 class="profile-username text-center">' + response
                            .data.fakultas + '</h3>');
                    }
                    $('#info-message').html('<p id="message" style="color:#1dff00;"><i class="' +
                        response.simbol + '"></i>  ' + response.message + '</p>');
                    $('#sound_suskes')[0].play();
                    setTimeout(function() {
                        $('#info-message').html('');

                        $('#modal-conf-kembali-tas').modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                        // window.location.href = "<?php echo base_url() ?>";
                    }, 3000);
                } else {
                    $('#id_loker').val('');
                    $('#info-message').html('<p id="message" style="color:#f20404;"><i class="' +
                        response.simbol + '"></i>  ' + response.message + '</p>');
                    $('#sound_tag1')[0].play();
                    setTimeout(function() {
                        $('#info-message').html('');
                    }, 3000);
                }

            }
        });
    }
});

$('#scan_tas').keypress((e) => {

    // Enter key corresponds to number 13
    if (e.which === 13) {
        e.preventDefault();
        var id_tas = $('#id_tas').val();

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('tas/kembali') ?>",
            data: {
                id_tas: id_tas,
            },
            dataType: "json",
            success: function(data) {
                console.log(data);
                if (data.status) {
                    $('#info-message').html('<p id="message" style="color:#1dff00;"><i class="' +
                        data.simbol + '"></i> ' + data.message + '</p>');
                    $('#sound_tag4')[0].play();
                    setTimeout(function() {
                        $('#info-message').html('');
                        $('#data-anggota').html('');
                        window.location.href = "<?php echo base_url() ?>";
                    }, 3000);
                } else {
                    $('#id_tas').focus();
                    $('#id_tas').val('');
                    $('#info-message').html('<p id="message" style="color:#f20404;"><i class="' +
                        data.simbol + '"></i>  ' + data.message + '</p>');
                    $('#sound_tag1')[0].play();
                    setTimeout(function() {
                        $('#info-message').html('');
                    }, 3000);
                }

            }
        });
    }
});

$(document).ready(function() {
    $("#id_tas").keyup(function() {
        $(this).attr('type', 'password');
    });
    $('#id_loker').focus();
    $('#scan_tas').hide();
    $('#sound_tag3')[0].play();
    setTimeout(function() {
        window.location.href = "<?php echo base_url() ?>";
    }, 51000);
});
</script>