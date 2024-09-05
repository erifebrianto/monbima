<?php
class Ping extends CI_Controller
{
    private $telegramToken = 'YOUR_BOT_TOKEN'; // Ganti dengan token bot Telegram Anda
    private $chatId = 'YOUR_CHAT_ID'; // Ganti dengan ID chat Telegram Anda

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

    public function ping_ips_realtime()
    {
        $ips = $this->IpModel->get_all_ips();
        $result = [];
        foreach ($ips as $ip) {
            $status = $this->ping($ip->ip_address) ? 'Up' : 'Down';
            if ($status === 'Down') {
                $this->sendTelegramMessage("Alert: IP Address " . $ip->ip_address . " is Down.");
            }
            $result[] = [
                'ip_address' => $ip->ip_address,
                'status' => $status
            ];
            // Update ping status in the database
            $this->IpModel->update_ping_status($ip->id, $status);
        }

        echo json_encode($result);
    }

    private function ping($ip_address)
    {
        $output = shell_exec("ping -c 1 " . escapeshellarg($ip_address));
        return (strpos($output, '1 received') !== false);
    }

    private function sendTelegramMessage($message)
    {
        $url = "https://api.telegram.org/bot" . $this->telegramToken . "/sendMessage";
        $data = [
            'chat_id' => $this->chatId,
            'text' => $message
        ];

        // Kirim permintaan HTTP POST ke API Telegram
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
