<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prediksi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Prediksi_model');
    }

    public function index()
    {
        $tahun = $this->input->get('tahun') ?? date('Y');

        $data['tahun'] = $tahun;
        $data['prediksi'] = $this->Prediksi_model->get_prediksi_by_year($tahun);
        $data['aktual'] = $this->Prediksi_model->get_aktual_by_year($tahun);
        $data['tahun_list'] = $this->Prediksi_model->get_tahun_list();

        $data['user'] = [
            'email' => $this->session->userdata('email'),
            'nama' => $this->session->userdata('nama'),
            'role' => $this->session->userdata('role'),
        ];

        $this->load->view('layout/header', $data);
        $this->load->view('prediksi/prediksi', $data);
        $this->load->view('layout/footer');
    }
}
