<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Kebun extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('kebun_model', 'kebun');
        $this->load->model('user_model', 'user');
    }

    public function index_get()
    {
        $id_kebun = $this->get('id_kebun');
        if ($id_kebun) {
            $data = $this->kebun->fetch_single($id_kebun);
            if ($data) {
                $this->response([
                    'status' => true,
                    'message' => 'Data kebun ditemukan',
                    'data' => $data,
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Data kebun tidak ditemukan',
                    'data' => null,
                ], REST_Controller::HTTP_NOT_FOUND);
            }

        } else {
            $data = $this->kebun->fetch_all();
            $this->response([
                'status' => true,
                'message' => 'Data kebun ditemukan',
                'data' => $data,
            ], REST_Controller::HTTP_OK);
        }
    }

    public function index_post()
    {
        $data = array(
            'nama' => $this->post('nama'),
            'luas' => $this->post('luas'),
            'tahun_tanam' => $this->post('tahun_tanam'),
            'jenis_tanah' => $this->post('jenis_tanah'),
            'desa' => $this->post('desa'),
            'kabupaten' => $this->post('kabupaten'),
            'kecamatan' => $this->post('kecamatan'),
            'sph' => $this->post('sph'),
        );

        $response = $this->kebun->insert($data);
        $this->response([
            'status' => true,
            'message' => 'Berhasil membuat kebun',
            'data' => $response,
        ], REST_Controller::HTTP_CREATED);
    }

    public function index_put()
    {
        $id_kebun = $this->input->get('id_kebun');
        $data_kebun = $this->kebun->fetch_single($id_kebun);
        if (!$data_kebun) {
            return $this->response([
                'status' => false,
                'message' => 'Data kebun tidak ditemukan',
            ], REST_Controller::HTTP_NOT_FOUND);
        }

        if ($this->put('nama')) {
            $data['nama'] = $this->put('nama');
        }

        if ($this->put('luas')) {
            $data['luas'] = $this->put('luas');
        }

        if ($this->put('tahun_tanam')) {
            $data['tahun_tanam'] = $this->put('tahun_tanam');
        }

        if ($this->put('jenis_tanah')) {
            $data['jenis_tanah'] = $this->put('jenis_tanah');
        }

        if ($this->put('desa')) {
            $data['desa'] = $this->put('desa');
        }

        if ($this->put('kabupaten')) {
            $data['kabupaten'] = $this->put('kabupaten');
        }

        if ($this->put('kecamatan')) {
            $data['kecamatan'] = $this->put('kecamatan');
        }

        if ($this->put('sph')) {
            $data['sph'] = $this->put('sph');
        }

        $response = $this->kebun->update($id_kebun, $data);
        $this->response([
            'status' => true,
            'message' => 'Berhasil update kebun',
            'data' => $response,
        ], REST_Controller::HTTP_OK);
    }

    public function index_delete()
    {
        $id_kebun = $this->get('id_kebun');
        $data_kebun = $this->kebun->fetch_single($id_kebun);
        if (!$data_kebun) {
            return $this->response([
                'status' => false,
                'message' => 'Data kebun tidak ditemukan',
            ], REST_Controller::HTTP_NOT_FOUND);
        }

        $response = $this->kebun->delete($id_kebun);

        if ($response) {
            $this->response([
                'status' => true,
                'message' => 'Berhasil menghapus kebun',
            ], REST_Controller::HTTP_NO_CONTENT);
        } else {
            return $this->response([
                'status' => false,
                'message' => 'Gagal menghapus kebun',
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

}