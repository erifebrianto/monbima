<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add IP Address</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Add IP Address</h1>
        <?php echo validation_errors(); ?>
        <?php echo form_open('ping/add_ip'); ?>
        <div class="form-group">
            <label for="ip_address">IP Address</label>
            <input type="text" class="form-control" id="ip_address" name="ip_address" placeholder="Enter IP Address">
        </div>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
        </div>
        <button type="submit" class="btn btn-primary">Add IP</button>
        <?php echo form_close(); ?>
    </div>
</body>
</html>
