<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pemupukan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct(); {
            is_logged_in();
        }
    }

    public function index()
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $this->load->view('layout/header', $data);
        $this->load->view('pemupukan/pemupukan', $data);
        $this->load->view('layout/footer', $data);
    }
}
