<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IP Address Monitoring</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>IP Address Monitoring</h1>
        <a href="<?php echo site_url('ping/add_ip'); ?>" class="btn btn-primary mb-3">Add IP Address</a>
        <table class="table">
            <thead>
                <tr>
                    <th>IP Address</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="ip-table-body">
                <!-- Data akan dimuat menggunakan AJAX -->
            </tbody>
        </table>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        function loadIps() {
            $.ajax({
                url: "<?php echo site_url('ping/get_ping_status'); ?>",
                method: "GET",
                dataType: "json",
                success: function(data) {
                    var tableBody = $('#ip-table-body');
                    tableBody.empty();
                    $.each(data, function(index, ip) {
                        var row = '<tr>' +
                            '<td>' + ip.ip_address + '</td>' +
                            '<td>' + ip.name + '</td>' +
                            '<td>' + ip.status + '</td>' +
                            '<td>' +
                                '<a href="' + '<?php echo site_url('ping/edit_ip/'); ?>' + ip.id + '" class="btn btn-warning btn-sm">Edit</a> ' +
                                '<a href="' + '<?php echo site_url('ping/delete_ip/'); ?>' + ip.id + '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this IP address?\');">Delete</a>' +
                            '</td>' +
                        '</tr>';
                        tableBody.append(row);
                    });
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: " + status + error);
                }
            });
        }

        // Load IPs on page load
        loadIps();

        // Set interval to reload IPs every 60 seconds
        setInterval(loadIps, 5000);
    });
    </script>
</body>
</html>
