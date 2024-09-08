<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Monitoring_model');
    }

    public function index() {
        // Daftar IP yang akan diping
        $ips = ['192.168.52.1', '192.168.23.1', '8.8.8.8', '1.1.1.1'];

        // Lakukan ping untuk setiap IP
        $data['results'] = [];
        foreach ($ips as $ip) {
            $data['results'][$ip] = $this->Monitoring_model->ping($ip);
        }

        // Load view dan kirim data hasil ping
        $this->load->view('monitoring_view', $data);
    }
}
