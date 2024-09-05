<?php
class IpModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database(); // Pastikan database diload
    }

    public function get_all_ips()
    {
        return $this->db->get('ip_addresses')->result();
    }

    public function insert_ip($ip_address)
    {
        $data = [
            'ip_address' => $ip_address,
        ];
        return $this->db->insert('ip_addresses', $data);
    }

    public function update_ping_status($id, $status)
    {
        $this->db->where('id', $id);
        return $this->db->update('ip_addresses', ['last_ping_status' => $status]);
    }
}
