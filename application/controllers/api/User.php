<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class User extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model', 'user');
    }

    public function index_get()
    {
        $data = $this->user->fetch_all();
        $this->response([
            'status' => true,
            'message' => 'Data user ditemukan',
            'data' => $data,
        ], REST_Controller::HTTP_OK);
    }
}
