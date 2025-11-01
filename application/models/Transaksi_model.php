<?php

class Transaksi_model extends CI_Model {

	public $table = 'recordtrx';
    public $column_order = array('no_loker', 'nama', 'nim', 'fakultas', 'tgl_pinjam', 'tgl_kembali', 'created_by');
    public $column_search = array('no_loker', 'nama', 'nim', 'fakultas', 'tgl_pinjam', 'tgl_kembali', 'created_by'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    public $order = array('tgl_pinjam' => 'desc');
    public $db_siprus;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->db_siprus = $this->load->database('siprus', true);
    }

    private function _get_datatables_query()
    {

        $this->db->from($this->table);

        $i = 0;

        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {

                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) {
                    $this->db->group_end();
                }

            }
            $i++;
        }

        ## Search
        if (!empty($_POST['searchNama'])) {
            $this->db->where('nama like "%' . $_POST['searchNama'] . '%"');
        }

        if (!empty($_POST['searchNim'])) {
            $this->db->where('nim like "%' . $_POST['searchNim'] . '%"');
        }

        if (!empty($_POST['searchFakultas'])) {
            $this->db->where('fakultas like "%' . $_POST['searchFakultas'] . '%"');
        }

       if (!empty($_POST['searchTanggal'])) {
            $tgl = explode(" - ", $_POST['searchTanggal']);
            $tgl1 = date('Y-m-d', strtotime($tgl[0]));
            $tgl2 = date('Y-m-d', strtotime($tgl[1]));
            $this->db->where("tgl_pinjam BETWEEN '" . $tgl1 . " 00:00:00' and '" . $tgl2 . " 23:00:00'");
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    private function _get_datatables_kembali_query()
    {

        $this->db->from($this->table);
        $this->db->where('tgl_kembali IS NULL');

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_datatables_kembali()
    {
        $this->_get_datatables_kembali_query();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $query = $this->db->get();
        return $query->result();
    }

    public function count_kembali()
    {
        $this->_get_datatables_kembali_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function getData($postData)
    {

        $this->db->from($this->table);
        if ($postData['nama'] !== '') {
            $this->db->where('nama like "%' . $postData['nama'] . '%"');
        }

        if ($postData['nim'] !== '') {
            $this->db->where('nim like "%' . $postData['nim'] . '%"');
        }

        if ($postData['fakultas'] !== '') {
            $this->db->where('fakultas like "%' . $postData['fakultas'] . '%"');
        }

        if (!empty($postData['tanggal'])) {
            $tgl = explode(" - ", $postData['tanggal']);
            $tgl1 = date('Y-m-d', strtotime($tgl[0]));
            $tgl2 = date('Y-m-d', strtotime($tgl[1]));
            $this->db->where("tgl_pinjam BETWEEN '" . $tgl1 . " 00:00:00' and '" . $tgl2 . " 23:00:00'");
        }
    }

	public function getDataPengunjung($where) {
		$dateNow = date('Y-m-d');
		$this->db->select('COUNT(*) AS jumlah');
		$this->db->from($this->table);
		$this->db->where('fakultas = "' . $where . '"');
		$this->db->where('tgl_pinjam BETWEEN "'.$dateNow.' 00:00:00" AND "'.$dateNow.' 23:00:00" ');
		$query = $this->db->get();
		return $query->row();
	}

	public function dataFakultas() {
		$this->db_siprus->from('fakultas');
		$this->db_siprus->where('fakultas != "Pegawai" ');
		$this->db_siprus->group_by('fakultas');
		$query = $this->db_siprus->get();
		return $query->result_array();
	}

	public function getMonitoring($date = false) {
		$this->db->from('log_transaksi');
		if ($date) {
			$this->db->where('tgl_pinjam  BETWEEN "' . $date . ' 00:00:00"  and "' . $date . ' 21:00:00"');
		};
		$query = $this->db->get();
		return $query->result();
	}
    
    public function getRealtime()
    {
        $this->db->from('log_transaksi');
        $this->db->where('tgl_pinjam  BETWEEN NOW() - INTERVAL 45 MINUTE AND NOW()');
        $query = $this->db->get();
        return $query->result();
    }

    public function getRealtimeDashboard()
    {
        $this->db->from('log_transaksi');
        $this->db->where('tgl_pinjam  BETWEEN NOW() - INTERVAL 15 MINUTE AND NOW()');
        $query = $this->db->get();
        return $query->result();
    }

	public function insertLog($data) {
		$this->db->insert('log_transaksi', $data);
		return $this->db->insert_id();
	}

	public function getJumlahPengujungTanggal($fakultas, $start, $end)
    {
        $this->db->select('COUNT(*) as jumlah');
        $this->db->from('recordtrx');
        $this->db->where('tgl_pinjam BETWEEN "' . $start . ' 00:00:00" AND "' . $end . ' 23:00:00"');
        $this->db->where('fakultas = "' . $fakultas . '" ');
        $query = $this->db->get();
        return $query->row();
    }

    public function getTotal($date = false) {
		$this->db->from('recordtrx');
        if($date == true)
        {
            $this->db->where('tgl_pinjam = "'.$date.'"');
        }
   
		$query = $this->db->get();
		return $query->num_rows();
	}

    public function getDataPengunjungExcel($tanggal)
    {   
        $tanggal1 = explode("-", $tanggal);

       
        $start = date_create($tanggal1[0]);
        $start1 = date_format($start, "Y/m/d");
        $end = date_create($tanggal1[1]);
        $end1 = date_format($end, "Y/m/d");
        
         $query = $this->db->query('SELECT fakultas,COUNT(tgl_pinjam) AS jumlah FROM `recordtrx` WHERE tgl_pinjam BETWEEN "'.$start1.'" AND "'.$end1.'"  GROUP BY fakultas');          
         return $query->result();
    }

    public function getPengunjungDalamSeminggu()
    {
       // $query = $this->db->query('SELECT DATE(tgl_pinjam) AS tgl_pinjam,DATE_FORMAT(tgl_pinjam, "%D %M") AS tgl, COUNT(tgl_pinjam) AS jumlah FROM `recordtrx` WHERE DATE(tgl_pinjam) > (NOW() - INTERVAL 7 DAY) GROUP BY tgl_pinjam');
	        $query = $this->db->query('SELECT DATE_FORMAT(tgl_pinjam, "%Y-%m-%d") AS tgl, COUNT(tgl_pinjam) AS jumlah FROM `recordtrx` WHERE DATE(tgl_pinjam) > (NOW() - INTERVAL 7 DAY) GROUP BY tgl');
                
return $query->result_array();

    }
    public function getTransaksiPerHariIni()
    {
        $date = date('y-m-d');
        // $date = '23-02-14';

        $query = $this->db->query("SELECT * FROM log_transaksi WHERE  `status`= 1 AND (tgl_pinjam  BETWEEN '" . $date . " 00:00:00' AND '" . $date . " 23:00:00') ORDER BY tgl_pinjam DESC LIMIT 10");
        return $query->result();
    }

       public function getTransaksiTanggungan() {
	 $this->db->from('recordtrx');
        $this->db->where('DATE(tgl_pinjam) < CURRENT_DATE()');
         $this->db->where('tgl_kembali IS NULL');
		$this->db->order_by('tgl_pinjam', 'desc');
				
		$query = $this->db->get();
		//echo $this->db->last_query();die();
		return $query->result_array();
	}
 	
	public function getTransaksiHariIniBelumKembali() {
		$this->db->from('recordtrx');
        $this->db->where('tgl_kembali IS NULL');
        $this->db->where('DATE(tgl_pinjam) = CURRENT_DATE()');
		$this->db->order_by('tgl_pinjam', 'desc');
				
		$query = $this->db->get();
		//echo $this->db->last_query();die();
		return $query->result_array();
	}

    public function getTransaksiYgBelumKembali($no_loker, $nim)
    {
        $this->db->from('recordtrx');
        $this->db->where('no_loker', $no_loker);
        $this->db->where('nim', $nim);
        $this->db->where('tgl_kembali IS NULL ');

        $query = $this->db->get();
        // echo $this->db->last_query();
        // die();
        return $query->row_array();
    }
}