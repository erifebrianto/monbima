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

    public function insert_ip($ip_address, $name)
    {
        $data = [
            'ip_address' => $ip_address,
            'name' => $name
        ];
        return $this->db->insert('ip_addresses', $data);
    }

    public function update_ip($id, $ip_address, $name)
    {
        $data = [
            'ip_address' => $ip_address,
            'name' => $name
        ];
        $this->db->where('id', $id);
        return $this->db->update('ip_addresses', $data);
    }

    public function get_ip_by_id($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('ip_addresses');
        return $query->row();
    }

    public function update_ping_status($id, $status)
    {
        $this->db->where('id', $id);
        return $this->db->update('ip_addresses', ['last_ping_status' => $status]);
    }
}
