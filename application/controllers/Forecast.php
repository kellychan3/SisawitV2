<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Forecast extends CI_Controller
{
    public function __construct()
    {
        parent::__construct(); 
            is_logged_in();
            $this->load->model("Panen_model");
            $this->load->model("Kebun_model");
    }

    public function index()
    {
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
        $data['predictions'] = [];
        $data['start_month'] = '';
        $data['end_month'] = '';

        $data['kebun'] = $this->Kebun_model->get();
        $data['panenPerBulan'] = $this->Panen_model->get_panen_per_month();
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $this->load->view('layout/header', $data);
        $this->load->view('forecast/forecast', $data);
        $this->load->view('layout/footer', $data);
    }

    public function predict()
    {
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

        $data['kebun'] = $this->Kebun_model->get();
        $data['panenPerBulan'] = $this->Panen_model->get_panen_per_month();
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $kebun = $_POST['kebun'];
        if($kebun == 'K1') 
        {
            $luas = 66300;
        }
        else if($kebun == 'K2')
        {
            $luas = 47172;
        }
        else if($kebun == 'K3')
        {
            $luas = 20200;
        }
        else if($kebun == 'K5')
        {
            $luas = 148000;
        }
        else if($kebun == 'K24')
        {
            $luas = 210000;
        }

        $suhu = $_POST['suhu'];
        $kelembapan = $_POST['kelembapan'];
        $totalpanen = $_POST['totalpanen'];
        $start_month = $_POST['start_month'];
        $end_month = $_POST['end_month'];

        // Prepare the input values as an array
        $input_values = [
            $luas,
            $suhu,
            $kelembapan,
            $totalpanen
        ];

        // Convert the input values to JSON string
        $input_values_json = json_encode($input_values);

        $pythonScriptPath = APPPATH . 'controllers\Predictions.py';

        // Prepare the command to execute
        $command = "python  " . escapeshellarg($pythonScriptPath) . " " . escapeshellarg(json_encode($start_month)) . " " . escapeshellarg(json_encode($end_month)) . " " . escapeshellarg($input_values_json);

        // Execute the command and get the output
        $output = shell_exec($command);

        $predictions = json_decode($output, true);

        // Display the predictions
        if ($predictions !== null) {
            $data['start_month'] = $start_month;
            $data['end_month'] = $end_month;
            $data['predictions'] = $predictions;
            $data['kebun'] = $kebun;
            $data['suhu'] = $suhu;
            $data['kelembapan'] = $kelembapan;
            $data['totalpanen'] = $totalpanen;

            $data['kebun'] = $this->Kebun_model->get();
            $data['panenPerBulan'] = $this->Panen_model->get_panen_per_month();
            $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

            $this->load->view('layout/header', $data);
            $this->load->view('forecast/forecast', $data);
            $this->load->view('layout/footer', $data);
        } else {
            echo "Error: Failed to decode predictions.";
        }
    }
}