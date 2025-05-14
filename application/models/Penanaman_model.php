<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penanaman_model extends CI_Model
{
    public $table = 'asset';
    public $id = 'aset.id';
    public function __construct()
    {
        parent::__construct();
    }
    public function get()
    {
        $this->db->from($this->table);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getBy()
    {
        $this->db->from($this->table);
        $this->db->where('email', $this->session->userdata('email'));
        $query = $this->db->get();
        return $query->row_array();
    }

}
