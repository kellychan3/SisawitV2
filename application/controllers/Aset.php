<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Aset extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        {
            is_logged_in();
            $this->load->model("Aset_model");
        }
    }

    public function index()
	{
        $data['asset'] = $this->Aset_model->get();
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$this->load->view('layout/header', $data);
		$this->load->view('aset/aset', $data);
		$this->load->view('layout/footer', $data);
	}

    public function addAset()
    {
        $data = [
            'namaaset' => $this->input->post('namaaset'),
            'lokasiaset' => $this->input->post('lokasiaset'),
            'jumlahaset' => $this->input->post('jumlahaset'),
        ];
        $this->Aset_model->insert($data);
        redirect('Aset');
    }

    public function editAset($id)
    {
        $data['user'] = $this->db->get_where('user', ['email' =>$this->session->userdata('email')])->row_array();
        $data = [
            'namaaset' => $this->input->post('namaaset'),
            'lokasiaset' => $this->input->post('lokasiaset'),
            'jumlahaset' => $this->input->post('jumlahaset'),
        ];
        $id = $this->input->post('id');
        $this->Aset_model->update(['id' => $id], $data);
        redirect('Aset');
    }

    public function deleteAset($id)
    {
        $this->Aset_model->delete($id);
        $error = $this->db->error();
        redirect('Aset');
    }
}
