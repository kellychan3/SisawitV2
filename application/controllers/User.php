<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        {
            is_logged_in();
            $this->load->model("User_model");
        }
    }

    public function index()
	{
        $data['account'] = $this->User_model->get();
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$this->load->view('layout/header', $data);
		$this->load->view('user/user', $data);
		$this->load->view('layout/footer', $data);
	}
}
