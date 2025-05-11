<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Panen_model extends CI_Model
{
    public $table = 'panen';
    public $id = 'panen.id_panen';
    public function __construct()
    {
        parent::__construct();
    }

    public function fetch_single($id_panen)
    {
        $this->db->where('id_panen', $id_panen);
        $query = $this->db->get($this->table);
        if ($query->result_array()) {
            return $query->result_array()[0];
        }
        return null;
    }

    public function fetch_all($id_kebun = null)
    {
        $this->db->select("p.*, u.name AS name, GROUP_CONCAT(pu.name SEPARATOR ',') AS pemanen_names");
		$this->db->from('panen p');
        $this->db->join('user u', 'u.id_user = p.id_user');
        $this->db->join('user pu', 'FIND_IN_SET(pu.id_user, p.pemanen)');
		$this->db->group_by('p.id_panen');
        $this->db->order_by('p.tanggal_panen', 'ASC');
        if ($id_kebun) {
            $this->db->where('id_kebun', $id_kebun);
        }
        return $this->db->get()->result_array();
    }

    public function get()
    {
        $this->db->from($this->table);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function jumlah_panen()
    {
        $this->db->select_sum('jumlah_panen');
        $this->db->from($this->table);
        $this->db->where('tanggal_panen', 'CURRENT_DATE()', false);
        $query = $this->db->get();
        $result = $query->row();
        return $result->jumlah_panen;
    }

    public function get_panen_by_date()
    {
        $title = ['Tanggal Panen'];
        $this->db->order_by('id_kebun', 'ASC');
        $resultKebun = $this->db->get('kebun')->result_array();
        $select = 'tanggal_panen as `Tanggal Panen`,';
        foreach ($resultKebun as $kebun) {
            $select .= 'SUM(CASE WHEN k.id_kebun = ' . $kebun["id_kebun"] . ' THEN p.jumlah_panen ELSE 0 END) AS `' . $kebun["nama"] . '`,';
            $title[] = $kebun["nama"];
        }
        $select .= 'SUM(p.jumlah_panen) AS total_panen';
        $this->db->select($select);
        $this->db->from('panen p');
        $this->db->join('kebun k', 'p.id_kebun = k.id_kebun');
        $this->db->group_by('tanggal_panen');
        $query = $this->db->get();
        $result = $query->result_array();
        return ['title' => $title, 'data' => $result];
    }

    public function get_panen_per_month()
    {
        $title = ['Bulan Panen'];
        $this->db->order_by('id_kebun', 'ASC');
        $resultKebun = $this->db->get('kebun')->result_array();
        $select = "DATE_FORMAT(tanggal_panen, '%Y-%m') as `Bulan Panen`, DATE_FORMAT(tanggal_panen, '%Y-%m') as bulan_panen,";
        foreach ($resultKebun as $kebun) {
            $select .= 'SUM(CASE WHEN k.id_kebun = ' . $kebun["id_kebun"] . ' THEN p.jumlah_panen ELSE 0 END) AS `' . $kebun["nama"] . '`,';
            $title[] = $kebun["nama"];
        }
        $select .= 'SUM(p.jumlah_panen) AS total_panen';
        $this->db->select($select);
        $this->db->from('panen p');
        $this->db->join('kebun k', 'p.id_kebun = k.id_kebun');
        $this->db->group_by('bulan_panen');
        $query = $this->db->get();
        $result = $query->result_array();
        return ['title' => $title, 'data' => $result];
    }

    public function get_panen_per_year()
    {
        $title = ['Tahun Panen'];
        $this->db->order_by('id_kebun', 'ASC');
        $resultKebun = $this->db->get('kebun')->result_array();
        $select = "DATE_FORMAT(tanggal_panen, '%Y') as `Tahun Panen`, DATE_FORMAT(tanggal_panen, '%Y') as tahun_panen,";
        foreach ($resultKebun as $kebun) {
            $select .= 'SUM(CASE WHEN k.id_kebun = ' . $kebun["id_kebun"] . ' THEN p.jumlah_panen ELSE 0 END) AS `' . $kebun["nama"] . '`,';
            $title[] = $kebun["nama"];
        }
        $select .= 'SUM(p.jumlah_panen) AS total_panen';
        $this->db->select($select);
        $this->db->from('panen p');
        $this->db->join('kebun k', 'p.id_kebun = k.id_kebun');
        $this->db->group_by('tahun_panen');
        $query = $this->db->get();
        $result = $query->result_array();
        return ['title' => $title, 'data' => $result];
    }

    public function get_panen_per_year_chart()
    {
        $select = "DATE_FORMAT(tanggal_panen, '%Y') as tahun_panen,";
        $select .= 'SUM(p.jumlah_panen) AS total_panen';
        $this->db->select($select);
        $this->db->from('panen p');
        $this->db->join('kebun k', 'p.id_kebun = k.id_kebun');
        $this->db->order_by('tahun_panen', 'ASC');
        $this->db->group_by('tahun_panen');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function getBy()
    {
        $this->db->from($this->table);
        $this->db->where('email', $this->session->userdata('email'));
        $query = $this->db->get();
        return $query->row_array();
    }

    public function panen_tahunan()
    {
        $query = "SELECT DATE_FORMAT(tanggal_panen, '%Y') AS Periode, kebun.nama As Nama, SUM(jumlah_panen) As Total
        FROM panen
        Join kebun on panen.id_kebun = kebun.id_kebun
        group by date_format(tanggal_panen, '%Y'), kebun.id_kebun";
        $sql = $this->db->query($query);
        return $sql->result();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($where, $data)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }
}
