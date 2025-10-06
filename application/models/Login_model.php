<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login_model extends CI_Model
{
    public function login_api($email, $password)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "http://160.187.144.173/api/login",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "no_hp" => $email,
                "password" => $password,
            ]),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return ['error' => true, 'message' => 'Koneksi ke server gagal!'];
        } else {
            return json_decode($response, true);
        }
    }
}
