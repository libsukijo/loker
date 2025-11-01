<?php

defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/core/BaseController.php';

class Transaksi_list extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('transaksi_model');
        $this->load->model('tamu_model');
        $this->isLoggedIn();
        // $this->load->library('excel');
    }


    public function index()
    {
        $data['fakultas'] = $this->tamu_model->getFakultas();
        $data['page'] = 'Transaksi List';

        $this->load->view('dashboard/transaksi/list', $data);
    }

    public function ajax_list()
    {
        $list = $this->transaksi_model->get_datatables();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $kunci) {
            $no++;
            $row = array();
            $row[] = $kunci->nama;
            $row[] = $kunci->nim;
            $no_loker=substr($kunci->no_loker,3);
            $row[] = $no_loker;
            $row[] = $kunci->fakultas;
            $row[] = $kunci->tgl_pinjam;
            $row[] = $kunci->created_by;
            $row[] = $kunci->tgl_kembali;
            $row[] = $kunci->updated_by; 
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->transaksi_model->count_all(),
            "recordsFiltered" => $this->transaksi_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}

/* End of file Controllername.php */
