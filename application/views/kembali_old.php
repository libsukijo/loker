<?php $this->load->view('home/template/header'); ?>
<audio id="sound_tag3" src="<?php echo base_url('assets/audio/kembali/scan_loker_kembali.mp3') ?>" preload=" auto"></audio>
<audio id="sound_suskes" src="<?php echo base_url('assets/audio/kembali/sukses_kembali_loker.mp3') ?>" preload=" auto"></audio>
<audio id="sound_tag1" src="<?php echo base_url('assets/audio/error.mp3') ?>" preload=" auto"></audio>

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
                                    <input type="text" class="form-control" name="id_loker" autocomplete="off" id="id_loker" style="text-align:center;color:#fff;border: 1px solid #0f0f0f;background-color:#0f0f0f;height:200px;font-size:70px;padding-left:100px;padding-right:100px;">
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
    function myfunction() {
        alert("Image is loaded");
    }

    function remove_error() {
        $('#message').remove();
    }
    
    function pageRedirect() {
        window.location.href = "<?php echo base_url() ?>";
    }

    function pageRedirectKembali() {
        $("#data-anggota").remove();
        window.location.href = "<?php echo base_url() ?>";
    }

    $("#id_loker").keyup(function(){
        $(this).attr('type', 'password');
    });

    $('#scan_loker').keypress((e) => {
        // Enter key corresponds to number 13
        if (e.which === 13) {
            e.preventDefault();
            var nim = $('#nim').val();
            var id_loker = $('#id_loker').val();
            var fakultas = $('#fakultas').val();
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
                            $("#data-anggota").append('<div class="text-center"><img class="profile-user-img img-fluid img-box" src="' + response.data.img + '" alt="User profile picture"></div><h3 class="profile-username text-center">' + response.data.nama + '</h3><h3 class="profile-username text-center">' + response.data.nim + '</h3><h3 class="profile-username text-center">' + response.data.fakultas + '</h3>');
                        }
                        $('#info-message').prepend('<p id="message" style="color:#1dff00;"><i class="' + response.simbol + '"></i>  ' + response.message + '</p>');
                        $('#sound_suskes')[0].play();
                        setTimeout("pageRedirectKembali()", 3000);
                    } else {
                        $('#id_loker').val('');
                        $('#info-message').prepend('<p id="message" style="color:#f20404;"><i class="' + response.simbol + '"></i>  ' + response.message + '</p>');
                        $('#sound_tag1')[0].play();
                        setTimeout("remove_error()", 3000);
                        setTimeout("pageRedirect()", 4000);
                    }

                }
            });
        }
    });

    $(document).ready(function() {
        $('#id_loker').focus();
        $('#sound_tag3')[0].play();
        setTimeout("pageRedirect()", 40000);
        
    });
</script>