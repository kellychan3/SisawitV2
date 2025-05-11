<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sawit extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        {
            is_logged_in();
            $this->load->model("Sawit_model");
            $this->load->model("Pohon_model");
            $this->load->model("PohonLog_model");
        }
    }
	
	public function index()
	{
	        $data['kebun'] = $this->Sawit_model->get(); //database kebun
	        foreach ($data['kebun'] as $kebun) {
	            $id = $kebun['id_kebun'];
	            $value = array('type' => 'kebun', 'id' => intval($id));
	            $this->Sawit_model->qr($id, json_encode($value));
	        }
	        $data['pohon'] = $this->Pohon_model->get(); //database pohon
		foreach ($data['pohon'] as $pohon) {
	            $id = $pohon['id_pohon'];
	            $value = array('type' => 'pohon', 'id' => intval($id));
	            $this->Pohon_model->qr($id, json_encode($value));
	        }
        	$data['pohon_log'] = $this->PohonLog_model->get(); //database pohon log
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$this->load->view('layout/header', $data);
		$this->load->view('sawit/sawit', $data);
		$this->load->view('layout/footer', $data);
	}
}
