<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
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
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$this->load->view('layout/header', $data);
		$this->load->view('profile/profile', $data);
		$this->load->view('layout/footer', $data);
	}

    public function changePassword($id)
    {
        $data = [
            'password' => md5($this->input->post('password')),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $id = $this->input->post('id_user');
        $this->User_model->update(['id_user' => $id], $data);
        redirect('Profile');
    }
}