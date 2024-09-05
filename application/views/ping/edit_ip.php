<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit IP Address</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Edit IP Address</h1>
        <?php echo validation_errors(); ?>
        <?php echo form_open('ping/edit_ip/' . $ip->id); ?>
            <div class="form-group">
                <label for="ip_address">IP Address</label>
                <input type="text" class="form-control" id="ip_address" name="ip_address" value="<?php echo set_value('ip_address', $ip->ip_address); ?>">
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo set_value('name', $ip->name); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update IP Address</button>
        </form>
    </div>
</body>
</html>
