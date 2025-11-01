<?php

class Kunci_model extends CI_Model {
	var $table = 'dftr_kunci';
	var $column_order = array('no_loker');
	var $column_search = array('no_loker'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('no_loker' => 'desc'); // default order

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query() {

		$this->db->from($this->table);

		$i = 0;

		foreach ($this->column_search as $item) // loop column
		{
			if ($_POST['search']['value']) // if datatable send POST for search
			{

				if ($i === 0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($this->column_search) - 1 == $i) //last loop
				{
					$this->db->group_end();
				}
				//close bracket
			}
			$i++;
		}

		if (isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order)) {
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	public function get_datatables() {
		$this->_get_datatables_query();
		if ($_POST['length'] != -1) {
			$this->db->limit($_POST['length'], $_POST['start']);
		}

		$query = $this->db->get();
		return $query->result();
	}

	public function count_filtered() {
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all() {
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function save($data) {
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data) {
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id) {
		$this->db->where('id', $id);
		$this->db->delete($this->table);
	}

	public function getKunciById($id) {
		$this->db->from($this->table);
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->row();
	}

	public function checkLoker($no_loker) {
		$this->db->from($this->table);
		$this->db->where('no_loker', $no_loker);
		$query = $this->db->get();		
		return $query->row_array();
	}

	public function checkLokerTransaksi($no_loker) {
		$this->db->from('dftr_kunci a');
		$this->db->join('recordtrx b','a.no_loker = b.no_loker','left');
		$this->db->where('b.no_loker', $no_loker);
		$this->db->where('b.tgl_kembali IS NULL');
		$this->db->order_by('b.tgl_kembali', 'desc');
		$this->db->limit(1);
		$query = $this->db->get();	
		return $query->row_array();
	}

	public function getTransaksiByIdloker($no_loker) {
		$this->db->from('recordtrx');
		$this->db->where('no_loker', $no_loker);
		$this->db->where('tgl_kembali IS NULL');
		$this->db->order_by('tgl_pinjam', 'desc');
		$query = $this->db->get();
		return $query->row_array();
	}

	public function checkPinjam($nim) {
		$this->db->from('recordtrx');
		$this->db->where('nim', $nim);
		$this->db->where('tgl_kembali IS NULL');
		$this->db->order_by('tgl_kembali', 'desc');
		$query = $this->db->get();
		return $query->row_array();
	}

	public function addTransaksi($data) {
		$this->db->insert('recordtrx', $data);
		return $this->db->insert_id();
	}

	public function updateTransaksi($where, $data) {
		$this->db->update('recordtrx', $data, $where);
		return $this->db->affected_rows();
	}

	public function totalLokerPria() {
		$this->db->from($this->table);
		$this->db->where('no_loker BETWEEN "LKR0000" AND "LKR0500"');
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function LkPriaPakai() {
		$this->db->from($this->table);
		$this->db->join('recordtrx', 'recordtrx.no_loker = dftr_kunci.no_loker');
		$this->db->where('recordtrx.tgl_kembali IS NULL');
		$this->db->where('dftr_kunci.no_loker BETWEEN "LKR0000" AND "LKR0500"');
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function LkPriaPakaiTamu() {
		$this->db->from($this->table);
		$this->db->join('recordtrktamu', 'recordtrx.no_loker = dftr_kunci.no_loker');
		$this->db->where('recordtrktamu.tgl_kembali IS NULL');
		$this->db->where('dftr_kunci.no_loker BETWEEN "LKR0001" AND "LKR0500"');
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function totalLokerWanita() {
		$lastRecord = 'LKR1025';
		$this->db->from($this->table);
		$this->db->where('dftr_kunci.no_loker BETWEEN "LKR0501" AND "' . $lastRecord . '"');
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function LkWanitaPakai() {
		$lastRecord = 'LKR1025';
		$this->db->from($this->table);
		$this->db->join('recordtrx', 'recordtrx.no_loker = dftr_kunci.no_loker');
		$this->db->where('recordtrx.tgl_kembali IS NULL');
		$this->db->where('dftr_kunci.no_loker BETWEEN "LKR0501" AND "' . $lastRecord . '"');
		$query = $this->db->get();
		return $query->num_rows();
	}
        
	public function getTransaksiHariIni() {
		$this->db->from('recordtrx');
		//$this->db->where('tgl_kembali IS NULL');
        $this->db->where('DATE(tgl_pinjam) = CURRENT_DATE()');

		$this->db->order_by('tgl_pinjam', 'desc');
				
		$query = $this->db->get();
		//echo $this->db->last_query();die();
		return $query->result_array();
	}
	
}