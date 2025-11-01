<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/core/BaseController.php';

class Kunci extends BaseController
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('kunci_model');
        $this->load->helper('url', 'form');
        $this->load->library("pdf");
        $this->isLoggedIn();
    }


    public function index()
    {   
        die('tes');
        $data['page'] = 'KUNCI';
        $this->load->view('dashboard/kunci/list', $data);
    }

    public function ajax_list()
    {
        $this->load->helper('url');
        $list = $this->kunci_model->get_datatables();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $kunci) {
            $no++;
            $row = array();
            $row[] = $kunci->no_loker;
            //add html for action
            // $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_kunci(' . "'" . $kunci->id . "'" . ')"><i class="fas fa-edit"></i> Edit</a>
            // <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_loker(' . "'" . $kunci->id . "'" . ')"><i class="fas fa-trash"></i> Delete</a>
            // ';

            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_kunci(' . "'" . $kunci->id . "'" . ')"><i class="fas fa-edit"></i> Edit</a>
            <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Cetak Barcode" onclick="cetak_barcode(' . "'" . $kunci->no_loker . "'" . ')"><i class="fas fa-print"></i> Cetak</a>
            ';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->kunci_model->count_all(),
            "recordsFiltered" => $this->kunci_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_add()
    {
        $this->_validate();

        $data = array(
            'no_loker' => $this->input->post('no_loker')
        );
        $insert = $this->kunci_model->save($data);
        echo json_encode(array('status' => TRUE));
    }

    public function ajax_edit($id)
    {
        $data = $this->kunci_model->getKunciById($id);
        echo json_encode($data);
    }

    public function ajax_update()
    {
        $this->_validate();

        $data = array(
            'no_loker' => $this->input->post('no_loker'),
        );
        $this->kunci_model->update(array('id' => $this->input->post('id')), $data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_delete($no_mhs)
    {
        $this->anggota_model->delete_by_id($no_mhs);
        echo json_encode(array("status" => TRUE));
    }


    public function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['input_error'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('no_loker') == '') {
            $data['input_error'][] = 'no_loker';
            $data['error_string'][] = 'Nomer loker diisi';
            $data['status'] = FALSE;
        }

        if ($data['status'] == FALSE) {
            echo json_encode($data);
            exit();
        }
    }

    public function cetakBarcode($id = null)
    {
        if ($id) {
            // Load library
            $this->load->library('zend');
            // Load in folder Zend
            $this->zend->load('Zend/Barcode');
            // Generate barcode

            $no_barcode = $id;

            $imageResource = Zend_Barcode::factory('code128', 'image', array('text' => $no_barcode, 'drawText' => false), array())->draw();
            $imageName = $no_barcode . '.jpg';
            $imagePath = 'assets/images/barcode/'; // penyimpanan file barcode
            imagejpeg($imageResource, $imagePath . $imageName);
            $pathBarcode = $imagePath . $imageName; //Menyimpan path image bardcode kedatabase

            $data = array(
                'no_barcode' => substr($no_barcode, 3),
                'image_barcode' => $pathBarcode,
            );
            $this->pdf->load_view('dashboard/kunci/barcode', $data);
            $this->pdf->render();
            $this->pdf->stream("NoBarode-" . $no_barcode . ".pdf");
        }
    }
}
