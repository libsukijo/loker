<?php

class Tas_model extends CI_Model {
	public $table = 'recordtas';
    public $column_order = array('no_barcode', 'nama', 'nim', 'fakultas', 'tgl_pinjam', 'tgl_kembali', 'created_by');
    public $column_search = array('no_barcode', 'nama', 'nim', 'fakultas', 'tgl_pinjam', 'tgl_kembali', 'created_by'); //set column field database for datatable searchable just firstname , lastname , address are searchable
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

	public function addTransaksi($data) {
     
		$this->db->insert('recordtas', $data);
        //echo $this->db->last_query();die();
		return true;
	}

	public function getTransaksiByBarcode($no_barcode) {
		$this->db->from('recordtas');
		$this->db->where('no_barcode', $no_barcode);
		$this->db->where('tgl_kembali IS NULL');
		$this->db->order_by('tgl_pinjam', 'desc');
				
		$query = $this->db->get();
		//echo $this->db->last_query();die();
		return $query->row_array();
	}

	public function updateTransaksi($where, $data) {
		$this->db->update('recordtas', $data, $where);
		return $this->db->affected_rows();
	}

	public function checkTas($no_barcode) {
		$this->db->from('dftr_tas');
		$this->db->where('no_barcode',$no_barcode);
		$query = $this->db->get();
		// echo $this->db->last_query();die();	
		return $query->num_rows();
	}
        public function getTransaksiHariIni() {
		$this->db->from('recordtas');
        $this->db->where('DATE(tgl_pinjam) = CURRENT_DATE()');

		$this->db->order_by('tgl_pinjam', 'desc');
				
		$query = $this->db->get();
		//echo $this->db->last_query();die();
		return $query->result_array();
	}

   public function checkPinjamTas($nim) {
		$this->db->from('recordtas');
		$this->db->where('nim', $nim);
		$this->db->where('tgl_kembali IS NULL');
		$this->db->order_by('tgl_kembali', 'desc');
		$query = $this->db->get();
		return $query->row_array();
	}



        public function getTransaksiTanggungan() {
		$this->db->from('recordtas');
        $this->db->where('DATE(tgl_pinjam) < CURRENT_DATE()');
         $this->db->where('tgl_kembali IS NULL');
		$this->db->order_by('tgl_pinjam', 'desc');
				
		$query = $this->db->get();
		//echo $this->db->last_query();die();
		return $query->result_array();
	}

	public function getTransaksiHariIniBelumKembali() {
		$this->db->from('recordtas');
        $this->db->where('tgl_kembali IS NULL');
        $this->db->where('DATE(tgl_pinjam) = CURRENT_DATE()');
		$this->db->order_by('tgl_pinjam', 'desc');
				
		$query = $this->db->get();
		//echo $this->db->last_query();die();
		return $query->result_array();
	}


        public function getTransaksiTasYgBelumKembali($no_tas, $nim)
    {
        $this->db->from('recordtas');
        $this->db->where('no_barcode', $no_tas);
        $this->db->where('nim', $nim);
        $this->db->where('tgl_kembali IS NULL ');

        $query = $this->db->get();
        // echo $this->db->last_query();
        // die();
        return $query->row_array();
    }



}