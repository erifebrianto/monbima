<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring_model extends CI_Model {

    public function ping($host) {
        $output = null;
        $result = null;

        // Ping command sesuai OS (Windows/Linux)
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            exec("ping -n 1 $host", $output, $result);
        } else {
            exec("ping -c 1 $host", $output, $result);
        }

        // Return hasil eksekusi ping
        return $result === 0 ? 'Online' : 'Offline';
    }
}
