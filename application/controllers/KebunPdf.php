<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KebunPdf extends CI_Controller
{
    public function index()
    {
	$this->load->model("Sawit_model");
        $this->load->library('pdf');
	$data['kebun'] = $this->Sawit_model->get();
        foreach ($data['kebun'] as $kebun) {
            $id = $kebun['id_kebun'];
            $value = array('type' => 'kebun', 'id' => intval($id));
            $this->Sawit_model->qr($id, json_encode($value));
        }
        $html = $this->load->view('pdf/GeneratePdfKebunView', $data, true);
        $this->pdf->createPDF($html, 'sisawit_kebun', false);
    }
}
