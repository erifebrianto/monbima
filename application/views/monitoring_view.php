<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring IP Real-Time</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        table { width: 70%; margin: 20px auto; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 10px; text-align: center; }
        th { background-color: #f2f2f2; }
        .center { text-align: center; }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 30%;
            text-align: center;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover, .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1 class="center">Status IP Monitoring (Real-Time)</h1>

    <div class="center">
        <button id="addIpBtn">Add IP Address</button>
        <button id="importCsvBtn">Import CSV</button>
    </div>

    <!-- Modal untuk menambah IP Address -->
    <div id="addIpModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add IP Address</h2>
            <form id="addIpForm" method="post" action="<?php echo base_url('monitoring/add_ip'); ?>">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required>
                <br><br>
                <label for="ip_address">IP Address:</label>
                <input type="text" name="ip_address" id="ip_address" required>
                <br><br>
                <button type="submit">Add</button>
            </form>
        </div>
    </div>

    <!-- Form untuk import CSV -->
    <div id="importCsvModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Import IP Address from CSV</h2>
            <form id="importCsvForm" method="post" enctype="multipart/form-data" action="<?php echo base_url('monitoring/import_csv'); ?>">
                <label for="csv_file">Choose CSV File:</label>
                <input type="file" name="csv_file" id="csv_file" required accept=".csv">
                <br><br>
                <button type="submit">Import</button>
            </form>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
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
                        tableContent += '<td>' + value.name + '</td>';
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

        // Modal script
        var modalAddIp = document.getElementById("addIpModal");
        var modalImportCsv = document.getElementById("importCsvModal");
        var btnAddIp = document.getElementById("addIpBtn");
        var btnImportCsv = document.getElementById("importCsvBtn");
        var span = document.getElementsByClassName("close");

        // Ketika tombol Add IP Address diklik, tampilkan modal
        btnAddIp.onclick = function() {
            modalAddIp.style.display = "block";
        }

        // Ketika tombol Import CSV diklik, tampilkan modal
        btnImportCsv.onclick = function() {
            modalImportCsv.style.display = "block";
        }

        // Ketika tombol 'x' di modal diklik, tutup modal
        for (let i = 0; i < span.length; i++) {
            span[i].onclick = function() {
                modalAddIp.style.display = "none";
                modalImportCsv.style.display = "none";
            }
        }

        // Jika user mengklik di luar modal, modal akan ditutup
        window.onclick = function(event) {
            if (event.target == modalAddIp || event.target == modalImportCsv) {
                modalAddIp.style.display = "none";
                modalImportCsv.style.display = "none";
            }
        }
    </script>
</body>
</html>
