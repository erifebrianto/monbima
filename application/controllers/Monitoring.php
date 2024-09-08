<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Monitoring_model');
    }

    // Method untuk menampilkan halaman utama
    public function index() {
        // Load view utama untuk monitoring
        $this->load->view('monitoring_view');
    }

    // Method untuk mendapatkan hasil ping terbaru (dengan AJAX)
    public function get_ping_status() {
        // Ambil semua IP yang tersimpan di database
        $ips = $this->Monitoring_model->get_all_ips();

        // Hasil ping untuk setiap IP
        $results = [];
        foreach ($ips as $ip) {
            $status = $this->Monitoring_model->ping($ip->ip_address);
            $this->Monitoring_model->save_ping_result($ip->ip_address, $status);
            $results[] = [
                'ip_address' => $ip->ip_address,
                'status' => $status
            ];
        }

        // Return data sebagai JSON
        echo json_encode($results);
    }

    // Method untuk menambah IP baru
    public function add_ip() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('ip_address', 'IP Address', 'required|valid_ip');

        if ($this->form_validation->run() === TRUE) {
            $ip_address = $this->input->post('ip_address');
            $this->db->insert('ip_addresses', ['ip_address' => $ip_address]);
            redirect('monitoring');
        } else {
            $this->load->view('add_ip_view');
        }
    }
}
