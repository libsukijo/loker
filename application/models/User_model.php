<?php

class User_model extends CI_Model
{
    var $db_siprus;
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->db_siprus = $this->load->database('siprus', TRUE);
    }


    public function checkUser($username)
    {
        $this->db_siprus->from('operator');
        $this->db_siprus->where('inisial', $username);
        $query = $this->db_siprus->get();
        return $query->row();
    }
}
