<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/core/BaseController.php';

class Kembali  extends BaseController
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
                    $data_log = array(
                        'message' => 'LOKER SUDAH DIKEMBALIKAN',
                        'nama' => $get_data_transaksi_by_id_loker['nama'],
                        'nim' => $get_data_transaksi_by_id_loker['nim'],
                        'no_loker' => $id_loker,
                    );
                    $this->insert_loker_log($data_log, 2, 'kembali');
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
        $get_data_anggota = $this->anggota_model->getAnggotaByNim($data_anggota['nim']);
        $data =  array(
            'tgl_kembali' => date('y-m-d H:i:s'),
            'updated_by' => '10.10.120.4',
        );

        //get image from api
        $img = $this->getImage($data_anggota['nim']);


        //update data
        $update = $this->kunci_model->updateTransaksi(array('no_loker' => $id_loker), $data);


        //insert log
        $data_log = array(
            'message' => ' BERHASIL MENGEMBALIKAN',
            'nama' => $get_data_anggota['nama'],
            'nim' => $data_anggota['nim'],
            'no_loker' => $id_loker,
        );

        $this->insert_loker_log($data_log, 1, 'kembali');

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
}
