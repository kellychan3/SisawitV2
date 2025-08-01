<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prediksi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Prediksi_model');
        $this->load->helper('pentaho'); 
    }

    public function index()
    {
        $data['user'] = $this->get_user_session_data();

        $organisasi_id = $this->session->userdata('organisasi_id'); 
        $data['data_prediksi_tersedia'] = $this->Prediksi_model->has_prediksi_data($organisasi_id);

        $data['last_updated'] = $this->get_last_refresh_time($organisasi_id);
        $data['tahun_list']   = $this->Prediksi_model->get_available_years();

        $tahun = $this->input->get('tahun') ?? date('Y');
        $data['filter']['tahun'] = $tahun;

        $kebun_list = $this->Prediksi_model->get_all_kebun($organisasi_id);
        $data['kebun_list'] = !empty($kebun_list) ? $kebun_list : [['kebun' => '', 'nama_kebun' => 'Belum ada kebun']];

        $selected_kebun = $this->input->get('kebun') ?? [];
        $data['filter']['kebun'] = $selected_kebun;

        // Ambil data prediksi dan aktual
        $data['total_prediksi'] = $this->Prediksi_model->get_total_prediksi_by_year($tahun, $selected_kebun, $organisasi_id);
        $data['total_aktual']   = $this->Prediksi_model->get_total_aktual_by_year($tahun, $selected_kebun, $organisasi_id);
        $data['prediksi']       = $this->Prediksi_model->get_prediksi_bulanan($tahun, $selected_kebun, $organisasi_id);
        $data['aktual']         = $this->Prediksi_model->get_aktual_bulanan($tahun, $selected_kebun, $organisasi_id);

        if ($data['data_prediksi_tersedia']) {
            $aktual2024 = $this->Prediksi_model->get_aktual_bulanan('2024', $selected_kebun, $organisasi_id);
            $prediksi2025 = $this->Prediksi_model->get_prediksi_bulanan('2025', $selected_kebun, $organisasi_id);
            $aktual2025 = $this->Prediksi_model->get_aktual_bulanan('2025', $selected_kebun, $organisasi_id);

$data['timeline'] = [
    'labels' => [
        'Jan 24', 'Feb 24', 'Mar 24', 'Apr 24', 'Mei 24', 'Jun 24', 
        'Jul 24', 'Agu 24', 'Sep 24', 'Okt 24', 'Nov 24', 'Des 24',
        'Jan 25', 'Feb 25', 'Mar 25', 'Apr 25', 'Mei 25', 'Jun 25', 
        'Jul 25', 'Agu 25', 'Sep 25', 'Okt 25', 'Nov 25', 'Des 25'
    ],
    'aktual' => array_merge(
        array_values($aktual2024), 
        array_values($aktual2025)
    ),
    'prediksi' => array_merge(
        array_fill(0, 12, null), // Null untuk tahun 2024
        array_values($prediksi2025)
    ),
    'pointColors' => array_merge(
        array_fill(0, 12, '#1cc88a'), // Warna hijau untuk semua titik 2024
        array_map(function($aktual, $prediksi) {
            return ($aktual >= $prediksi) ? '#1cc88a' : '#e74a3b';
        }, $aktual2025, $prediksi2025)
    )
];
        }

        $this->load->view('layout/header', $data);
        $this->load->view('prediksi/prediksi', $data);
        $this->load->view('layout/footer');
    }

    public function refresh_data()
    {
        $id_user = $this->input->post('id_user');

       if (run_pentaho_job($id_user, 'main_job_2.kjb')) {
            $this->log_refresh($id_user);
            set_alert('success', 'Refresh berhasil!');
        } else {
            set_alert('danger', 'Refresh gagal. Cek log.');
        }

        redirect('prediksi');
    }

    private function get_user_session_data()
    {
        return [
            'email' => $this->session->userdata('email'),
            'nama'  => $this->session->userdata('nama'),
            'role'  => $this->session->userdata('role'),
        ];
    }

    private function get_last_refresh_time($organisasi_id)
    {
        $this->db->select('refreshed_at')
                 ->from('dashboard_prediksi_refresh_log')
                 ->where('id_organisasi', $organisasi_id)
                 ->order_by('refreshed_at', 'DESC')
                 ->limit(1);
        $query = $this->db->get();
        return $query->row() ? $query->row()->refreshed_at : null;
    }

    private function log_refresh($id_user)
    {
        $this->db->insert('dashboard_prediksi_refresh_log', [
            'id_organisasi' => $this->session->userdata('organisasi_id'),
            'id_user'       => $id_user,
            'refreshed_at'  => date('Y-m-d H:i:s'),
        ]);
    }
}
