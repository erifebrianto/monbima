<!DOCTYPE html>
<html>
<head>
    <title>Add IP Address</title>
</head>
<body>
    <h1>Add New IP Address</h1>
    <?php echo validation_errors(); ?>
    <?php echo form_open('ping/add_ip'); ?>
        <label for="ip_address">IP Address:</label>
        <input type="text" name="ip_address" required>
        <input type="submit" value="Add IP">
    <?php echo form_close(); ?>
</body>
</html>
