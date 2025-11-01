<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_model extends CI_Model
{
    var $table = 'recordtrx';
    var $column_order = array('no_loker', 'nama', 'fakultas', 'tgl_pinjam');
    var $column_search = array('no_loker', 'nama', 'fakultas', 'tgl_pinjam'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('tgl_pinjam' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {

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
                    $this->db->group_end(); //close bracket
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
            $tgl1 = date('d-m-y', strtotime($tgl[0]));
            $tgl2 = date('d-m-y', strtotime($tgl[1]));
            $this->db->where("tgl_pinjam BETWEEN '" . $tgl1 . "' and '" . $tgl2 . "'");
        }



        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
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
            $tgl1 = date('d-m-y', strtotime($tgl[0]));
            $tgl2 = date('d-m-y', strtotime($tgl[1]));
            $this->db->where("tgl_pinjam BETWEEN '" . $tgl1 . "' and '" . $tgl2 . "'");
        }

        $query = $this->db->get();
        return $query->result();
    }
}

/* End of file ModelName.php */
