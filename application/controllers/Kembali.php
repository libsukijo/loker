<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Kembali extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('anggota_model');
        $this->load->model('kunci_model');
        $this->load->model('tamu_model');
        $this->load->model('transaksi_model');
    }

    public function index()
    {
        $totalLokerPria = $this->kunci_model->totalLokerPria();
        $totalLokerWanita = $this->kunci_model->totalLokerWanita();
        $LkPriaPakai = $this->kunci_model->LkPriaPakai();
        $LkWanitaPakai = $this->kunci_model->LkWanitaPakai();

        $data = array(
            'title' => 'PENGEMBALIAN KUNCI LOKER',
            'total_loker_pria' => $totalLokerPria,
            'total_loker_wanita' => $totalLokerWanita,
            'sisa_loker_pria' => $totalLokerPria - $LkPriaPakai,
            'sisa_loker_wanita' => $totalLokerWanita - $LkWanitaPakai,
            'page' => 'KEMBALI'
        );

        $this->load->view('kembali', $data);
    }

    public function transaksi()
    {
        $id_loker = $this->input->post('id_loker');
        //jika id loker tidak ada input
        if ($id_loker != '') {
            // check validasi loker
            $check_loker = $this->kunci_model->checkLoker($id_loker);
            if (count($check_loker) > 0) {
                // pengecekan transaksi loker jika sudah kembalikan maka tidak bisa diproses 
                $get_data_transaksi_by_id_loker = $this->kunci_model->getTransaksiByIdloker($id_loker);
                if (count($get_data_transaksi_by_id_loker) > 0) {

                    $nim = $get_data_transaksi_by_id_loker['nim'];
                    $checkTamu = substr($nim, 0, 2);

                    if ($checkTamu == 'TM') {
                        //untuk data tamu
                        $id_tamu = $get_data_transaksi_by_id_loker['id_anggota'];
                        $this->update_tr_tamu($id_tamu);
                    } else {
                        //untuk data anggota
                        $this->update_tr_anggota($get_data_transaksi_by_id_loker);
                    }
                } else {

                    //insert log
                    $this->insert_loker_log(array('nama' => $get_data_transaksi_by_id_loker['nama'], 'no_loker' => $id_loker,'nim'=>$get_data_transaksi_by_id_loker['nim'], 'message' => 'LOKER SUDAH DIKEMBALIKAN'), 2);
                    echo json_encode(array('status' => FALSE, 'message' => 'LOKER SUDAH DIKEMBALIKAN', 'simbol' => 'fas fa-times'));
                }
            } else {
                echo json_encode(array('status' => FALSE, 'message' => 'LOKER TIDAK VALID', 'simbol' => 'fas fa-times'));
            }
        }
    }

    private function update_tr_anggota($data_anggota)
    {
        $id_loker = $this->input->post('id_loker');


        //cari ID transaksi
        $get_data_anggota = $this->anggota_model->getAnggotaByNim($data_anggota['nim']);
        $get_transaksi = $this->transaksi_model->getTransaksiYgBelumKembali($id_loker, $data_anggota['nim']);
        //end cari ID transaksi


        $data =  array(
            'tgl_kembali' => date('y-m-d H:i:s'),
            'updated_by' => '10.10.120.4',
        );

        //get image from api
        // $img = $this->getImage($data_anggota['nim']);
        $img='';

        //update data
        //$update = $this->kunci_model->updateTransaksi(array('no_loker' => $id_loker), $data);
         $update = $this->kunci_model->updateTransaksi(array('no_loker' => $id_loker, 'id' => $get_transaksi['id']), $data);

        //insert log
        $this->insert_loker_log(array('nama' => $get_data_anggota['nama'],'nim'=> $data_anggota['nim'], 'no_loker' => $id_loker, 'message' => 'BERHASIL MENGEMBALIKAN'), 1);

        //menampilkan data berdasarkan data anggota
        $data_anggota['anggota'] = 'mahasiswa';
        $data_anggota['img'] = $img;
        echo json_encode(array('status' => TRUE, 'data' => $data_anggota, 'message' => ' BERHASIL MENGEMBALIKAN', 'simbol' => 'fas fa-check-square'));
    }

    private function update_tr_tamu($id_tamu)
    {
        $id_loker = $this->input->post('id_loker');

        $get_data_tamu = $this->tamu_model->getTamuById($id_tamu);
        $data = array(
            'tgl_kembali' => date('y-m-d H:i:s'),
            'updated_by' => '10.10.120.4',
        );

        $data_update = array(
            'status' => 2,
            'no_barcode' => '',
            'image_barcode' => ''
        );
        $update_tr = $this->kunci_model->updateTransaksi(array('no_loker' => $id_loker), $data);
        $update =  $this->tamu_model->update(array('id_tamu' => $id_tamu), $data_update);
        echo json_encode(array('status' => TRUE, 'data' => $get_data_tamu, 'message' => ' BERHASIL MENGEMBALIKAN', 'simbol' => 'fas fa-check-square'));
    }


    private function insert_loker_log($data, $status)
    {
        $data = array(
            'nama' => $data['nama'],
            'nim' => $data['nim'],
            'status' => $status,
            'event' => 'kembali',
            'keterangan' => $data['message'],
            'no_loker' => $data['no_loker'],
            'created_by' => '10.10.120.4',
        );
        $this->transaksi_model->insertLog($data);
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

        return $img;
    }
}
