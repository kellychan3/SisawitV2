<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sawit_model extends CI_Model
{
    public $table = 'kebun';
    public $id = 'kebun.id_kebun';

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

    public function qr($name, $value)
    {
        if ($name) {
            $filename = 'qr/kebun/' . $name . ".jpg";
            if (!file_exists($filename)) {
                $this->load->library('ciqrcode');
                $params['data'] = $value;
                $params['level'] = 'H';
                $params['size'] = 10;
                $params['savename'] = FCPATH . 'qr/kebun/' . $name . ".jpg";
                return $this->ciqrcode->generate($params);
            }
        }
    }
}
