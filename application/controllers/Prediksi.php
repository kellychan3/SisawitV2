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
        $tahun_sebelumnya = $tahun - 1;
        
        $data['filter']['tahun'] = $tahun;
        $data['filter']['tahun_sebelumnya'] = $tahun_sebelumnya;
        
        $kebun_list = $this->Prediksi_model->get_all_kebun($organisasi_id);
        $data['kebun_list'] = !empty($kebun_list) ? $kebun_list : [['kebun' => '', 'nama_kebun' => 'Belum ada kebun']];

        $selected_kebun = $this->input->get('kebun') ?? [];
        $data['filter']['kebun'] = $selected_kebun;

        // Ambil data prediksi dan aktual
        $data['total_prediksi'] = $this->Prediksi_model->get_total_prediksi_by_year($tahun, $selected_kebun, $organisasi_id);
        $data['total_aktual']   = $this->Prediksi_model->get_total_aktual_by_year($tahun, $selected_kebun, $organisasi_id);
        $data['aktual_sebelumnya'] = $this->Prediksi_model->get_aktual_bulanan($tahun_sebelumnya, $selected_kebun, $organisasi_id);
        $data['aktual'] = $this->Prediksi_model->get_aktual_bulanan($tahun, $selected_kebun, $organisasi_id);
        $data['prediksi'] = $this->Prediksi_model->get_prediksi_bulanan($tahun, $selected_kebun, $organisasi_id);

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
