<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Log extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('PohonLog_model', 'pohon_log');
        $this->load->model('Monitoring_model', 'monitoring');
        $this->load->model('Pohon_model', 'pohon');
        $this->load->model('User_model', 'user');
    }

    public function index_get()
    {
        $id_pohon_log = $this->get('id_pohon_log');
        if ($id_pohon_log) {
            $data = $this->pohon_log->fetch_single($id_pohon_log);
            if ($data) {
                $this->response([
                    'status' => true,
                    'message' => 'Data log pohon ditemukan',
                    'data' => $data,
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Data log pohon tidak ditemukan',
                    'data' => null,
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $id_pohon = $this->get('id_pohon');
            $data = $this->pohon_log->fetch_all($id_pohon);
            $this->response([
                'status' => true,
                'message' => 'Data log pohon ditemukan',
                'data' => $data,
            ], REST_Controller::HTTP_OK);
        }

    }

    public function index_post()
    {
        if ($this->post('id_user')) {
            $idUser = $this->post('id_user');
        } else {
            $idUser = 1;
        }

        $data = array(
            'buah_tandan' => $this->post('buah_tandan'),
            'buah_tandan_mentah' => $this->post('buah_tandan_mentah'),
            'buah_tandan_matang' => $this->post('buah_tandan_matang'),
            'buah_tandan_segera_matang' => $this->post('buah_tandan_segera_matang'),
            'kondisi_daun' => $this->post('kondisi_daun'),
            'kondisi_batang' => $this->post('kondisi_batang'),
            'id_pohon' => $this->post('id_pohon'),
        );

        $response = $this->pohon_log->insert($data);

        $user = $this->user->fetch_single($idUser);
        $pohon = $this->pohon->fetch_single($this->post('id_pohon'));
        $this->monitoring->insert([
            'status' => $user['name'] . ' melakukan penginputan pohon ' . $pohon['nama'],
        ]);
        $this->response([
            'status' => true,
            'message' => 'Berhasil membuat log pohon',
            'data' => $response,
        ], REST_Controller::HTTP_CREATED);
    }

    public function index_put()
    {
        $id_pohon_log = $this->input->get('id_pohon_log');
        $data_pohon_log = $this->pohon_log->fetch_single($id_pohon_log);
        if (!$data_pohon_log) {
            return $this->response([
                'status' => false,
                'message' => 'Data log pohon tidak ditemukan',
            ], REST_Controller::HTTP_NOT_FOUND);
        }

        if ($this->put('buah_tandan')) {
            $data['buah_tandan'] = $this->put('buah_tandan');
        }

        if ($this->put('buah_tandan_mentah')) {
            $data['buah_tandan_mentah'] = $this->put('buah_tandan_mentah');
        }

        if ($this->put('buah_tandan_matang')) {
            $data['buah_tandan_matang'] = $this->put('buah_tandan_matang');
        }

        if ($this->put('buah_tandan_segera_matang')) {
            $data['buah_tandan_segera_matang'] = $this->put('buah_tandan_segera_matang');
        }

        if ($this->put('kondisi_daun')) {
            $data['kondisi_daun'] = $this->put('kondisi_daun');
        }

        if ($this->put('kondisi_batang')) {
            $data['kondisi_batang'] = $this->put('kondisi_batang');
        }

        $response = $this->pohon_log->update($id_pohon_log, $data);
        $this->response([
            'status' => true,
            'message' => 'Berhasil update log pohon',
            'data' => $response,
        ], REST_Controller::HTTP_OK);
    }

    public function index_delete()
    {

        $id_pohon_log = $this->get('id_pohon_log');
        $data_pohon_log = $this->pohon_log->fetch_single($id_pohon_log);
        if (!$data_pohon_log) {
            return $this->response([
                'status' => false,
                'message' => 'Data log pohon tidak ditemukan',
            ], REST_Controller::HTTP_NOT_FOUND);
        }

        $response = $this->pohon_log->delete($id_pohon_log);

        if ($response) {
            $this->response([
                'status' => true,
                'message' => 'Berhasil menghapus log pohon',
            ], REST_Controller::HTTP_NO_CONTENT);
        } else {
            return $this->response([
                'status' => false,
                'message' => 'Gagal menghapus log pohon',
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

}
