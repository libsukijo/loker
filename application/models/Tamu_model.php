<?php

class Tamu_model extends CI_Model
{

    var $table = 'anggota_tamu';
    var $column_order = array('nama', 'nik', 'instansi');
    var $column_search = array('nama', 'nik', 'instansi'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('id_tamu' => 'desc'); // default order 
    var $db_siprus;


    public function __construct()
    {
        parent::__construct();
        $this->db_siprus = $this->load->database('siprus', TRUE);
        $this->load->database();
    }

    private function _get_datatables_query()
    {

        $this->db_siprus->from($this->table);

        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db_siprus->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db_siprus->like($item, $_POST['search']['value']);
                } else {
                    $this->db_siprus->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db_siprus->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db_siprus->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db_siprus->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db_siprus->limit($_POST['length'], $_POST['start']);
        $query = $this->db_siprus->get();
        return $query->result();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db_siprus->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db_siprus->from($this->table);
        return $this->db_siprus->count_all_results();
    }

    public function save($data)
    {
        $this->db_siprus->insert($this->table, $data);
        return $this->db_siprus->insert_id();
    }

    public function getTamuById($id)
    {
        $this->db_siprus->from($this->table);
        $this->db_siprus->where('id_tamu', $id);
        $query = $this->db_siprus->get();
        return $query->row_array();
    }


    public function getFakultas()
    {
        $this->db_siprus->from('fakultas');
        $this->db_siprus->group_by('fakultas');
        $query = $this->db_siprus->get();
        return $query->result_array();
    }


    public function getTamuByBarcode($barcode)
    {
        $this->db_siprus->from($this->table);
        $this->db_siprus->where('no_barcode', $barcode);
        $query = $this->db_siprus->get();
        return $query->row_array();
    }

    public function update($where, $data)
    {
        $this->db_siprus->update($this->table, $data, $where);
        return $this->db_siprus->affected_rows();
    }


    public function SimpanNoUrut($data)
    {
        $this->db->insert('no_urut', $data);
        return $this->db_siprus->insert_id();
    }

    public function getNoUrut()
    {
        $this->db->from('no_urut');
        $this->db->order_by('no_urut', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row();
    }
}
