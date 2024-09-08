<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah IP Address</title>
</head>
<body>
    <h1>Tambah IP Address Baru</h1>

    <?php echo validation_errors(); ?>

    <form action="<?php echo base_url('monitoring/add_ip'); ?>" method="post">
        <label for="ip_address">IP Address:</label>
        <input type="text" name="ip_address" required>
        <button type="submit">Tambah</button>
    </form>
</body>
</html>
