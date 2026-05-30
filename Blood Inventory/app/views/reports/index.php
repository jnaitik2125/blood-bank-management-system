<div class="card">
    <h2 style="margin:0 0 8px">Reports</h2>
    <div class="small">Basic tabular reports for donations, requests, and inventory logs.</div>
</div>

<div class="card">
    <h3 style="margin:0 0 8px">Donations Report</h3>
    <table>
        <thead><tr><th>Date</th><th>Blood Group</th><th>Total Quantity</th></tr></thead>
        <tbody>
        <?php foreach ($donations as $r): ?>
            <tr><td><?= e($r['date']) ?></td><td><?= e($r['blood_group']) ?></td><td><?= e((string) $r['total_quantity']) ?></td></tr>
        <?php endforeach; ?>
        <?php if (!$donations): ?><tr><td colspan="3" class="small">No data.</td></tr><?php endif; ?>
        </tbody>
    </table>
</div>

<div class="card">
    <h3 style="margin:0 0 8px">Requests Report</h3>
    <table>
        <thead><tr><th>Date</th><th>Blood Group</th><th>Status</th><th>Total Quantity</th></tr></thead>
        <tbody>
        <?php foreach ($requests as $r): ?>
            <tr><td><?= e($r['date']) ?></td><td><?= e($r['blood_group']) ?></td><td><?= e($r['status']) ?></td><td><?= e((string) $r['total_quantity']) ?></td></tr>
        <?php endforeach; ?>
        <?php if (!$requests): ?><tr><td colspan="4" class="small">No data.</td></tr><?php endif; ?>
        </tbody>
    </table>
</div>

<div class="card">
    <h3 style="margin:0 0 8px">Inventory Logs</h3>
    <table>
        <thead><tr><th>Timestamp</th><th>Action</th><th>Blood Group</th><th>Quantity</th><th>By</th></tr></thead>
        <tbody>
        <?php foreach ($logs as $l): ?>
            <tr>
                <td><?= e($l['timestamp']) ?></td>
                <td><?= e($l['action']) ?></td>
                <td><?= e($l['blood_group']) ?></td>
                <td><?= e((string) $l['quantity']) ?></td>
                <td><?= e($l['performed_by_name'] ?? '-') ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$logs): ?><tr><td colspan="5" class="small">No logs.</td></tr><?php endif; ?>
        </tbody>
    </table>
</div>

