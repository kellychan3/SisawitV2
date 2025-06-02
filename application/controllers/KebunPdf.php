<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Dompdf\Dompdf;

class KebunPdf extends CI_Controller
{
    public function index()
    {
        $token = $this->session->userdata('token');

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

        $data['kebun'] = json_decode($response, true) ?: [];

        // Load view ke dalam string
        $html = $this->load->view('kebun/kebunpdf', $data, true);

        // Load Dompdf
        require_once FCPATH . 'vendor/autoload.php';
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Outputkan PDF
        $dompdf->stream("data_kebun.pdf", array("Attachment" => 1));
    }
}
