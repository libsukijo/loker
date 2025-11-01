<?php
require APPPATH . '/core/BaseController.php';

class Dashboard extends BaseController {

	public function __construct() {
		parent::__construct();
		$this->isLoggedIn();
		$this->load->model('transaksi_model');
		//Do your magic here
	}

	public function index() {
		$data['page'] = 'Dashboard';
		$data['transaksi'] = $this->transaksi_model->getTransaksiPerHariIni();
		$this->load->view('dashboard/index', $data);
	}

	public function getPeminjam()
    {
        $getData = $this->transaksi_model->getPengunjungDalamSeminggu();
		// echo "<pre>";
		// print_r($getData);die();

        $data = array(
            'status' => true,
            'data' => $getData,
        );

        echo json_encode($data);
    }

	public function transaksi_kembali_list()
    {
        $this->load->helper('url');
        $list = $this->transaksi_model->get_datatables_kembali();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $trx) {
            $no++;
            $row = array();
            $row[] = $trx->nama;
            $row[] = $trx->nim;
            $no_loker=substr($trx->no_loker,3);
            $row[] = $no_loker;
            $row[] = $trx->tgl_pinjam;
            if(empty($trx->tgl_kembali)){
                $row[] = '<b>Belum Dikembalikan</b>';
            }
            else{
                $row[] = $trx->tgl_kembali;
            }
            //add html for action

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->transaksi_model->count_kembali(),
            "recordsFiltered" => $this->transaksi_model->count_kembali(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}
