<?php

// representatif tabel pohon
class Pohon_model extends CI_Model
{
    public $table = 'pohon';
    public $id = 'pohon.id_pohon';

    public function fetch_single($id_pohon)
    {
        $this->db->select('pohon.id_pohon as id_pohon, pohon.nama as nama, pohon.id_kebun as id_kebun, kebun.nama as nama_kebun');
        $this->db->from('pohon');
        $this->db->join('kebun', 'pohon.id_kebun = kebun.id_kebun');
        $this->db->where('pohon.id_pohon', $id_pohon);
        $query = $this->db->get();
        if ($query->result_array()) {
            return $query->result_array()[0];
        }
        return null;
    }

    public function fetch_all($id_kebun = null)
    {
        $this->db->select('pohon.id_pohon as id_pohon, pohon.nama as nama, pohon.id_kebun as id_kebun, kebun.nama as nama_kebun');
        $this->db->from('pohon');
        $this->db->join('kebun', 'pohon.id_kebun = kebun.id_kebun');
        if ($id_kebun) {
            $this->db->where('pohon.id_kebun', $id_kebun);
        }
        $this->db->order_by('id_pohon', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get()
    {
        $this->db->from($this->table);
        $query = $this->db->get();
        return $query -> result_array();
    }

    public function getBy()
    {
        $this->db->from($this->table);
        $this->db->where('email', $this->session->userdata('email'));
        $query = $this->db->get();
        return $query->row_array();
    }

    public function insert($data)
    {
        $this->db->insert('pohon', $data);
        $id_pohon = $this->db->insert_id();
        return $this->fetch_single($id_pohon);
    }

    public function update($id_pohon, $data)
    {
        $this->db->where('id_pohon', $id_pohon);
        $this->db->update('pohon', $data);
        return $this->fetch_single($id_pohon);
    }

    public function delete($id_pohon)
    {
        $this->db->where('id_pohon', $id_pohon);
        $this->db->delete('pohon');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function qr($name, $value)
    {
        if ($name) {
            $filename = 'qr/pohon/' . $name . ".jpg";
            if (!file_exists($filename)) {
                $this->load->library('ciqrcode');
                $params['data'] = $value;
                $params['level'] = 'H';
                $params['size'] = 10;
                $params['savename'] = FCPATH . 'qr/pohon/' . $name . ".jpg";
                return $this->ciqrcode->generate($params);
            }
        }
    }
}
