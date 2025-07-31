<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lupa_sandi extends CI_Controller {

    private $api_base = 'http://103.150.101.10/api'; // Ganti sesuai kebutuhan

    public function index() {
        $this->load->view('layout/auth_header');
        $this->load->view('auth/lupa_sandi');
        $this->load->view('layout/auth_footer');
    }

    public function request_otp() {
        $email = $this->input->post('email');

        if (empty($email)) {
            $this->session->set_flashdata('error', 'Kolom email wajib diisi.');
            redirect('lupa_sandi');
        }

        $payload = json_encode([
            'email' => $email,
            'intent' => 'forget_password'
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->api_base . '/otp/request');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $result = json_decode($response, true);

    if ($http_code == 200) {
        $this->session->set_userdata('email', $email);
        redirect('lupa_sandi/verifikasi_otp');
    } elseif (isset($result['message']) && $result['message'] === 'Email tidak ditemukan') {
        $this->session->set_flashdata('error', 'Email tidak terdaftar.');
        redirect('lupa_sandi');
    } else {
        $this->session->set_flashdata('error', 'Gagal mengirim OTP. Silakan coba lagi.');
        redirect('lupa_sandi');
    }
}

    public function verifikasi_otp() {
        if (!$this->session->userdata('email')) {
        redirect('lupa_sandi');
        return;
    }

        $this->load->view('layout/auth_header');
        $this->load->view('auth/verifikasi_otp');
        $this->load->view('layout/auth_footer');
    }

    public function submit_otp() {
        $otp = $this->input->post('otp');
        $email = $this->session->userdata('email');

        if (!$email || !$otp) {
            $this->session->set_flashdata('error', 'OTP atau email tidak ditemukan.');
            redirect('lupa_sandi/verifikasi_otp');
            return;
        }

        $payload = json_encode([
            'email' => $email,
            'intent' => 'forget_password',
            'otp' => $otp
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->api_base . '/otp/verify');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = json_decode($response, true);

    if ($http_code == 200 && isset($result['reset_token'])) {
        $this->session->set_userdata('reset_token', $result['reset_token']);
        $this->session->set_flashdata('success', 'Kata sandi berhasil direset. Silakan isi kata sandi baru.');
        redirect('lupa_sandi/reset_password');
    } else {
        $error_message = $result['message'] ?? 'Gagal verifikasi OTP.';
        $this->session->set_flashdata('error', $error_message);
        redirect('lupa_sandi/verifikasi_otp');
    }
    }

    public function reset_password() {
        if (!$this->session->userdata('reset_token') || !$this->session->userdata('email')) {
        redirect('lupa_sandi');
        return;
    }

        $this->load->view('layout/auth_header');
        $this->load->view('auth/reset_password');
        $this->load->view('layout/auth_footer');
    }

    public function submit_reset_password() {
    $password = $this->input->post('password');
    $konfirmasi = $this->input->post('password_konfirmasi');
    $reset_token = $this->session->userdata('reset_token');
    $email = $this->session->userdata('email');

    // Validasi minimum panjang dan kecocokan
    if (strlen($password) < 6) {
        $this->session->set_flashdata('error', 'Password minimal 6 karakter.');
        redirect('lupa_sandi/reset');
        return;
    }
    
    if (!$email || !$reset_token) {
        $this->session->set_flashdata('error', 'Token atau email tidak ditemukan.');
        redirect('lupa_sandi/reset_password');
    }

    if ($password !== $konfirmasi) {
        $this->session->set_flashdata('error', 'Konfirmasi password tidak cocok.');
        redirect('lupa_sandi/reset_password');
    }

    $payload = json_encode([
        'email' => $email,
        'token' => $reset_token,
        'password' => $password
    ]);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->api_base . '/reset-password');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code === 200) {
        $this->session->unset_userdata(['reset_token', 'email']);
        $this->session->set_flashdata('success', 'Kata sandi berhasil diubah. Silakan login.');
        redirect('Authentication');
    } else {
        $this->session->set_flashdata('error', 'Gagal mengubah kata sandi. Silakan coba lagi.');
        redirect('lupa_sandi/reset_password');
    }
}

}
