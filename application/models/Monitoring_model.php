<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring_model extends CI_Model {

    // Fungsi untuk melakukan ping ke alamat IP
    public function ping($host) {
        $output = null;
        $result = null;

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            exec("ping -n 1 $host", $output, $result);
        } else {
            exec("ping -c 1 $host", $output, $result);
        }

        return $result === 0 ? 'Online' : 'Offline';
    }

    // Fungsi untuk menyimpan atau memperbarui status ping di database
    public function save_ping_result($ip_address, $status) {
        // Cek apakah IP sudah ada di database
        $query = $this->db->get_where('ip_addresses', ['ip_address' => $ip_address]);

        if ($query->num_rows() > 0) {
            // Update status ping jika IP sudah ada
            $this->db->where('ip_address', $ip_address);
            $this->db->update('ip_addresses', ['last_ping_status' => $status]);
        } else {
            // Insert jika IP belum ada
            $this->db->insert('ip_addresses', [
                'ip_address' => $ip_address,
                'last_ping_status' => $status
            ]);
        }
    }

    // Fungsi untuk mengambil semua IP dari database
    public function get_all_ips() {
        $query = $this->db->get('ip_addresses');
        return $query->result();
    }
}
