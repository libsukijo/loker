<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/core/BaseController.php';

class Transaksi extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('laporan_model');
        $this->load->model('tamu_model');
        $this->load->model('anggota_model');
        $this->load->model('kunci_model');
        $this->load->model('transaksi_model');
        $this->isLoggedIn();
    }


    public function index()
    {
        $data['page'] = 'Laporan';

        $this->load->view('dashboard/transaksi/peminjaman', $data);
    }

    public function ajax_list()
    {
        $list = $this->laporan_model->get_datatables();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $kunci) {
            $no++;
            $row = array();
            $row[] = $kunci->nama;
            $row[] = $kunci->no_loker;
            $row[] = $kunci->fakultas;
            $row[] = $kunci->tgl_pinjam;
            //add html for action
            $row[] = '';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->laporan_model->count_all(),
            "recordsFiltered" => $this->laporan_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function peminjaman()
    {
        $data['page'] = 'Peminjaman';
        $this->load->view('dashboard/transaksi/peminjaman', $data);
    }

    public function pengembalian()
    {
        $data['page'] = 'Pengembalian';
        $this->load->view('dashboard/transaksi/pengembalian', $data);
    }

    public function save_peminjaman()
    {
        $this->_validate();
        $nim = $this->input->post('nim');
        $checkNim = substr($nim, 0, 2);
        if ($checkNim == 'TM') {
            $this->tambahTransaksiTamu();
        } else {
            $get_data_anggota = $this->anggota_model->getAnggotaByNim($nim);
            $data = array(
                'nim' => $this->input->post('nim'),
                'id_anggota' => $this->input->post('nim'),
                'nama' => $get_data_anggota['nama'],
                'no_loker' => $this->input->post('no_loker'),
                'fakultas' => $get_data_anggota['fakultas'],
                'tgl_pinjam' => date('y-m-d H:i:s'),
                'created_by' => $this->session->userdata('nama'),
                'tgl_kembali' => NULL
            );
            //check validasi loker
            $this->_validate_loker($data);

            //insert transaksi
            $insert = $this->kunci_model->addTransaksi($data);

            //insert log
            $data['message'] = 'BERHASIL MEMINJAM';
            $this->insert_loker_log($data, '1', 'pinjam');
            //end insert log
            $image=$this->getImage($this->input->post('nim'));
            echo json_encode(array('status' => 1, 'message' => ' BERHASIL MEMINJAM', 'data_anggota' => $get_data_anggota,'image'=>$image));
        }
    }

    private function tambahTransaksiTamu()
    {
        $getDataTamu = $this->tamu_model->getTamuByBarcode($this->input->post('nim'));
        $data = array(
            'nim' => $this->input->post('nim'),
            'id_anggota' => $getDataTamu['id_tamu'],
            'nama' => $getDataTamu['nama'],
            'fakultas' => 'Tamu',
            'no_loker' =>$this->input->post('no_loker'),
            'tgl_pinjam' => date('y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama'),
            'tgl_kembali' => NULL
        );
        $insert = $this->kunci_model->addTransaksi($data);
        //insert log
        $data['message'] = 'BERHASIL MEMINJAM';
        $this->insert_loker_log($data, '1', 'pinjam');
        //end insert log
        echo json_encode(array('status' => 1, 'message' => ' BERHASIL MEMINJAM', 'data_tamu' => $getDataTamu));
    }

    public function _validate_loker($info)
    {
        $nim = $this->input->post('nim');
        $no_loker = $this->input->post('no_loker');
        $check_loker = $this->kunci_model->checkLoker($no_loker);
        $check_loker_transaksi = $this->kunci_model->getTransaksiByIdloker($no_loker);

        $check_pinjam = $this->kunci_model->checkPinjam($nim);
        $data = array();
        $data['status'] = 1;
        if (count($check_loker) == 0) {
            $data['message'] = 'Kunci Loker tidak valid';
            $data['status'] = 2; //gaga;
        }

        if (count($check_loker_transaksi) > 0) {
            $data['message'] = 'Kunci Loker sudah dipakai';
            $data['status'] = 2; //gagal;
        }
        if (count($check_pinjam) > 0) {
            $data['message'] = 'Kunci belum di kembalikan';
            $data['status'] = 2; //gagal
        }

        if ($data['status'] == 2) {
            $info['message'] = $data['message'];
            //insert log
            $this->insert_loker_log($info, 2, 'pinjam');
            //end insert log
            echo json_encode($data);
            exit();
        }
    }

    public function save_pengembalian()
    {
        $this->_validate_form_pengembalian();
        $no_loker = $this->input->post('no_loker');
        $get_data_transaksi_by_id_loker = $this->kunci_model->getTransaksiByIdloker($no_loker);
        if (!empty($get_data_transaksi_by_id_loker)) {
            $nim = $get_data_transaksi_by_id_loker['nim'];
            $checkTamu = substr($nim, 0, 2);
            if ($checkTamu == 'TM') {
                $id_tamu = $get_data_transaksi_by_id_loker['id_anggota'];
                $this->update_tr_tamu($id_tamu);
            } else {
                $this->update_tr_anggota($nim);
            }
        } else {
            echo json_encode(array('status' => 2, 'message' => ' Loker tidak valid / sudah dikembalikan'));
        }
    }

    private function update_tr_anggota($nim)
    {
        $no_loker = $this->input->post('no_loker');
        $get_data_anggota = $this->anggota_model->getAnggotaByNim($nim);
        $data =  array(
            'tgl_kembali' => date('y-m-d H:i:s'),
        );
        $update = $this->kunci_model->updateTransaksi(array('no_loker' => $no_loker), $data);
        //insert log
        $data_log = array('message' => 'BERHASIL MENGEMBALIKAN', 'no_loker' => $no_loker, 'nama' => $get_data_anggota['nama'],'nim'=>$get_data_anggota['no_mhs']);
        $this->insert_loker_log($data_log, '1', 'kembali');
        //end insert log

        $image=$this->getImage($nim);
        echo json_encode(array('status' => TRUE, 'data_anggota' => $get_data_anggota, 'message' => ' BERHASIL MENGEMBALIKAN','image'=>$image,));
    }

    private function update_tr_tamu($id_tamu)
    {
        $id_loker = $this->input->post('no_loker');
        $get_data_tamu = $this->tamu_model->getTamuById($id_tamu);
        $data = array(
            'tgl_kembali' => date('y-m-d H:i:s'),
        );

        $data_update = array(
            'status' => 2,
            'no_barcode' => '',
            'image_barcode' => ''
        );
        $update_tr = $this->kunci_model->updateTransaksi(array('no_loker' => $id_loker), $data);
        $update =  $this->tamu_model->update(array('id_tamu' => $id_tamu), $data_update);
        echo json_encode(array('status' => 1, 'data' => $get_data_tamu, 'message' => ' BERHASIL MENGEMBALIKAN'));
    }

    public function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['input_error'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('nim') == '') {
            $data['input_error'][] = 'nim';
            $data['error_string'][] = 'Nim harus diisi';
            $data['status'] = FALSE;
        }
        if ($this->input->post('no_loker') == '') {
            $data['input_error'][] = 'no_loker';
            $data['error_string'][] = 'No Loker harus diisi';
            $data['status'] = FALSE;
        }

        if ($data['status'] == FALSE) {
            echo json_encode($data);
            exit();
        }
    }

    public function _validate_form_pengembalian()
    {

        $data = array();
        $data['error_string'] = array();
        $data['input_error'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('no_loker') == '') {
            $data['input_error'][] = 'no_loker';
            $data['error_string'][] = 'No Loker harus diisi';
            $data['status'] = FALSE;
        }

        if ($data['status'] == FALSE) {
            echo json_encode($data);
            exit();
        }
    }

    public function realtime()
    {
        $data = array(
            'page' => 'Realtime'
        );
        $this->load->view('dashboard/transaksi/realtime', $data);
    }

    public function getLog()
    {
        $list = $this->transaksi_model->getRealtimeDashboard();
        //$list = $this->transaksi_model->getMonitoring();

        $data = array();
        foreach ($list as $log) {
            $image = $this->getImage($log->nim);
            if ($log->status == 2) {
                $loker = substr($log->no_loker, 3);
                $row[] = '<tr><td width="100px"><img class="profile-user-img img-fluid img-box" src="'.$image.'" alt="User profile picture" style="width:90px;height:100px"></div></td><td><h2 class="nama">' . $log->nama . '  <span class= "right badge badge-danger"> Gagal </span></h2><p>' . $log->keterangan . '<br/><b>Nomer ' . $loker . '</b></p></td></tr>';
            } else {
                $loker = substr($log->no_loker, 3);
                $row[] = '<tr><td width="100px"><img class="profile-user-img img-fluid img-box" src="'.$image.'" alt="User profile picture" style="width:90px;height:100px"></div></td><td><h2 class="nama">' . $log->nama . '  <span class= "right badge badge-success"> Berhasil </span></h2><p>' . $log->keterangan . '<br/><b>Nomer ' . $loker . '</b></p></td></tr>';
            }
            $data = $row;
        }

        $response = array(
            'data' => $data,
            'total_row' => count($list)
        );
        echo json_encode($response);
    }
}

/* End of file Controllername.php */
