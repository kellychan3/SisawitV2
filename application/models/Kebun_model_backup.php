<?php

class Kebun_model_backup extends CI_Model
{
    public $table = 'kebun';
    public $id = 'kebun.id_kebun';
    public function __construct()
    {
        parent::__construct();
    }

    public function count_data()
    {
        $query = $this->db->get('kebun');
        return $query->num_rows();
    }

    public function luas_kebun()
    {
        $this->db->select_sum('luas', 'luas_kebun');
        $this->db->from($this->table);
        $query = $this->db->get();
        $result = $query->row();
        return $result->luas_kebun;
    }

    public function detail_luas_kebun() {
        $this->db->select('nama, luas');
        $query = $this->db->get($this->table);
        return $query->result();
    }
    

    // mengambil semua data kebun
    public function fetch_all()
    {
        $this->db->order_by('id_kebun', 'DESC');
        return $this->db->get('kebun')->result_array();
    }

    public function get()
    {
        $this->db->from($this->table);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function insert($data)
    {
        $this->db->insert('kebun', $data);
        $id_kebun = $this->db->insert_id();
        return $this->fetch_single($id_kebun);
    }

    public function fetch_single($id_kebun)
    {
        $this->db->where('id_kebun', $id_kebun);
        $query = $this->db->get('kebun');
        if ($query->result_array()) {
            return $query->result_array()[0];
        }
        return null;
    }

    public function update($id_kebun, $data)
    {
        $this->db->where('id_kebun', $id_kebun);
        $this->db->update('kebun', $data);
        return $this->fetch_single($id_kebun);
    }

    public function delete($id_kebun)
    {
        $this->db->where('id_kebun', $id_kebun);
        $this->db->delete('kebun');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
