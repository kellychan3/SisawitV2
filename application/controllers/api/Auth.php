<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Auth extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model', 'user');
    }

    public function login_post()
    {
		$email = $this->post('email');
		$password = $this->post('password');
		$data = $this->user->login($email, md5($password));
        if ($data) {
            $this->response([
                'status' => true,
                'message' => 'Data user ditemukan',
                'data' => $data,
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Data user tidak ditemukan',
                'data' => null,
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function register_post()
    {
        $data = array(
			'name' => $this->post('name'),
			'email' => $this->post('email'),
			'password' => md5($this->post('password')),
		);

		$response = $this->user->insert($data);
		$this->response([
			'status' => true,
			'message' => 'Berhasil mendaftar',
			'data' => $response,
		], REST_Controller::HTTP_CREATED);
    }

}