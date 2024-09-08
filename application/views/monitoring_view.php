<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring IP</title>
</head>
<body>
    <h1>Status IP Monitoring</h1>
    <table border="1">
        <thead>
            <tr>
                <th>IP Address</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $ip => $status): ?>
            <tr>
                <td><?= $ip ?></td>
                <td><?= $status ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
