<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kebun extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        {
            is_logged_in();
            $this->load->model("Kebun_model");
        }
    }
	
	public function index()
	{
	        $data['kebun'] = $this->Kebun_model->get();
	        foreach ($data['kebun'] as $kebun) {
	            $id = $kebun['id_kebun'];
	            $value = array('type' => 'kebun', 'id' => intval($id));
	            
	        }
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$this->load->view('layout/header', $data);
		$this->load->view('kebun/kebun', $data);
		$this->load->view('layout/footer', $data);
	}
}
