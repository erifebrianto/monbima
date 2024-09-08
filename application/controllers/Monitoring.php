<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Monitoring_model');
        $this->load->library('form_validation');
    }

    public function index() {
        $this->load->view('monitoring_view');
    }

    public function get_ping_status() {
        $ips = $this->Monitoring_model->get_all_ips();
        $results = [];

        foreach ($ips as $ip) {
            $status = $this->Monitoring_model->ping($ip->ip_address);
            $this->Monitoring_model->save_ping_result($ip->ip_address, $status);
            $results[] = [
                'name' => $ip->name,
                'ip_address' => $ip->ip_address,
                'status' => $status
            ];
        }

        echo json_encode($results);
    }

    public function add_ip() {
        $this->form_validation->set_rules('ip_address', 'IP Address', 'required|valid_ip');
        $this->form_validation->set_rules('name', 'Name', 'required');

        if ($this->form_validation->run() === TRUE) {
            $ip_address = $this->input->post('ip_address');
            $name = $this->input->post('name');
            $this->db->insert('ip_addresses', ['ip_address' => $ip_address, 'name' => $name]);
            redirect('monitoring');
        } else {
            $this->load->view('monitoring_view');
        }
    }

    public function import_csv() {
        if (isset($_FILES['csv_file']['name'])) {
            $file = $_FILES['csv_file']['tmp_name'];

            // Buka file CSV
            if (($handle = fopen($file, "r")) !== FALSE) {
                fgetcsv($handle); // Lewati header

                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $name = $data[0];
                    $ip_address = $data[1];

                    // Validasi format IP sebelum insert
                    if (filter_var($ip_address, FILTER_VALIDATE_IP)) {
                        // Cek apakah IP sudah ada di database
                        $query = $this->db->get_where('ip_addresses', ['ip_address' => $ip_address]);

                        if ($query->num_rows() == 0) {
                            // Insert ke database jika IP belum ada
                            $this->db->insert('ip_addresses', [
                                'ip_address' => $ip_address,
                                'name' => $name
                            ]);
                        }
                    }
                }
                fclose($handle);
                redirect('monitoring');
            } else {
                echo "Error opening the file.";
            }
        }
    }
}
