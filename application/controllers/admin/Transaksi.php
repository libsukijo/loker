<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/core/BaseController.php';

class Transaksi extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('laporan_model');
        $this->load->model('tamu_model');
        $this->load->model('anggota_model');
        $this->load->model('kunci_model');
        $this->load->model('transaksi_model');
        $this->isLoggedIn();
    }


    public function index()
    {
        $data['page'] = 'Laporan';

        $this->load->view('dashboard/transaksi/peminjaman', $data);
    }

    public function ajax_list()
    {
        $list = $this->laporan_model->get_datatables();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $kunci) {
            $no++;
            $row = array();
            $row[] = $kunci->nama;
            $row[] = $kunci->no_loker;
            $row[] = $kunci->fakultas;
            $row[] = $kunci->tgl_pinjam;
            //add html for action
            $row[] = '';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->laporan_model->count_all(),
            "recordsFiltered" => $this->laporan_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function peminjaman()
    {
        $data['page'] = 'Peminjaman';
        $this->load->view('dashboard/transaksi/peminjaman', $data);
    }

    public function pengembalian()
    {
        $data['page'] = 'Pengembalian';
        $this->load->view('dashboard/transaksi/pengembalian', $data);
    }

    public function save_peminjaman()
    {
        $nim = $this->input->post('nim');
        $no_loker=$this->input->post('no_loker');

        $check_loker= $this->kunci_model->checkLoker($no_loker);// Check validasi barcode Kunci Loker
        if(count($check_loker) > 0 ){
            $this->check_transaksi_loker($no_loker);
            $checkNim = substr($nim, 0, 2);
            if ($checkNim == 'TM') {
                $this->tambahTransaksiTamu();
            } else {
                $get_data_anggota = $this->anggota_model->getAnggotaByNim($nim);
                $data = array(
                    'nim' => $this->input->post('nim'),
                    'id_anggota' => $this->input->post('nim'),
                    'nama' => $get_data_anggota['nama'],
                    'no_loker' => $this->input->post('no_loker'),
                    'fakultas' => $get_data_anggota['fakultas'],
                    'tgl_pinjam' => date('y-m-d H:i:s'),
                    'created_by' => $this->session->userdata('nama'),
                    'tgl_kembali' => NULL
                );
                //insert transaksi
                $insert = $this->kunci_model->addTransaksi($data);
    
                //insert log
                $data['message'] = 'BERHASIL MEMINJAM';
                $this->insert_loker_log($data, '1', 'pinjam');
                //end insert log
                
                $image=$this->getImage($this->input->post('nim'));
                echo json_encode(array('status' => 1, 'message' => 'Data Berhasil ditambah ', 'data_anggota' => $data,'image'=>$image));
            }
            
        }    
        else{
            echo json_encode(array('status' => 0, 'message' => 'No Barcode Tidak Valid'));
        }     
    }

    private function tambahTransaksiTamu(){
        $nim = $this->input->post('nim');
        $no_barcode=$this->input->post('no_loker');
        $data = array(
            'nim' => $this->input->post('nim'),
            'id_anggota' => $this->input->post('nim'),
            'nama' => substr($nim,3),
            'no_loker' => $this->input->post('no_loker'),
            'fakultas' => 'tamu',
            'tgl_pinjam' => date('y-m-d H:i:s'),
            'created_by' => $this->session->userdata('nama'),
            'tgl_kembali' => NULL
        );
        $this->kunci_model->addTransaksi($data);
        echo json_encode(array('status' => 1, 'message' => 'Data Berhasil ditambah '));
        exit();
    }

    private function check_transaksi_loker($no_barcode){
       
        $check= $this->kunci_model->getTransaksiByIdloker($no_barcode);
        if(count($check) > 0){
            echo json_encode(array('status' => 0, 'message' => 'loker Dalam Pinjaman'));
            exit();        
        }    
    }

    public function save_pengembalian()
    {
        $no_loker = $this->input->post('no_loker');
        $get_data_transaksi_by_id_loker = $this->kunci_model->getTransaksiByIdloker($no_loker);
        if (!empty($get_data_transaksi_by_id_loker)) {
            $nim = $get_data_transaksi_by_id_loker['nim'];
                $this->update_tr_anggota($nim);
        
        } else {
            echo json_encode(array('status' => 0, 'message' => ' Loker tidak valid / sudah dikembalikan'));
        }
    }

    private function update_tr_anggota($nim)
    {
        $no_loker = $this->input->post('no_loker');
        $data =  array(
            'tgl_kembali' => date('y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('nama'),
        );

        
      //cari ID transaksi
        $get_transaksi = $this->transaksi_model->getTransaksiYgBelumKembali($no_loker, $nim);
        // var_dump($get_transaksi);
        // die();
        //End cari ID transaksi

        $update = $this->kunci_model->updateTransaksi(array('no_loker' => $no_loker,'id'=>$get_transaksi ['id']), $data);
        $checkNim = substr($nim, 0, 2);
        if ($checkNim !== 'TM') {
            $get_data_anggota = $this->anggota_model->getAnggotaByNim($nim);
            $image=$this->getImage($nim);
            }else{  
                $get_data_anggota['nama']= substr($nim,3); 
                $get_data_anggota['no_mhs']=$nim;  
                $image='';
            }
            
        //insert log
        $data_log = array('message' => 'BERHASIL MENGEMBALIKAN', 'no_loker' => $no_loker, 'nama' => $get_data_anggota['nama'],'nim'=>$get_data_anggota['no_mhs']);
        $this->insert_loker_log($data_log, '1', 'kembali');
        //end insert log
        echo json_encode(array('status' => 1, 'data_anggota' => $get_data_anggota, 'message' => ' BERHASIL MENGEMBALIKAN','image'=>$image,));
    }

    public function realtime()
    {
        $data = array(
            'page' => 'Realtime'
        );
        $this->load->view('dashboard/transaksi/realtime', $data);
    }

    public function getLog()
    {
        $list = $this->transaksi_model->getRealtimeDashboard();
        //$list = $this->transaksi_model->getMonitoring();

        $data = array();
        foreach ($list as $log) {
            $image = $this->getImage($log->nim);
            if ($log->status == 2) {
                $loker = substr($log->no_loker, 3);
                $row[] = '<tr><td width="100px"><img class="profile-user-img img-fluid img-box" src="'.$image.'" alt="User profile picture" style="width:90px;height:100px"></div></td><td><h2 class="nama">' . $log->nama . '  <span class= "right badge badge-danger"> Gagal </span></h2><p>' . $log->keterangan . '<br/><b>Nomer ' . $loker . '</b></p></td></tr>';
            } else {
                $loker = substr($log->no_loker, 3);
                $row[] = '<tr><td width="100px"><img class="profile-user-img img-fluid img-box" src="'.$image.'" alt="User profile picture" style="width:90px;height:100px"></div></td><td><h2 class="nama">' . $log->nama . '  <span class= "right badge badge-success"> Berhasil </span></h2><p>' . $log->keterangan . '<br/><b>Nomer ' . $loker . '</b></p></td></tr>';
            }
            $data = $row;
        }

        $response = array(
            'data' => $data,
            'total_row' => count($list)
        );
        echo json_encode($response);
    }

    Public function monitoring(){
        $data['getTransaksiHariIni']=$this->transaksi_model->getTransaksiHariIniBelumKembali();
        $data['getTotalTransaksiHariIni']=count($this->kunci_model->getTransaksiHariIni());
	$data['getTransaksiHariIniBelumKembali']=count($this->transaksi_model->getTransaksiHariIniBelumKembali());
        $data['page'] = 'Monitoring Peminjaman Kunci Loker';     
        $this->load->view('dashboard/transaksi/monitoring', $data);      
    }

     Public function tanggungan(){
        $data['getTransaksiTanggungan']=$this->transaksi_model->getTransaksiTanggungan();
        $data['getTotalTransaksiTanggungan']=count($this->transaksi_model->getTransaksiTanggungan());
        $data['page'] = 'Tanggungan Peminjaman Kunci Loker';     
        $this->load->view('dashboard/transaksi/tanggungan', $data);      
    }
}

/* End of file Controllername.php */