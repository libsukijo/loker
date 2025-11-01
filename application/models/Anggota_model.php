<?php


class Anggota_model extends CI_Model
{
    var $table = 'anggota';
    var $column_order = array('no_mhs', 'nama', 'angkatan', 'status');
    var $column_search = array('no_mhs', 'nama', 'angkatan'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('no_mhs' => 'desc'); // default order 

    var $db_siprus;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->db_siprus = $this->load->database('siprus', TRUE);
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

    public function getAnggotaById($no_mhs)
    {
        $this->db_siprus->from($this->table);
        $this->db_siprus->where('no_mhs', $no_mhs);
        $query = $this->db->get();

        return $query->row();
    }

    public function getAnggota()
    {
        $this->db_siprus->from($this->table);
        $this->db_siprus->order_by('angkatan', 'desc');
        $query = $this->db_siprus->get();


        return $query->result();
    }


    public function getAnggotaByAngkatan($angkatan)
    {
        $this->db_siprus->from($this->table);
        $this->db_siprus->where('angkatan', $angkatan);
        $this->db_siprus->order_by('angkatan', 'desc');
        $query = $this->db_siprus->get();
        return $query->result();
    }



    public function getAnggotaByNim($nim)
    {
        $db2 = $this->load->database('siprus', TRUE);
        $this->db_siprus->from($this->table);
        $this->db_siprus->where('no_mhs', $nim);
        $this->db_siprus->join('fakultas', 'fakultas.kd_fakultas = anggota.kd_fakultas', 'left');
        $query = $this->db_siprus->get();
        return $query->row_array();
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

    public function getFakultas()
    {
        $this->db_siprus->from('fakultas');
        $query = $this->db_siprus->get();
        return $query->result();
    }

    public function update($where, $data)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }
}
