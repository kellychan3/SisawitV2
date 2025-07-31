<?php
function is_logged_in()
{
    $ci = get_instance();
    if (!$ci->session->userdata('id_user')) {
        redirect('Authentication');
    }
}

function set_alert($type, $message)
{
    $ci = get_instance();
    $ci->session->set_flashdata('alert', [
        'type' => $type,
        'message' => $message
    ]);
}

function apiRequest($url, $method = 'GET', $data = null)
{
    $ci = get_instance();
    $token = $ci->session->userdata('token');

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $token",
            "Accept: application/json",
            "Content-Type: application/json"
        ],
    ]);

    if ($data) {
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }

    $response = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    return [$status, json_decode($response, true)];
}

