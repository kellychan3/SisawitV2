<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Panen extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        {
            is_logged_in();
            $this->load->model("Panen_model");
        }
    }
	public function index()
	{
        $data['panenPerTanggal'] = $this->Panen_model->get_panen_by_date();
        $data['panenPerBulan'] = $this->Panen_model->get_panen_per_month();
        $data['panenPerTahun'] = $this->Panen_model->get_panen_per_year();
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$this->load->view('layout/header', $data);
		$this->load->view('panen/panen', $data);
		$this->load->view('layout/footer', $data);
	}
}
