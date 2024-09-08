<table>
    <thead>
        <tr>
            <th>IP Address</th>
            <th>Status</th>
            <th>Last Ping Time</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($results)): ?>
            <?php foreach ($results as $result): ?>
                <tr>
                    <td><?= $result['ip'] ?></td>
                    <td class="<?= $result['status'] === 'Up' ? 'status-up' : 'status-down' ?>">
                        <?= $result['status'] ?>
                    </td>
                    <td><?= date('Y-m-d H:i:s', strtotime($result['created_at'])) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">No IP addresses found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
