<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Panen extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Panen_model', 'panen');
        $this->load->model('Monitoring_model', 'monitoring');
        $this->load->model('Kebun_model', 'kebun');
        $this->load->model('User_model', 'user');
    }

    public function index_get()
    {
        $id_panen = $this->get('id_panen');
        if ($id_panen) {
            $data = $this->panen->fetch_single($id_panen);
            if ($data) {
                $this->response([
                    'status' => true,
                    'message' => 'Data panen ditemukan',
                    'data' => $data,
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Data panen tidak ditemukan',
                    'data' => null,
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $id_kebun = $this->get('id_kebun');
            $data = $this->panen->fetch_all($id_kebun);
            $this->response([
                'status' => true,
                'message' => 'Data panen ditemukan',
                'data' => $data,
            ], REST_Controller::HTTP_OK);
        }
    }

    public function index_post()
    {
        if($this->post('id_user')){
            $idUser = $this->post('id_user');
        }else{
            $idUser = 1;
        }
		if($this->post('pemanen')){
            $pemanen = $this->post('pemanen');
        }else{
            $pemanen = strval($idUser);
        }
        if($this->post('tanggal_panen')){
            $tanggalPanen = $this->post('tanggal_panen');
        }else{
            $tanggalPanen = date('Y-m-d');
        }

        $data = array(
            'id_kebun' => $this->post('id_kebun'),
            'jumlah_panen' => $this->post('jumlah_panen'),
            'tanggal_panen' => $tanggalPanen,
            'id_user' => $idUser,
            'pemanen' => $pemanen,
        );

        $user = $this->user->fetch_single($idUser);
		$idsPemanen = explode(",", $pemanen);
        $userPemanen = $this->user->fetch_user_in($idsPemanen);

		$namaPemanen = [];
		for ($i=0; $i < count($userPemanen); $i++) { 
			$namaPemanen[] = $userPemanen[$i]['name'];
		}

        $kebun = $this->kebun->fetch_single($this->post('id_kebun'));
        $response = $this->panen->insert($data);
        $this->monitoring->insert([
            'status' => $kebun['nama'] 
						. ' panen sebesar ' . $this->post('jumlah_panen') 
						.' kg oleh ' 
						. $user['name'] 
						. ' (' . implode(', ', $namaPemanen) . ')'
						. ' pada ' . $tanggalPanen,
        ]);
        $this->response([
            'status' => true,
            'message' => 'Berhasil membuat panen',
            'data' => $response,
        ], REST_Controller::HTTP_CREATED);
    }

    public function index_put()
    {
        $id_panen = $this->input->get('id_panen');
        $data_panen = $this->panen->fetch_single($id_panen);
        if (!$data_panen) {
            return $this->response([
                'status' => false,
                'message' => 'Data panen tidak ditemukan',
            ], REST_Controller::HTTP_NOT_FOUND);
        }

        if ($this->put('jumlah_panen')) {
            $data['jumlah_panen'] = $this->put('jumlah_panen');
        }

        $response = $this->panen->update($id_panen, $data);
        $this->response([
            'status' => true,
            'message' => 'Berhasil update panen',
            'data' => $response,
        ], REST_Controller::HTTP_OK);
    }

    public function index_delete()
    {

        $id_panen = $this->get('id_panen');
        $data_panen = $this->panen->fetch_single($id_panen);
        if (!$data_panen) {
            return $this->response([
                'status' => false,
                'message' => 'Data panen tidak ditemukan',
            ], REST_Controller::HTTP_NOT_FOUND);
        }

        $response = $this->panen->delete($id_panen);

        if ($response) {
            $this->response([
                'status' => true,
                'message' => 'Berhasil menghapus panen',
            ], REST_Controller::HTTP_NO_CONTENT);
        } else {
            return $this->response([
                'status' => false,
                'message' => 'Gagal menghapus panen',
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

}
