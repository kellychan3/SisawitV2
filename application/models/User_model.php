<?php

class User_model extends CI_Model
{

    public $table = 'user';
    public $id = 'user.id_user';
    public function __construct()
    {
        parent::__construct();
    }

    public function fetch_all()
    {
        $this->db->order_by('name', 'ASC');
        return $this->db->get($this->table)->result_array();
    }

    public function fetch_single($id_user)
    {
        $this->db->where('id_user', $id_user);
        $query = $this->db->get('user');
        if ($query->result_array()) {
            return $query->result_array()[0];
        }
        return null;
    }

    public function fetch_user_in($ids)
    {
        $this->db->where_in('id_user', $ids);
        $query = $this->db->get('user');
        return $query->result_array();
    }
    
    public function asd($id_user)
    {
        $this->db->where('id_user', $id_user);
        $query = $this->db->get('user');
        if ($query->result_array()) {
            return $query->result_array()[0];
        }
        return null;
    }

    public function insert($data)
    {
        $this->db->insert('user', $data);
        $id_user = $this->db->insert_id();
        return $this->fetch_single($id_user);
    }

    public function insert1($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function login($email, $password)
    {
        $this->db->where('email', $email);
        $this->db->where('password', $password);
        $query = $this->db->get('user');
        if ($query->result_array()) {
            return $query->result_array()[0];
        }
        return null;
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
