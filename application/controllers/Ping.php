<?php
class Ping extends CI_Controller
{
    private $telegramToken = '1871492308:AAFodYb2KexgZHokKYw6qTpzyYGeC_n2SRI'; // Ganti dengan token bot Telegram Anda
    private $chatId = '-4504951482'; // Ganti dengan ID chat Telegram Anda
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('IpModel');
        $this->load->helper('url');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['ips'] = $this->IpModel->get_all_ips();
        $this->load->view('ping/index', $data);
    }

    public function add_ip()
    {
        $this->form_validation->set_rules('ip_address', 'IP Address', 'required|valid_ip');
        $this->form_validation->set_rules('name', 'Name', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('ping/add_ip');
        } else {
            $ip_address = $this->input->post('ip_address');
            $name = $this->input->post('name');
            $this->IpModel->insert_ip($ip_address, $name);
            redirect('ping/index');
        }
    }

    public function edit_ip($id)
    {
        $data['ip'] = $this->IpModel->get_ip_by_id($id);

        if (empty($data['ip'])) {
            show_404();
        }

        $this->form_validation->set_rules('ip_address', 'IP Address', 'required|valid_ip');
        $this->form_validation->set_rules('name', 'Name', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('ping/edit_ip', $data);
        } else {
            $ip_address = $this->input->post('ip_address');
            $name = $this->input->post('name');
            $this->IpModel->update_ip($id, $ip_address, $name);
            redirect('ping/index');
        }
    }

    public function delete_ip($id)
    {
        $this->IpModel->delete_ip($id);
        redirect('ping/index');
    }

    public function get_ping_status()
    {
        try {
            $ips = $this->IpModel->get_all_ips();
            $result = [];
            foreach ($ips as $ip) {
                $status = $this->ping($ip->ip_address) ? 'Up' : 'Down';
                $now = date('Y-m-d H:i:s');
                $down_time = $ip->last_down_time;
                $duration = $ip->down_duration;

                if ($status === 'Down') {
                    if (is_null($down_time)) {
                        $this->IpModel->update_ping_status($ip->id, 'Down', $now, $duration);
                    }
                    $this->sendTelegramMessage("Alert: IP Address " . $ip->ip_address . " is Down since " . $now . ".");
                } else {
                    if (!is_null($down_time)) {
                        $down_duration = (strtotime($now) - strtotime($down_time)) / 60; // Durasi dalam menit
                        $this->IpModel->update_ping_status($ip->id, 'Up', null, $duration + $down_duration);
                        $this->sendTelegramMessage("Alert: IP Address " . $ip->ip_address . " is Up. It was down for " . $down_duration . " minutes.");
                    }
                    $this->IpModel->update_ping_status($ip->id, 'Up');
                }

                $result[] = [
                    'id' => $ip->id,
                    'ip_address' => $ip->ip_address,
                    'name' => $ip->name,
                    'status' => $status
                ];
            }

            echo json_encode($result);
        } catch (Exception $e) {
            log_message('error', 'Exception in get_ping_status: ' . $e->getMessage());
            show_error('An error occurred while processing the request.');
        }
    }

    private function ping($ip_address)
    {
        $output = shell_exec("ping -c 1 " . escapeshellarg($ip_address));
        log_message('debug', "Ping output for $ip_address: $output");
        return (strpos($output, '1 received') !== false);
    }

    private function sendTelegramMessage($message)
    {
        $url = "https://api.telegram.org/bot" . $this->telegramToken . "/sendMessage";
        $data = [
            'chat_id' => $this->chatId,
            'text' => $message
        ];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ],
        ];

        $context  = stream_context_create($options);
        file_get_contents($url, false, $context);
    }
}
