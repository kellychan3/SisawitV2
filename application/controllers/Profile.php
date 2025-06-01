<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        {
            is_logged_in();
        }
    }

	public function index()
{
    // Ambil data dari session
    $user = [
        'name' => $this->session->userdata('nama'),
        'email' => $this->session->userdata('email'),
        'role' => $this->session->userdata('role'),
        'id_user' => $this->session->userdata('id_user'),
    ];

    // Kirim data ke view
    $this->load->view('layout/header'); // jika kamu pakai layout
    $this->load->view('profile/profile', ['user' => $user]);
    $this->load->view('layout/footer');
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