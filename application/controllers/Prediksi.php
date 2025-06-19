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
    if ($this->session->userdata('email')) {
        $data['user'] = [
            'email' => $this->session->userdata('email'),
            'nama' => $this->session->userdata('nama'),
            'role' => $this->session->userdata('role'),
        ];
    }

    $organisasi_id = $this->session->userdata('organisasi_id'); 

    $this->db->select('refreshed_at');
    $this->db->from('dashboard_prediksi_refresh_log');
    $this->db->where('id_organisasi', $organisasi_id);
    $this->db->order_by('refreshed_at', 'DESC');
    $this->db->limit(1);
    $query = $this->db->get();

    $data['last_updated'] = $query->row() ? $query->row()->refreshed_at : null;

    $data['tahun_list'] = $this->Prediksi_model->get_available_years();

    // Tangkap tahun dari dropdown (GET)
    $tahun = $this->input->get('tahun') ?? date('Y');
    $data['filter']['tahun'] = $tahun;

    $data['kebun_list'] = $this->Prediksi_model->get_all_kebun();

    // Tangkap kebun terpilih dari GET
    $selected_kebun = $this->input->get('kebun') ?? [];
    $data['filter']['kebun'] = $selected_kebun;

    // Ubah query total dan bulanan agar ikut filter kebun
    $data['total_prediksi'] = $this->Prediksi_model->get_total_prediksi_by_year($tahun, $selected_kebun);
    $data['total_aktual']   = $this->Prediksi_model->get_total_aktual_by_year($tahun, $selected_kebun);
    $data['prediksi']       = $this->Prediksi_model->get_prediksi_bulanan($tahun, $selected_kebun);
    $data['aktual']         = $this->Prediksi_model->get_aktual_bulanan($tahun, $selected_kebun);

    $this->load->view('layout/header', $data);
    $this->load->view('prediksi/prediksi', $data);
    $this->load->view('layout/footer');
}

    public function refresh_data()
    {
        $id_user = $this->input->post('id_user');
        $escaped_id_user = escapeshellarg($id_user);

        $command = "/opt/data-integration/kitchen.sh -file=/var/www/html/pentaho/main_job_2.kjb -param:id_user={$escaped_id_user}";

        exec($command, $output, $status);
        log_message('debug', 'Output: ' . print_r($output, true));
        log_message('debug', 'Status: ' . $status);

        if ($status === 0) {
        // Catat waktu refresh
        $this->db->insert('dashboard_prediksi_refresh_log', [
            'id_organisasi' => $this->session->userdata('organisasi_id'),
            'id_user' => $id_user,
            'refreshed_at' => date('Y-m-d H:i:s'),
        ]);

        $this->session->set_flashdata('message', 'Refresh berhasil!');
        }
        else {
            $this->session->set_flashdata('message', 'Refresh gagal. Cek log.');
        }

        redirect('prediksi');
    }
}
