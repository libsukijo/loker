<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tas extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('anggota_model');
		$this->load->model('tas_model');
		$this->load->model('blokir_model');
	}

	public function index()
	{
		$this->load->view('pinjam');
	}

	public function tambahTransaksiTas()
	{
		$nim = $this->input->post('nim');
		$no_barcode_tas = $this->input->post('id_tas');

		$get_data_anggota = $this->anggota_model->getAnggotaByNim($nim);
		//validasi untuk pengecakan tas
		$check_tas = $this->tas_model->checkTas($no_barcode_tas);
		if ($check_tas == 1) {
			$check_transaksi_tas = $this->tas_model->getTransaksiByBarcode($no_barcode_tas);
			$this->check_pinjam($nim);
			if (count($check_transaksi_tas) == 0) {
				$data = array(
					'nim' => $nim,
					'id_anggota' => $nim,
					'nama' => $get_data_anggota['nama'],
					'no_barcode' => $no_barcode_tas,
					'fakultas' => $this->input->post('fakultas'),
					'tgl_pinjam' => date('y-m-d H:i:s'),
					'tgl_kembali' => NULL,
					'created_by' => '10.10.120.4',
				);
				$insert = $this->tas_model->addTransaksi($data);
				echo json_encode(array('status' => TRUE, 'message' => ' BERHASIL MEMINJAM', 'simbol' => 'fas fa-check-square'));
			} else {
				echo json_encode(array('status' => FALSE, 'message' => 'Tas Dalam Pinjaman', 'simbol' => 'fas fa-times'));
			}
		} else {
			echo json_encode(array('status' => FALSE, 'message' => 'No Barcode Tas Tidak Valid ', 'simbol' => 'fas fa-times'));
		}
	}

	private function insert_loker_log($data, $status)
	{
		$data = array(
			'nama' => $data['nama'],
			'nim' => $data['nim'],
			'status' => $status,
			'event' => 'pinjam',
			'keterangan' => $data['message'],
			'no_loker' => $data['no_loker'],
			'created_by' => '10.10.120.4'
		);
		$this->transaksi_model->insertLog($data);
	}


	private function check_pinjam($nim)
	{
		$check = $this->tas_model->checkPinjamTas($nim);
		if (count($check) > 0) {
			echo json_encode(array('status' => 0, 'message' => 'Anda Mempunyai tanggungan tas nomer ' . $check['no_barcode']));
			exit();
		}
	}


	// public function kembali()
	// {
	//     $barcode_tas = $this->input->post('id_tas');
	//     //jika id loker tidak ada input
	//     if ($barcode_tas != '') {
	// 		 	//validasi untuk pengecakan tas
	//      	$check_tas= $this->tas_model->checkTas($barcode_tas);
	// 		 if ($check_tas == 1) {
	// 			$get_data_transaksi_by_no_barcode = $this->tas_model->getTransaksiByBarcode($barcode_tas);

	// 			// check validasi loker
	// 			if(count($get_data_transaksi_by_no_barcode) > 1){
	// 				$nim = $get_data_transaksi_by_no_barcode['nim'];
	// 				$data =  array(
	// 					'tgl_kembali' => date('y-m-d H:i:s'),
	// 					'updated_by' => '10.10.120.4',
	// 				);
	// 				//update data
	// 				$update = $this->tas_model->updateTransaksi(array('no_barcode' => $barcode_tas), $data);
	// 				echo json_encode(array('status' => TRUE, 'message' => ' BERHASIL MENGEMBALIKAN', 'simbol' => 'fas fa-check-square'));
	// 			}
	// 			else{
	// 				echo json_encode(array('status' => FALSE, 'message' => 'TAS TIDAK DALAM PEMINJAMAN', 'simbol' => 'fas fa-times'));
	// 			}

	// 		 }
	// 		 else{
	// 			echo json_encode(array('status' => FALSE, 'message' => 'No Barcode Tas Tidak Valid', 'simbol' => 'fas fa-times'));
	// 		}



	//     }
	// }

	public function kembali()
	{
		$barcode_tas = $this->input->post('id_tas');
		//jika id loker tidak ada input
		if ($barcode_tas != '') {
			//validasi untuk pengecakan tas
			$check_tas = $this->tas_model->checkTas($barcode_tas);
			if ($check_tas == 1) {

				$get_data_transaksi_by_no_barcode = $this->tas_model->getTransaksiByBarcode($barcode_tas);

				//cari ID transaksi
				$get_transaksi_tas = $this->tas_model->getTransaksiTasYgBelumKembali($barcode_tas, $get_data_transaksi_by_no_barcode['nim']);
				//end cari ID transaksi


				// check validasi loker
				if (count($get_data_transaksi_by_no_barcode) > 1) {
					$nim = $get_data_transaksi_by_no_barcode['nim'];
					$data =  array(
						'tgl_kembali' => date('y-m-d H:i:s'),
						'updated_by' => '10.10.120.4',
					);
					//update data
					$update = $this->tas_model->updateTransaksi(array('no_barcode' => $barcode_tas, 'id' => $get_transaksi_tas['id']), $data);
					echo json_encode(array('status' => TRUE, 'message' => ' BERHASIL MENGEMBALIKAN', 'simbol' => 'fas fa-check-square'));
				} else {
					echo json_encode(array('status' => FALSE, 'message' => 'TAS TIDAK DALAM PEMINJAMAN', 'simbol' => 'fas fa-times'));
				}
			} else {
				echo json_encode(array('status' => FALSE, 'message' => 'No Barcode Tas Tidak Valid', 'simbol' => 'fas fa-times'));
			}
		}
	}
}
