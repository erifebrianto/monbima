<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring IP Real-Time</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        table { width: 50%; margin: 20px auto; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 10px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Status IP Monitoring (Real-Time)</h1>
    
    <table>
        <thead>
            <tr>
                <th>IP Address</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="ip-status">
            <!-- Data hasil ping akan di-update di sini -->
        </tbody>
    </table>

    <script type="text/javascript">
        // Fungsi untuk memperbarui tabel hasil ping setiap beberapa detik
        function updatePingStatus() {
            $.ajax({
                url: "<?php echo base_url('monitoring/get_ping_status'); ?>",
                method: "GET",
                dataType: "json",
                success: function(data) {
                    var tableContent = '';
                    $.each(data, function(key, value) {
                        tableContent += '<tr>';
                        tableContent += '<td>' + value.ip_address + '</td>';
                        tableContent += '<td>' + value.status + '</td>';
                        tableContent += '</tr>';
                    });
                    $('#ip-status').html(tableContent);
                }
            });
        }

        // Panggil fungsi updatePingStatus setiap 5 detik (5000 ms)
        setInterval(updatePingStatus, 5000);

        // Jalankan update pertama kali saat halaman dimuat
        updatePingStatus();
    </script>
</body>
</html>
