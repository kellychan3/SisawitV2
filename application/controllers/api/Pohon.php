<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Pohon extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('pohon_model', 'pohon');
    }

    public function index_get()
    {
        $id_pohon = $this->get('id_pohon');
        if ($id_pohon) {
            $data = $this->pohon->fetch_single($id_pohon);
            if ($data) {
                $this->response([
                    'status' => true,
                    'message' => 'Data pohon ditemukan',
                    'data' => $data,
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Data pohon tidak ditemukan',
                    'data' => null,
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $id_kebun = $this->get('id_kebun');
            $data = $this->pohon->fetch_all($id_kebun);
            $this->response([
                'status' => true,
                'message' => 'Data pohon ditemukan',
                'data' => $data,
            ], REST_Controller::HTTP_OK);
        }
    }

    public function index_post()
    {
        $data = array(
            'nama' => $this->post('nama'),
            'id_kebun' => $this->post('id_kebun'),
        );

        $response = $this->pohon->insert($data);
        $this->response([
            'status' => true,
            'message' => 'Berhasil membuat pohon',
            'data' => $response,
        ], REST_Controller::HTTP_CREATED);
    }

    public function index_put()
    {
        $id_pohon = $this->input->get('id_pohon');
        $data_pohon = $this->pohon->fetch_single($id_pohon);
        if (!$data_pohon) {
            return $this->response([
                'status' => false,
                'message' => 'Data pohon tidak ditemukan',
            ], REST_Controller::HTTP_NOT_FOUND);
        }

        if ($this->put('nama')) {
            $data['nama'] = $this->put('nama');
        }

        $response = $this->pohon->update($id_pohon, $data);
        $this->response([
            'status' => true,
            'message' => 'Berhasil update pohon',
            'data' => $response,
        ], REST_Controller::HTTP_OK);
    }

    public function index_delete()
    {
        $id_pohon = $this->get('id_pohon');
        $data_pohon = $this->pohon->fetch_single($id_pohon);
        if (!$data_pohon) {
            return $this->response([
                'status' => false,
                'message' => 'Data pohon tidak ditemukan',
            ], REST_Controller::HTTP_NOT_FOUND);
        }

        $response = $this->pohon->delete($id_pohon);

        if ($response) {
            $this->response([
                'status' => true,
                'message' => 'Berhasil menghapus pohon',
            ], REST_Controller::HTTP_NO_CONTENT);
        } else {
            return $this->response([
                'status' => false,
                'message' => 'Gagal menghapus pohon',
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

}
