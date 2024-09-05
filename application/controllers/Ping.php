<?php
class ping extends CI_Controller
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

    public function ping_ips_realtime()
    {
        $ips = $this->IpModel->get_all_ips();
        $result = [];
        foreach ($ips as $ip) {
            $status = $this->ping($ip->ip_address) ? 'Up' : 'Down';
            $result[] = [
                'ip_address' => $ip->ip_address,
                'status' => $status
            ];

            // Kirim pesan ke Telegram jika status "Down"
            if ($status === 'Down') {
                $this->sendTelegramMessage("Alert: IP Address " . $ip->ip_address . " is Down.");
            }
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
