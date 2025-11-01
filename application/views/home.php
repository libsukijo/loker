<?php $this->load->view('home/template/header'); ?>
<audio id="sound_pinjam_kembali" src="<?php echo base_url('assets/audio/pinjam_kembali.mp3') ?>" preload=" auto"></audio>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card" style="border-top: 4px solid #0069D9 ;">
                    <div class="card-body text-center" style="background-color:#0f0f0f">
                        <a href="<?php echo base_url('pinjam') ?>"><button type="button" class="btn btn-warning" onclick="playAudio()" style="height: 300px;width:300px;font-size:50px">PINJAM</button></a>
                        <a href="<?php echo base_url('kembali') ?>"><button type="button" class="btn btn-danger" style="height: 300px;width:300px;font-size:50px">KEMBALI</button></a>
                    </div>
                </div>
            </div>
        </div>

    </div>

</section>
<?php $this->load->view('home/template/footer') ?>
<script type="text/javascript">
    $(document).on('keypress',function(e) {
        if(e.which == 13) {
            $('#sound_pinjam_kembali')[0].play();
        }
    });
</script>