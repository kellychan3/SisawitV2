<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Asset extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        {
            is_logged_in();
            $this->load->model("Asset_model");
        }
    }

    public function index()
	{
        $data['asset'] = $this->Asset_model->get();
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$this->load->view('layout/header', $data);
		$this->load->view('asset/asset', $data);
		$this->load->view('layout/footer', $data);
	}

    public function addAsset()
    {
        $data = [
            'namaaset' => $this->input->post('namaaset'),
            'lokasiaset' => $this->input->post('lokasiaset'),
            'jumlahaset' => $this->input->post('jumlahaset'),
        ];
        $this->Asset_model->insert($data);
        redirect('Asset');
    }

    public function editAsset($id)
    {
        $data['user'] = $this->db->get_where('user', ['email' =>$this->session->userdata('email')])->row_array();
        $data = [
            'namaaset' => $this->input->post('namaaset'),
            'lokasiaset' => $this->input->post('lokasiaset'),
            'jumlahaset' => $this->input->post('jumlahaset'),
        ];
        $id = $this->input->post('id');
        $this->Asset_model->update(['id' => $id], $data);
        redirect('Asset');
    }

    public function deleteAsset($id)
    {
        $this->Asset_model->delete($id);
        $error = $this->db->error();
        redirect('Asset');
    }
}
