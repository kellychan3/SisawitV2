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

    public function addUser()
    {
        $data = [
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'nomor_handphone' => $this->input->post('nomor_handphone'),
            'nik' => $this->input->post('nik'),
            'role' => $this->input->post('role'),
            'password' => md5($this->input->post('password')),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $this->User_model->insert($data);
        redirect('User');
    }

    public function editUser($id)
    {
        $data['user'] = $this->db->get_where('user', ['email' =>$this->session->userdata('email')])->row_array();
        $data = [
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'role' => $this->input->post('role'),
            'password' => md5($this->input->post('password')),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $id = $this->input->post('id_user');
        $this->User_model->update(['id_user' => $id], $data);
        redirect('User');
    }

    public function deleteUser($id)
    {
        $this->User_model->delete($id);
        $error = $this->db->error();
        redirect('User');
    }
}
