<?php

defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/core/BaseController.php';

class Transaksi_tas extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('transaksi_model');
        $this->load->model('anggota_model');
        $this->load->model('tamu_model');
        $this->load->model('tas_model');
        $this->isLoggedIn();
        // $this->load->library('excel');
    }


    public function index()
    {
        $data['fakultas'] = $this->tamu_model->getFakultas();
        $data['page'] = 'Transaksi Peminjaman Tas';

        $this->load->view('dashboard/transaksi_tas/list', $data);
    }

    public function ajax_list()
    {
        $list = $this->tas_model->get_datatables();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $kunci) {
            $no++;
            $row = array();
            $row[] = $kunci->nama;
            $row[] = $kunci->nim;
            $no_loker = substr($kunci->no_barcode, 3);
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
            "recordsTotal" => $this->tas_model->count_all(),
            "recordsFiltered" => $this->tas_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function peminjaman()
    {
        $data['page'] = 'Peminjaman Tas';
        $this->load->view('dashboard/transaksi_tas/peminjaman', $data);
    }

    public function pengembalian()
    {
        $data['page'] = 'Pengembalian Tas';
        $this->load->view('dashboard/transaksi_tas/pengembalian', $data);
    }

    public function save_peminjaman()
    {
        $nim = $this->input->post('nim');
           
        $check_tas= $this->tas_model->checkTas($this->input->post('barcode_tas'));// Check validasi barcode tas
        if ($check_tas > 0) {
            $this->check_transaksi_tas($this->input->post('barcode_tas')); // check transaksi tas
	    $this->check_pinjam($nim);//validasi transaksi jika sudah meminjam tidak boleh meminjam lagi
            $checkTamu = substr($nim, 0, 2);// jika peminjam selain pemustaka uin
            if ($checkTamu == 'TM') {
                $this->tambahTransaksiTamu();
            }
            $get_data_anggota = $this->anggota_model->getAnggotaByNim($nim);
            if (count($get_data_anggota) > 1) {
                $data = array(
                    'nim' => $this->input->post('nim'),
                    'id_anggota' => $this->input->post('nim'),
                    'nama' => $get_data_anggota['nama'],
                    'no_barcode' => $this->input->post('barcode_tas'),
                    'fakultas' => $get_data_anggota['fakultas'],
                    'tgl_pinjam' => date('y-m-d H:i:s'),
                    'created_by' => $this->session->userdata('nama'),
                    'tgl_kembali' => NULL
                );
                $this->tas_model->addTransaksi($data);
                $image=$this->getImage($nim);
                echo json_encode(array('status' => 1, 'message' => 'Data Berhasil ditambah ', 'data_anggota' => $data,'image'=>$image));
            } else {
                echo json_encode(array('status' => 0, 'message' => 'Data Anggota Tidak Ditemukan'));
            }
        }
        else {
            echo json_encode(array('status' => 0, 'message' => 'No Barcode Tidak Valid'));
        }
 
    }

    private function check_pinjam($nim){
        $check= $this->tas_model->checkPinjamTas($nim);
    //    var_dump($check['no_barcode']);die();
        if(count($check) > 0){
            echo json_encode(array('status' => 0, 'message' => 'Anda sudah meminjam tas nomer '.$check['no_barcode']));
            exit();        
        }    
    }

    private function check_transaksi_tas($no_barcode){
        $check= $this->tas_model->getTransaksiByBarcode($no_barcode);
        if(count($check) > 0){
            echo json_encode(array('status' => 0, 'message' => 'Tas Dalam Pinjaman'));
            exit();        
        }    
    }

    private function tambahTransaksiTamu(){
        $nim = $this->input->post('nim');
        $no_barcode=$this->input->post('barcode_tas');
        $data = array(
            'nim' => $nim,
            'id_anggota' => $nim,
            'nama' => substr($nim,3),
            'no_barcode' => $no_barcode,
            'fakultas' => 'tamu',
            'tgl_pinjam' => date('y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama'),
            'tgl_kembali' => NULL
        );
        $this->tas_model->addTransaksi($data);
        echo json_encode(array('status' => 1, 'message' => 'Data Berhasil ditambah '));
        exit();
    }



    public function save_pengembalian()
    {  
        $barcode_tas = $this->input->post('barcode_tas');
        $check_tas= $this->tas_model->checkTas($this->input->post('barcode_tas'));// Check validasi barcode tas
        if($check_tas > 0){
            $get_data_transaksi_by_no_barcode = $this->tas_model->getTransaksiByBarcode($barcode_tas);
            	// check validasi transaksi jika ada yang meminjam
				if(count($get_data_transaksi_by_no_barcode) > 1){
					$nim = $get_data_transaksi_by_no_barcode['nim'];
					$data =  array(
						'tgl_kembali' => date('y-m-d H:i:s'),
						'updated_by' => $this->session->userdata('nama')
					);
					//update data

                $id = $get_data_transaksi_by_no_barcode['id'];
                $update = $this->tas_model->updateTransaksi(array('no_barcode' => $barcode_tas, 'id' => $id), $data);
			
                    echo json_encode(array('status' => 1, 'message' => 'Transaksi Berhasil '));
				}
				else{
                    echo json_encode(array('status' => 0, 'message' => 'Tas Tidak Dalam Pinjaman'));
				}
        }
        else{
            echo json_encode(array('status' => 0, 'message' => 'No Barcode Tas Tidak Valid'));
        }
    }

   Public function monitoring(){
        $data['getTransaksiHariIni']=$this->tas_model->getTransaksiHariIniBelumKembali();
        $data['getTotalTransaksiHariIni']=count($this->tas_model->getTransaksiHariIni());
	$data['getTransaksiHariIniBelumKembali']=count($this->tas_model->getTransaksiHariIniBelumKembali());
        $data['page'] = 'Monitoring Peminjaman Tas';     
        $this->load->view('dashboard/transaksi_tas/monitoring', $data);      
    }

    Public function tanggungan(){
        $data['getTransaksiTanggungan']=$this->tas_model->getTransaksiTanggungan();
        $data['getTotalTransaksiTanggungan']=count($this->tas_model->getTransaksiTanggungan());
        $data['page'] = 'Tanggungan Peminjaman Tas';     
        $this->load->view('dashboard/transaksi_tas/tanggungan', $data);      
    }
}

/* End of file Controllername.php */