<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prediksi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct(); {
            is_logged_in();
        }
     }

    public function index()
    {
        $data['user'] = [
            'email' => $this->session->userdata('email'),
            'nama' => $this->session->userdata('nama'),
            'role' => $this->session->userdata('role'),
        ];
        
        $this->load->view('layout/header', $data);
        $this->load->view('prediksi/prediksi', $data);
        $this->load->view('layout/footer', $data);
    }

}