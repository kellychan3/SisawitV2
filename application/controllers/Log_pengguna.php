<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Log_pengguna extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        {
            is_logged_in();
            $this->load->model("Log_pengguna_model");
        }
    }
	public function index()
	{
        $data['SystemLog'] = $this->Log_pengguna_model->get();
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$this->load->view('layout/header', $data);
		$this->load->view('log_pengguna/log_pengguna', $data);
		$this->load->view('layout/footer', $data);
	}
}