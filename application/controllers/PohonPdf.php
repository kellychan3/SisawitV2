<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PohonPdf extends CI_Controller
{
    public function index()
    {	
	$this->load->model("Kebun_model");
	$this->load->model("Pohon_model");
        $this->load->library('pdf');
	$id_kebun = $this->input->get('id_kebun');
	$data['kebun'] = $this->Kebun_model->fetch_single($id_kebun);
	$data['pohon'] = $this->Pohon_model->fetch_all($id_kebun);
        foreach ($data['pohon'] as $pohon) {
		$id = $pohon['id_pohon'];
        	$value = array('type' => 'pohon', 'id' => intval($id));
            	$this->Pohon_model->qr($id, json_encode($value));
        }
	$html = $this->load->view('pdf/GeneratePdfPohonView', $data, true);
	$this->pdf->createPDF($html, 'sisawit_pohon', false);
    }
}
