<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IP Address Monitoring</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .up {
            color: green;
        }

        .down {
            color: red;
        }
    </style>
</head>
<body>
    <h1>IP Address Monitoring</h1>
    <table>
        <thead>
            <tr>
                <th>IP Address</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="ip-status-table">
            <?php foreach ($ips as $ip) : ?>
                <tr>
                    <td><?= $ip->ip_address; ?></td>
                    <td class="status">Checking...</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        function fetchPingStatus() {
            $.ajax({
                url: "<?= site_url('ping/ping_ips_realtime') ?>",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $("#ip-status-table tr").each(function(index) {
                        let statusCell = $(this).find(".status");
                        if (data[index].status === "Up") {
                            statusCell.text("Up").removeClass("down").addClass("up");
                        } else {
                            statusCell.text("Down").removeClass("up").addClass("down");
                        }
                    });
                }
            });
        }

        // Fetch status ping setiap 5 detik
        setInterval(fetchPingStatus, 5000);

        // Fetch status ping pertama kali saat halaman dimuat
        $(document).ready(function() {
            fetchPingStatus();
        });
    </script>
</body>
</html>
