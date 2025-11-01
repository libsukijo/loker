<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Realtime extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('transaksi_model');
    }


    public function index()
    {
        $data['page'] = 'Realtime';
        $this->load->view('realtime/realtime', $data);
    }

    public function getLog()
    {
        
        $list = $this->transaksi_model->getRealtime();
        $data = array();
        foreach ($list as $log) {
            // $image = $this->getImage($log->nim);
 $image = '';
            if(empty($image)){
                $image= site_url('assets/images/uin_default.png');
            }
            if ($log->status == 2) {
                $loker = substr($log->no_loker, 3);
                if(empty($log->nama)){
                    $row[] = '<tr style="color:red"><td width="100px""><img class="img-thumbnail" src="' . $image . '" alt="User profile picture" style="width:90px;height:100px"></div></td><td><h2 class="nama"> '.$log->nim.' <span class= "right badge badge-danger"> GAGAL </span></h2><p style="font-size:20px">' . $log->keterangan . '<br/><b>Nomer '.$loker.'</b></p></td></tr>';
                }
                else {
                    $row[] = '<tr style="color:red"><td width="100px""><img class="img-thumbnail" src="' . $image . '" alt="User profile picture" style="width:90px;height:100px"></div></td><td><h2 class="nama">' . $log->nama . ' - ' . $log->nim . '  <span class= "right badge badge-danger"> GAGAL </span></h2><p style="font-size:20px">' . $log->keterangan . '<br/><b>Nomer '.$loker.'</b></p></td></tr>';
                }
            } else {
                $loker = substr($log->no_loker, 3);
                $row[] = '<tr><td width="100px"><img class="img-thumbnail" src="' . $image . '" alt="User profile picture" style="width:90px;height:100px"></div></td><td><h2 class="nama">' . $log->nama . ' - ' . $log->nim . '  <span class= "right badge badge-success"> BERHASIL </span></h2><p style="font-size:20px">' . $log->keterangan . '<b/><br/><b>Nomer '.$loker.'</b></p></td></tr>';
            }
            $data = $row;
        }

        $response = array(
            'data' => $data,
            'total_row' => count($list)
        );
        echo json_encode($response);
    }

    private function getImage($nim)
    {
        //get image mahasiswa from api
        $url = 'https://siprus.uin-suka.ac.id/realtime/b/a.php?nim=' . $nim . '';
        $html = file_get_contents($url);
        $doc = new DOMDocument();
        $img = '';
        @$doc->loadHTML($html);
        $tags = $doc->getElementsByTagName('img');
        foreach ($tags as $tag) {
            $img = $tag->getAttribute('src');
        }
$img='';
        return $img;
    }
}
