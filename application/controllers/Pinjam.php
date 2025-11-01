<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/core/BaseController.php';

class Pinjam  extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('anggota_model');
		$this->load->model('kunci_model');
		$this->load->model('blokir_model');
		$this->load->model('kunci_model');
		$this->load->model('tamu_model');
		$this->load->model('transaksi_model');
		$this->load->library('curl');
	}

	public function index()
	{
		$totalLokerPria = $this->kunci_model->totalLokerPria();
		$totalLokerWanita = $this->kunci_model->totalLokerWanita();
		$LkPriaPakai = $this->kunci_model->LkPriaPakai();
		$LkWanitaPakai = $this->kunci_model->LkWanitaPakai();

		$data = array(
			'title' => 'PEMINJAMAN KUNCI LOKER',
			'total_loker_pria' => $totalLokerPria,
			'total_loker_wanita' => $totalLokerWanita,
			'sisa_loker_pria' => $totalLokerPria - $LkPriaPakai,
			'sisa_loker_wanita' => $totalLokerWanita - $LkWanitaPakai,
			'page' => 'PINJAM',
		);

		$this->load->view('pinjam', $data);
	}

	public function getAnggota()
	{
		$nim = $this->input->post('nim');
		$checkNoTamu = substr($nim, 0, 2);
		if ($nim !== '') {
			if ($checkNoTamu == "TM") {
				$this->checkTamu();
			} else {
				$this->checkMahasiswa();
			}
		}
	}

	private function checkMahasiswa()
	{
		$nim = $this->input->post('nim');
		$get_data_anggota = $this->anggota_model->getAnggotaByNim($nim);
		$totData = count($get_data_anggota);
		if ($totData > 0) {
			//get image from api
			$img = $this->getImage($nim);
			//add data in array 
			$get_data_anggota['img'] = $img;
			$get_data_anggota['anggota'] = 'mahasiswa';

			$check_pinjam = $this->kunci_model->checkPinjam($nim);

			$checkBlokir = $this->blokir_model->checkBlokir($nim);

			//if jika loker belum dikembalikan
			if (!empty($check_pinjam) && is_array($check_pinjam) && count($check_pinjam) > 0) {

				//remove alphabet
				if (preg_match("/[a-z]/i", $check_pinjam['no_loker'])) {
					$noLoker = preg_replace('~\D~', '', $check_pinjam['no_loker']);
				}
				//remove alphabet

				$message = 'BELUM MENGEMBALIKAN Loker no <b>' . $noLoker . '</b>! Silakan menghubungi petugas';

				//if jika anggota tidak aktif
			}
			//else if($get_data_anggota['status'] == 'P')
			//{
			//	$message = 'Status Anggota tidak aktif! Silakan menghubungi petugas';

			//}
			//if jika anggota Sudah bebas pustaka
			else if ($get_data_anggota['status'] == 'BP') {
				$message = 'Status Anggota sudah Bebas Pustaka';

				//if jika anggota masih R1
			}
			// else if($get_data_anggota['status'] == 'R1')
			// {
			// 	$message = 'Status Anggota Belum Aktif';

			//   //if jika anggota diblokir
			// }

			else if (is_array($checkBlokir) && count($checkBlokir) > 0) {
				$message = 'KEBLOKIR! Silakan menghubungi petugas';
			}
			//berhasil
			else {
				echo json_encode(array('status' => 1, 'data' => $get_data_anggota, 'message' => 'BERHASIL', 'simbol' => 'fas fa-check-square'));
				exit();
			}


			//insert log
			$this->insert_loker_log(array('message' => $message, 'nama' => $get_data_anggota['nama'], 'no_loker' => '', 'nim' => $this->input->post('nim')), 2, 'pinjam');

			echo json_encode(array('status' => 3, 'data' => $get_data_anggota, 'message' => $message, 'simbol' => 'fas fa-times'));
			exit();
		} else {
			//jika scan kartu anggota tidak valid
			if (preg_match("/[a-z]/i", $nim)) {
				$nim = preg_replace('~\D~', '', $nim);
			}
			$this->insert_loker_log(array('message' => 'Data Anggota Tidak Ada Di Siprus', 'nama' => '', 'no_loker' => '', 'nim' => $nim), 2);

			echo json_encode(array('status' => 0, 'message' => 'Data Tidak Ditemukan', 'simbol' => 'fas fa-times'));
		}
	}
	//

	private function checkTamu()
	{
		$barcode = $this->input->post('nim');
		$check_tamu_ada = $this->tamu_model->getTamuByBarcode($barcode);
		if ($check_tamu_ada != NULL) {
			$checkPinjamTamu = $this->kunci_model->checkPinjam($barcode);
			$data = $check_tamu_ada;
			$data['anggota'] = 'tamu';
			//berhasil
			if ($checkPinjamTamu == NULL) {
				echo json_encode(array('status' => 1, 'data' => $data, 'message' => 'BERHASIL', 'simbol' => 'fas fa-check-square'));
			}
			//if jika sudah meminjam
			else {
				$message = 'BELUM MENGEMBALIKAN! Silakan menghubungi petugas';
			}
			echo json_encode(array('status' => 0, 'message' => $message, 'simbol' => 'fas fa-times'));
		} else {
			echo json_encode(array('status' => 0, 'message' => 'Data tidak ditemukan', 'simbol' => 'fas fa-times'));
		}
	}

	public function tambahTransaksi()
	{
		$nim = $this->input->post();
		if ($nim !== '') {
			$nim = $this->input->post('nim');
			$checkNim = substr($nim, 0, 2);
			//validasi untuk pengecakan loker
			$check_loker = $this->kunci_model->checkLoker($this->input->post('id_loker'));
			$check_loker_transaksi = $this->kunci_model->checkLokerTransaksi($this->input->post('id_loker'));
			$get_data_anggota = $this->anggota_model->getAnggotaByNim($nim);
			//check kunci loker
			if (is_array($check_loker) && count($check_loker) > 0) {
				//check loker sedang dipakai atau tidak di tabel transaski
				if (is_array($check_loker_transaksi) && count($check_loker_transaksi) > 0) {
					//   var_dump($check_loker_transaksi);die();
					//insert log
					$log = array(
						'message' => 'SUDAH DIPAKAI <b>' . $check_loker_transaksi['nama'] . '-' . $check_loker_transaksi['nim'] . '</b>',
						'nama' => $get_data_anggota['nama'],
						'nim' => $this->input->post('nim'),
						'no_loker' => $this->input->post('id_loker'),
					);
					$this->insert_loker_log($log, '2', 'pinjam');
					//end inser log
					echo json_encode(array('status' => FALSE, 'message' => 'LOKER ' . $this->input->post('id_loker') . ' SUDAH DIPAKAI ' . $check_loker_transaksi['nama'] . '-' . $check_loker_transaksi['nim'], 'simbol' => 'fas fa-times'));
				} else {
					if ($checkNim == 'TM') {
						$this->tambahTransaksiTamu();
					} else {
						$data = array(
							'nim' => $this->input->post('nim'),
							'id_anggota' => $this->input->post('nim'),
							'nama' => $get_data_anggota['nama'],
							'no_loker' => $this->input->post('id_loker'),
							'fakultas' => $this->input->post('fakultas'),
							'tgl_pinjam' => date('y-m-d H:i:s'),
							'tgl_kembali' => NULL,
							'created_by' => '10.10.120.4',
						);

						$insert = $this->kunci_model->addTransaksi($data);
						//insert log
						$log = array(
							'message' => ' BERHASIL MEMINJAM',
							'nim' => $this->input->post('nim'),
							'nama' => $get_data_anggota['nama'],
							'no_loker' => $this->input->post('id_loker'),

						);
						$this->insert_loker_log($log, '1', 'pinjam');
						//end inser log
						echo json_encode(array('status' => TRUE, 'message' => ' BERHASIL MEMINJAM', 'simbol' => 'fas fa-check-square'));
					}
				}
			} else {
				//insert log
				$log = array(
					'message' => 'LOKER TIDAK VALID',
					'nama' => $get_data_anggota['nama'],
					'nim' => $this->input->post('nim'),
					'no_loker' => $this->input->post('id_loker'),
				);
				$this->insert_loker_log($log, '2', 'pinjam');
				//end inser log
				echo json_encode(array('status' => FALSE, 'message' => 'LOKER TIDAK VALID', 'simbol' => 'fas fa-times'));
			}
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
			'no_loker' => $this->input->post('id_loker'),
			'tgl_pinjam' => date('d-m-y'),
			'tgl_kembali' => NULL,
		);
		$insert = $this->kunci_model->addTransaksi($data);
		//insert log
		$log = array(
			'message' => ' BERHASIL MEMINJAM',
			'nama' => $getDataTamu['nama'],
			'nim' => $this->input->post('nim'),
			'no_loker' => $this->input->post('id_loker'),
		);
		$this->insert_loker_log($log, 1, 'pinjam');
		//end inser log
		echo json_encode(array('status' => TRUE, 'message' => ' BERHASIL MEMINJAM', 'simbol' => 'fas fa-check-square'));
	}
}
