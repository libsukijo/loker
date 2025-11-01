<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/core/BaseController.php';

class Blokir extends BaseController
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('blokir_model');
        $this->load->model('anggota_model');
        $this->load->library('curl');
        $this->isLoggedIn();
    }

    public function index()
    {
        $data['anggotas'] = $this->anggota_model->getAnggota();
        // $anggota = $this->curl->simple_get(API_URL . 'api/anggota');
        // $data['anggotas'] = json_decode($anggota, true);
        $data['page'] = 'BLOKIR';
        $this->load->view('dashboard/blokir/list', $data);
    }

    public function anggotaList($angkatan)
    {
        $getAnggota =  $this->anggota_model->getAnggotaByAngkatan($angkatan);
        $data = array();
        foreach ($getAnggota as $key => $value) {
            $data[$key]['id'] = $value->no_mhs . '-' . $value->nama;
            $data[$key]['text'] = $value->no_mhs . '-' . $value->nama;
        }
        $output = array('status' => TRUE, 'data' => $data);

        echo json_encode($output);
    }

    public function ajax_list()
    {
        $this->load->helper('url');
        $list = $this->blokir_model->get_datatables();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $blokir) {
            $no++;
            $row = array();
            $row[] = $blokir->nim;
            $row[] = $blokir->tgl;
            $row[] = $blokir->status;
            //add html for action
            $row[] = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="remove_blokir(' . "'" . $blokir->nim . "'" . ')"><i class="fas fa-trash"></i> Remove Blokir</a>
            ';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->blokir_model->count_all(),
            "recordsFiltered" => $this->blokir_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }


    public function ajax_add()
    {
        $this->_validate();
        $nim = '';
        $nama = '';
        if ($this->input->post('mahasiswa')) {
            $explode = explode('-', $this->input->post('mahasiswa'));
            $nim = $explode[0];
            $nama = $explode[1];
        }

        $data = array(
            'nim' => $nim,
            'tgl' => date('y-m-d'),
            'status' => $this->input->post('status')
        );


        $insert = $this->blokir_model->save($data);
        echo json_encode(array('status' => TRUE));
    }


    public function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['input_error'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('mahasiswa') == '') {
            $data['input_error'][] = 'mahasiswa';
            $data['error_string'][] = 'mahasiswa harus diisi';
            $data['status'] = FALSE;
        }
        if ($this->input->post('status') == '') {
            $data['input_error'][] = 'status';
            $data['error_string'][] = 'Status harus diisi';
            $data['status'] = FALSE;
        }

        if ($data['status'] == FALSE) {
            echo json_encode($data);
            exit();
        }
    }

    public function ajax_delete($no_mhs)
    {
        $this->blokir_model->delete_by_id($no_mhs);
        echo json_encode(array("status" => TRUE));
    }
}
