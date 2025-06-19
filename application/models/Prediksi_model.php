<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prediksi_model extends CI_Model
{
    private $db;

    public function __construct()
{
    parent::__construct();
    $this->db = $this->load->database('default', TRUE);
}

}
