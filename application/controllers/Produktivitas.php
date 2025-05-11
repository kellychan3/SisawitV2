<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produktivitas extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        {
            is_logged_in();
            $this->load->model("Produktivitas_model");
        }
    }
	public function index()
	{
        $data['produktivitas'] = $this->Produktivitas_model->get();
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$this->load->view('layout/header', $data);
		$this->load->view('produktivitas/produktivitas', $data);
		$this->load->view('layout/footer', $data);
	}
}