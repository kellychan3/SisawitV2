<?php

class PohonLog_model extends CI_Model
{
    
    public $table = 'pohon_log';
    public $id = 'pohon_log.id_pohon_log';

    public function fetch_all($id_pohon = null)
    {
        $this->db->order_by('id_pohon_log', 'DESC');
        if ($id_pohon) {
            $this->db->where('id_pohon', $id_pohon);
		}
        return $this->db->get('pohon_log')->result_array();
    }

    public function get_last_data_per_pohon() {
		$this->db->select('pl.*, k.nama AS kebun, MAX(pl.tanggal_pemeriksaan) AS last_pemeriksaan');
		$this->db->from('pohon_log pl');
		$this->db->join('pohon p', 'p.id_pohon = pl.id_pohon');
		$this->db->join('kebun k', 'p.id_kebun = k.id_kebun');
		$this->db->group_by('pl.id_pohon');
		$query = $this->db->get();
		return $query->result();
	}

    public function get()
    {
        $this->db->from($this->table);
        $query = $this->db->get();
        return $query -> result_array();
    }

    public function insert($data)
    {
        $this->db->insert('pohon_log', $data);
		$id_pohon_log = $this->db->insert_id();
		return $this->fetch_single($id_pohon_log);
    }

    public function fetch_single($id_pohon_log)
    {
        $this->db->where('id_pohon_log', $id_pohon_log);
        $query = $this->db->get('pohon_log');
		if($query->result_array()){
			return $query->result_array()[0];
		}
        return null;
    }

    public function update($id_pohon_log, $data)
    {
        $this->db->where('id_pohon_log', $id_pohon_log);
        $this->db->update('pohon_log', $data);
		return $this->fetch_single($id_pohon_log);
    }

    public function delete($id_pohon_log)
    {
        $this->db->where('id_pohon_log', $id_pohon_log);
        $this->db->delete('pohon_log');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
