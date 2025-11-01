<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/core/BaseController.php';

class Anggota extends BaseController
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('anggota_model');
        $this->isLoggedIn();
    }


    public function index()
    {
        $data['page'] = 'Anggota';
        $data['fakultas'] = $this->anggota_model->getFakultas();
        $this->load->view('dashboard/anggota/list', $data);
    }

    public function ajax_list()
    {
        $this->load->helper('url');
        $list = $this->anggota_model->get_datatables();
        $data = [];
        $no = $_POST['start'];

        foreach ($list as $anggota) {
            $no++;
            $row = [];
            $row[] = $anggota->no_mhs;
            $row[] = $anggota->nama;
            $row[] = $anggota->angkatan;
            //add html for action
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_anggota(' . "'" . $anggota->no_mhs . "'" . ')"><i class="fas fa-edit"></i> Edit</a>
            <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_anggota(' . "'" . $anggota->no_mhs . "'" . ')"><i class="glyphicon glyphicon-trash"></i> Delete</a>
            ';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->anggota_model->count_all(),
            "recordsFiltered" => $this->anggota_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_add()
    {

        $this->_validate();

        $data = [
            'nama' => $this->input->post('nama'),
            'no_mhs' => $this->input->post('no_mhs'),
            'angkatan' => $this->input->post('angkatan'),
            'kd_fakultas' => $this->input->post('fakultas'),
            'status' => $this->input->post('status')
        ];
        $insert = $this->anggota_model->save($data);
        echo json_encode(['status' => TRUE]);
    }

    public function ajax_edit($no_mhs)
    {
        $data = $this->anggota_model->getAnggotaById($no_mhs);
        echo json_encode($data);
    }

    public function ajax_update()
    {
        $this->_validate();

        $data = [
            'nama' => $this->input->post('nama'),
            'angkatan' => $this->input->post('angkatan'),
            'kd_fakultas' => $this->input->post('fakultas'),
            'status' => $this->input->post('status')
        ];
        $this->anggota_model->update(array('no_mhs' => $this->input->post('no_mhs')), $data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_delete($no_mhs)
    {
        $this->anggota_model->delete_by_id($no_mhs);
        echo json_encode(array("status" => TRUE));
    }


    public function _validate()
    {
        $data = [];
        $data['error_string'] = [];
        $data['input_error'] = [];
        $data['status'] = TRUE;

        if ($this->input->post('nama') == '') {
            $data['input_error'][] = 'nama';
            $data['error_string'][] = 'Nama harus diisi';
            $data['status'] = FALSE;
        }
        if ($this->input->post('no_mhs') == '') {
            $data['input_error'][] = 'no_mhs';
            $data['error_string'][] = 'No mhs harus diisi';
            $data['status'] = FALSE;
        }
        if ($this->input->post('angkatan') == '') {
            $data['input_error'][] = 'angkatan';
            $data['error_string'][] = 'Angkatan harus diisi';
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
}
