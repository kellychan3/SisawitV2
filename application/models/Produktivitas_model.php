<?php

class Produktivitas_model extends CI_Model
{

    public $table = 'monitoring';
    public $id = 'monitoring.id_monitoring';

    public function fetch_all($limit = null)
    {
        $this->db->order_by('date', 'DESC');
        if($limit){
            $this->db->limit($limit);
        }
        return $this->db->get($this->table)->result();
    }

    public function get()
    {
        $this->db->from($this->table);
        $query = $this->db->get();
        return $query -> result_array();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        return $this->fetch_single($id);
    }

    public function fetch_single($id)
    {
        $this->db->where($this->id, $id);
        $query = $this->db->get($this->table);
        if ($query->result_array()) {
            return $query->result_array()[0];
        }
        return null;
    }

    public function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
        return $this->fetch_single($id);
    }

    public function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
