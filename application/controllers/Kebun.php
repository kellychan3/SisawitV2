<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kebun extends CI_Controller
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
        $token = $this->session->userdata('token');
        $organisasi_id = $this->session->userdata('organisasi_id');
        if (!$token || !$organisasi_id) redirect('authentication');

        // Ambil data aset
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "http://103.150.101.10/api/kebun",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $token",
                "Accept: application/json"
            ],
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        $allkebun = json_decode($response, true) ?: [];

        $data['kebun'] = $allkebun;
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->load->view('layout/header', $data);
        $this->load->view('kebun/kebun', $data);
        $this->load->view('layout/footer', $data);
    }
}
