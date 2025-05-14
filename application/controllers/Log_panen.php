<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Log_panen extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        {
            is_logged_in();
            $this->load->model("Log_panen_model");
        }
    }
	public function index()
	{
        $data['produktivitas'] = $this->Log_panen_model->get();
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$this->load->view('layout/header', $data);
		$this->load->view('log_panen/log_panen', $data);
		$this->load->view('layout/footer', $data);
	}
}