<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/core/BaseController.php';


class Tamu extends BaseController
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('tamu_model');
        $this->load->helper('url', 'form');
        $this->load->library("pdf");
        $this->isLoggedIn();
    }

    public function index()
    {
        $data['page'] = 'TAMU';
        $this->load->view('dashboard/tamu/list', $data);
    }

    public function ajax_list()
    {

        $this->load->helper('url');
        $list = $this->tamu_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $tamu) {
            $no++;
            $row = array();
            $row[] = $tamu->nama;
            $row[] = $tamu->nik;
            $row[] = $tamu->instansi;
            if ($tamu->status == 1) {
                $row[] = 'Sedang Meminjam';
            } else {
                $row[] = 'belum Meminjam';
            }
            //add html for action
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_tamu(' . "'" . $tamu->id_tamu . "'" . ')"><i class="fas fa-edit"></i> Edit</a>
            <a class="btn btn-sm btn-success" href="javascript:void(0)"  onclick="window.open(' . "'" . site_url('admin/tamu/cetakBarcode/' . $tamu->id_tamu) . "'" . ',' . "'Cetak Kartu'" . ',' . "'width=600,height=200'" . ' )" title="Cetak barcode"  disabled="disabled"><i class="fas fa-print"></i> Cetak Barcode</a>
            <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_tamu(' . "'" . $tamu->id_tamu . "'" . ')"><i class="glyphicon glyphicon-trash"></i> Delete</a>
            ';

            $data[] = $row;
        }


        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->tamu_model->count_all(),
            "recordsFiltered" => $this->tamu_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_add()
    {

        $this->_validate();

        $data = array(
            'nama' => $this->input->post('nama'),
            'nik' => $this->input->post('nik'),
            'instansi' => $this->input->post('instansi'),
            'penjaminan' => $this->input->post('penjaminan'),
            // 'status' => 2,
            'created_date' => date('y-m-d h:i:s'),
            'created_by' => $this->session->userdata('nama'),
        );
        $insert = $this->tamu_model->save($data);
        echo json_encode(array('status' => TRUE));
    }

    public function ajax_edit($id)
    {
        $data = $this->tamu_model->getTamuById($id);
        echo json_encode($data);
    }

    public function ajax_update()
    {
        $this->_validate();

        $data = array(
            'nama' => $this->input->post('nama'),
            'nik' => $this->input->post('nik'),
            'instansi' => $this->input->post('instansi'),
            'penjaminan' => $this->input->post('penjaminan'),
            'updated_date' => date('y-m-d'),
            'updated_by' => $this->session->userdata('nama')
        );
        $this->tamu_model->update(array('id_tamu' => $this->input->post('id')), $data);
        echo json_encode(array("status" => TRUE));
    }

    public function _validate()
    {

        $data = array();
        $data['error_string'] = array();
        $data['input_error'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('nama') == '') {
            $data['input_error'][] = 'nama';
            $data['error_string'][] = 'Nama harus diisi';
            $data['status'] = FALSE;
        }
        if ($this->input->post('nik') == '') {
            $data['input_error'][] = 'nik';
            $data['error_string'][] = 'NIK harus diisi';
            $data['status'] = FALSE;
        }
        if ($this->input->post('penjaminan') == '') {
            $data['input_error'][] = 'penjaminan';
            $data['error_string'][] = 'penjaminan harus diisi';
            $data['status'] = FALSE;
        }
        if ($data['status'] == FALSE) {
            echo json_encode($data);
            exit();
        }
    }

    public function cetakBarcode($id = NULL)
    {
        if ($id) {
            // Load library
            $this->load->library('zend');
            // Load in folder Zend
            $this->zend->load('Zend/Barcode');
            // Generate barcode

            $data = $this->tamu_model->getTamuById($id);



            if ($data['status'] == 1) {
                $no_barcode = $data['no_barcode'];
            } else {
                $date = date('ymd');
                $no_antrian = $this->no_urut();
                $no_barcode = 'TM' . $date . '' . $no_antrian;
            }


            $imageResource = Zend_Barcode::factory('code128', 'image', array('text' => $no_barcode), array())->draw();
            $imageName = $no_barcode . '.jpg';
            $imagePath = 'assets/images/barcode/'; // penyimpanan file barcode
            imagejpeg($imageResource, $imagePath . $imageName);
            $pathBarcode = $imagePath . $imageName; //Menyimpan path image bardcode kedatabase

            $data_update = array(
                'no_barcode' => $no_barcode,
                'image_barcode' => $pathBarcode,
                'status' => 1
            );
            $update =  $this->tamu_model->update(array('id_tamu' => $id), $data_update);

            $data = array(
                'data' => $data,
            );
            $this->pdf->load_view('dashboard/tamu/kartu/layout', $data);
            $this->pdf->render();
            $this->pdf->stream("kartu-pinjam-" . $no_barcode . ".pdf");
        }
    }

    private function no_urut()
    {
        //check no urut
        $getNoUrut = $this->tamu_model->getNoUrut();

        if ($getNoUrut == NULL) {
            $no = 1;
        } else {
            $no_urut_temp = (int)$getNoUrut->no_urut;
            $no =  $no_urut_temp + 1;
        }
        $data = array(
            'no_urut' => $no
        );
        $this->tamu_model->SimpanNoUrut($data);
        return $no;
    }
}
