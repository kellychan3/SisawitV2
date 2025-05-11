<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Authentication extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model', 'userrole');
        $this->load->model('SystemLog_model');
    }

    public function index()
    {
        $this->load->view('layout/auth_header');
        $this->load->view('auth/login');
        $this->load->view('layout/auth_footer');
    }

    public function ceklogin()
    {
        $email = $this->input->post('email');
        $password = md5($this->input->post('password'));
        $user = $this->db->get_where('user', ['email' => $email])->row_array();

        if ($user) {
            if ($password == $user['password']) {
                $data = [
                    'email' => $user['email'],
                ];
                $currentDate = date('Y-m-d H:i:s');
                $this->SystemLog_model->insert([
                    'id_user' => $user['id_user'],
                    'value' => date('d/m/Y H:i:s : ', strtotime($currentDate)) . $user['name'] . ' login',
                    'date' => $currentDate,
                ]);
                $this->session->set_userdata($data);
                redirect('Dashboard');
                
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Wrong Password</div>');
                redirect('Authentication');
            }
        }
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Account is not registered</div>');
        redirect('Authentication');
    }

    public function logout()
    {
        $email = $this->session->userdata('email');
        $user = $this->db->get_where('user', ['email' => $email])->row_array();
        $currentDate = date('Y-m-d H:i:s');
        $data = [
            'email' => $user['email'],
        ];
        $this->SystemLog_model->insert([
            'id_user' => $user['id_user'],
            'value' => date('d/m/Y H:i:s : ', strtotime($currentDate)) . $user['name'] . ' Logout ',
            'date' => $currentDate,
        ]);
        $this->session->unset_userdata($data);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Logout Successful</div>');
        redirect('Authentication');
    }
}
