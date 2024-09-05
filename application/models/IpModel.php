<?php
class IpModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
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

    public function delete_ip($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('ip_addresses');
    }

    public function update_ping_status($id, $status, $down_time = null, $duration = 0)
    {
        $data = [
            'last_ping_status' => $status,
            'last_down_time' => $down_time,
            'down_duration' => $duration
        ];
        $this->db->where('id', $id);
        return $this->db->update('ip_addresses', $data);
    }

    public function get_ip_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('ip_addresses')->row();
    }
}
