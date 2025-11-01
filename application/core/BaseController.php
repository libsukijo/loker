<?php

class BaseController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('transaksi_model');
    }

    public function isLoggedIn()
    {
        $isLoggedIn = $this->session->userdata('isLoggedIn');
        if (!isset($isLoggedIn) || $isLoggedIn !== TRUE) {
            redirect('login');
        } else {
        }
    }

    public function loginCheck()
    {
        $exp_time = $this->session->userdata("expires_by");
        if (time() < $exp_time) {
            return true;
        } else {
            $this->session->sess_destroy();
            redirect('login');
            return false;
        }
    }

    public function insert_loker_log($data, $status, $event)
    {
        $data = array(
            'nama' => $data['nama'],
            'nim' => $data['nim'],
            // 'tgl_pinjam' => date('y-m-d h:i:s'),
            'status' => $status,
            'event' => $event,
            'keterangan' => $data['message'],
            'no_loker' => $data['no_loker']
        );
        $this->transaksi_model->insertLog($data);
    }

    public function getImage($nim)
    {
        //get image mahasiswa from api
        $url = 'https://siprus.uin-suka.ac.id/realtime/b/a.php?nim=' . $nim;
        $html = @file_get_contents($url);
        if ($html === false) {
            // Jika gagal, gunakan gambar default
            return base_url('assets/img/default.png');
        }
        $doc = new DOMDocument();
        $img = '';
        @$doc->loadHTML($html);
        $tags = $doc->getElementsByTagName('img');
        foreach ($tags as $tag) {
            $img = $tag->getAttribute('src');
        }
        return $img ? $img : base_url('assets/img/default.png');
    }
}
