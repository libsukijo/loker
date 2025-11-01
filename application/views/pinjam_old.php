<?php $this->load->view('home/template/header'); ?>
<audio id="sound_tag" src="<?php echo base_url('assets/audio/scan_kartu.mp3') ?>" preload=" auto"></audio>
<audio id="sound_tag1" src="<?php echo base_url('assets/audio/error.mp3') ?>" preload=" auto"></audio>
<audio id="sound_tag2" src="<?php echo base_url('assets/audio/kunci_note.mp3') ?>" preload=" auto"></audio>
<audio id="sound_tag3" src="<?php echo base_url('assets/audio/scan_loker1.mp3') ?>" preload=" auto"></audio>
<audio id="sound_tag4" src="<?php echo base_url('assets/audio/berhasil_loker.mp3') ?>" preload=" auto"></audio>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card" style="border: 1px solid #fff ;background-color:#0f0f0f;font-size:15px">
                    <div class="card-body" style="text-align: center;height:350px">
                        <form action="#" class="info">
                            <div id="scan_anggota">
                                <div class="form-group ipt" style="text-align: center;font-size:40px">
                                    <label>SCAN KARTU ANGGOTA</label>
                                    <input type="text" class="form-control" name="nim" pattern="\d" autocomplete="off" style="text-align:center;color:#fff;border: 1px solid #0f0f0f;background-color:#0f0f0f;height:200px;font-size:70px;padding-left:100px;padding-right:100px;" id="nim" onload="myfunction()">
                                </div>
                            </div>
                            <div id="scan_loker">
                                <div class="form-group ipt" style="text-align: center;font-size:40px;">
                                    <label>SCAN ID LOKER</label>
                                    <input type="text" class="form-control" name="id_loker" autocomplete="off" id="id_loker" style="text-align:center;border: 1px solid #0f0f0f;background-color:#0f0f0f;color:#FBFBFB;height:200px;font-size:70px;padding-left:100px;padding-right:100px;">
                                    <input type="hidden" class="form-control" id="fakultas" value="">
                                </div>
                            </div>
                            <div id="info-message" style="font-size: 30px;">
                            </div>
                        </form>
                    </div>
                    <div class="footer" style="margin-bottom:20px;margin-right:20px;font-family:Arial, Helvetica, sans-serif">
                        <a href="<?php echo base_url() ?>" class="btn btn-warning float-right" fdprocessedid="idkgqi" style="font-size: 20px;width:200px"><i class="fas fa-chevron-left"></i> BACK</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


</section>

<?php $this->load->view('home/template/footer') ?>


<script type="text/javascript">
    function remove_error() {
        $('#message').remove();
    }

    function pageRedirect() {
        window.location.href = "<?php echo base_url() ?>";
    }

    $(document).ready(function() {
        $('#sound_tag')[0].play();
        $('#nim').focus();
        $("#scan_loker").hide();
        setTimeout("pageRedirect()", 40000);
    });

    $("#id_loker").keyup(function(){
        $(this).attr('type', 'password');
    });


    $('#scan_anggota').keypress((e) => {
        // Enter key corresponds to number 13
        if (e.which === 13) {
            e.preventDefault();
            var nim = $('#nim').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('pinjam/getAnggota') ?>",
                data: {
                    nim: nim
                },
                dataType: "json",
                success: function(response) {
                    console.log(response);

                    if (response.status == 1) {
                        if (response.data.anggota == 'mahasiswa') {
                            $("#data-anggota").append('<div class="text-center"><img class="profile-user-img img-fluid img-box" src="' + response.data.img + '" alt="User profile picture" style="height:30%"></div><h3 class="profile-username text-center">' + response.data.nama + '</h3><h3 class="profile-username text-center">' + response.data.no_mhs + '</h3><h3 class="profile-username text-center">' + response.data.fakultas + '</h3>');
                        } else {

                            $("#data-anggota").append('<div class="text-center"><img class="profile-user-img img-fluid img-box" src="<?php echo base_url('assets/images/img_user.png') ?>" alt="User profile picture"></div></div><h3 class="profile-username text-center">' + response.data.nama + '</h3><h3 class="profile-username text-center"><div id="nik">' + response.data.nik + '</h3><h3 class="profile-username text-center">' + response.data.instansi + '</h3>');
                        }
                        $("#fakultas").val(response.data.fakultas);
                        $("#scan_anggota").hide();
                        $("#scan_loker").show();
                        $('#info-message').append('<p id="message" style="color:#1dff00;"><i class="' + response.simbol + '"></i> ' + response.message + '</p>');
                        $('#id_loker').focus();
                        $('#sound_tag3')[0].play();
                    } else {
                        $('#sound_tag1')[0].play();
                        $('#nim').val('');
                        $('#info-message').append('<p id="message" style="color:#f20404;"><i class="' + response.simbol + '"></i>  ' + response.message + '</p>');
                    }
                    setTimeout("remove_error()", 1000);
                }
            });
        }
    });

    $('#scan_loker').keypress((e) => {
        // Enter key corresponds to number 13
        if (e.which === 13) {
            var nim = $('#nim').val();
            var id_loker = $('#id_loker').val();
            var fakultas = $('#fakultas').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('pinjam/tambahTransaksi') ?>",
                data: {
                    nim: nim,
                    id_loker: id_loker,
                    fakultas: fakultas
                },
                dataType: "json",
                success: function(data) {

                    if (data.status) {
                        $('#info-message').prepend('<p id="message" style="color:#1dff00;"><i class="' + data.simbol + '"></i> ' + data.message + '</p>');
                        $('#sound_tag4')[0].play();
                        setTimeout("pageRedirect()", 3000);
                    } else {
                        $('#id_loker').val('');
                        $('#info-message').prepend('<p id="message" style="color:#f20404;"><i class="' + data.simbol + '"></i> ' + data.message + '</p>');
                        $('#sound_tag1')[0].play();
                        setTimeout("remove_error()", 3000);
                        setTimeout("pageRedirect()", 5000);
                    }

                }
            });
        }
    });
</script>