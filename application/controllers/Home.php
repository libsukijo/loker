<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('kunci_model');
		$this->load->model('transaksi_model');
	}

	public function index() {
		$totalLokerPria = $this->kunci_model->totalLokerPria();
		$totalLokerWanita = $this->kunci_model->totalLokerWanita();
		$LkPriaPakai = $this->kunci_model->LkPriaPakai();
		$LkWanitaPakai = $this->kunci_model->LkWanitaPakai();

		$data = array(
			'title' => 'DEALKEY KUNCI LOKER',
			'total_loker_pria' => $totalLokerPria,
			'total_loker_wanita' => $totalLokerWanita,
			'sisa_loker_pria' => $totalLokerPria - $LkPriaPakai,
			'sisa_loker_wanita' => $totalLokerWanita - $LkWanitaPakai,
			'page' => 'HOME',
		);
		$this->load->view('home', $data);
	}

	public function getStatistik() {
		$getDataFakultas = $this->transaksi_model->dataFakultas();
		$tes = array();
		$i=0;
		foreach ($getDataFakultas as $key => $value) {
			$jum = $this->transaksi_model->getDataPengunjung($value['fakultas']);
			$totData=(int) $jum->jumlah;
			if($totData !== 0){				
				$tes[$i]['fakultas'] = $value['fakultas'];
			    $tes[$i]['jumlah'] = (int) $jum->jumlah;

				if($value['fakultas'] == 'Adab dan Ilmu Budaya')
				{
					$tes[$i]['color']= '#FEF552'; //fakultas Adab dan Ilmu Budaya
				}
				if($value['fakultas'] == 'Dakwah dan Komunikasi'){ //Dakwah dan Komunikasi
					$tes[$i]['color']= '#D29E6F';
				}
				if($value['fakultas'] == 'Dosen'){ // Dosen
					$tes[$i]['color']= '#3B3A42';
				} 
				if($value['fakultas'] == 'Ekonomi dan Bisnis Islam'){ //fakultas Ekonomi dan Bisnis Islam
					$tes[$i]['color']= '#FF5E10';
				}
				if($value['fakultas'] == 'Ilmu Sosial dan Humaniora'){ //fakultas Ilmu Sosial dan Humaniora
					$tes[$i]['color']= '#CE85CE';
				}
				if($value['fakultas'] == 'Ilmu Tarbiyah dan Keguruan'){ //fakultas Ilmu Tarbiyah dan Keguruan
					$tes[$i]['color']= '#4bf707';
				}
				if($value['fakultas'] == 'Pascasarjana'){ //fakultas Pascasarjana
					$tes[$i]['color']= 'red';
				}
				if($value['fakultas'] == 'SAINS DAN TEKNOLOGI'){ //fakultas SAINS DAN TEKNOLOGI
					$tes[$i]['color']= '#295ED2';
				}
				if($value['fakultas'] == "Syari'ah dan Hukum"){
					$tes[$i]['color']= '#2A292F'; //fakultas Syari'ah dan Hukum
				}
				if($value['fakultas'] == 'Ushuluddin dan Pemikiran Islam'){ //fakultas Ushuluddin dan Pemikiran Islam 
					$tes[$i]['color']= '#26dbef';
				}
					$i++;
			}
		}

		$data = array(
			'status' => true,
			'data' => $tes,
		);
		echo json_encode($data);
	}
}
