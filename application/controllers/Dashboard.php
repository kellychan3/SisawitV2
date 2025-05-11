<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct(); {
            is_logged_in();
            $this->load->model('Kebun_model');
            $this->load->model('Panen_model');
            $this->load->model('SystemLog_model');
            $this->load->model('Monitoring_model');
            $this->load->model('PohonLog_model');
        }
    }

    public function index()
    {
        $data['count_kebun'] = $this->Kebun_model->count_data();
        $data['luas_kebun'] = number_format($this->Kebun_model->luas_kebun(), 0, ',', '.');
        $data['total_panen_tahunan'] = $this->Panen_model->panen_tahunan();
        $data['detail_luas_kebun'] = $this->Kebun_model->detail_luas_kebun();
        $data['jumlah_panen'] = number_format($this->Panen_model->jumlah_panen(), 0, ',', '.');
        $data['count_total_monitoring'] = $this->Monitoring_model->count_data_total();
        $data['count_today_monitoring'] = $this->Monitoring_model->count_data_today();
        $data['count_total_systemlog'] = $this->SystemLog_model->count_data_total();
        $data['panenPerTahun'] = $this->Panen_model->get_panen_per_year_chart();
        $data['dataPerPohon'] = $this->PohonLog_model->get_last_data_per_pohon();

        // mengambil dari API Open weather map
        $lat = '0.8932008';  //0.8932008 0.5998689
        $long = '99.8310756';  //99.8310756 100.6135699
        $apiKey = '12e3bad62bb9cbec81a403269d19ae04'; //d1892c4134d10a835bea51234eea29e2
        $url = 'https://api.openweathermap.org/data/2.5/weather?lat={LATITUDE}&lon={LONGITUDE}&appid={API_KEY}&units=metric';
        $url = str_replace(['{LATITUDE}', '{LONGITUDE}', '{API_KEY}'], [$lat, $long, $apiKey], $url);
        $response = file_get_contents($url);
        $response = json_decode($response);
        $humidity = $response->main->humidity;
        $temp = $response->main->temp;
        $data['kelembapan'] = $humidity;
        $data['temperatur'] = $temp;

        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $this->load->view('layout/header', $data);
        $this->load->view('dashboard/home', $data);
        $this->load->view('layout/footer', $data);
    }
}
