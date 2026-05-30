<div class="card">
    <h2 style="margin:0 0 8px">Dashboard</h2>
    <div class="small">Signed in as <?= e($currentUser['name'] ?? '') ?> (<?= e($currentUser['role'] ?? '') ?>)</div>
</div>

<div class="grid">
    <div class="stat"><div class="k">Total Donors</div><div class="v"><?= e((string) $stats['donors']) ?></div></div>
    <div class="stat"><div class="k">Total Users</div><div class="v"><?= e((string) $stats['users']) ?></div></div>
    <div class="stat"><div class="k">Total Requests</div><div class="v"><?= e((string) $stats['requests']) ?></div></div>
    <div class="stat"><div class="k">Available Units (sum)</div><div class="v"><?= e((string) $stats['available_units']) ?></div></div>
</div>

<div class="card">
    <h3 style="margin:0 0 10px">Stock By Blood Group</h3>
    <table>
        <thead>
        <tr>
            <th>Blood Group</th>
            <th>Total Quantity</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($stock as $row): ?>
            <tr>
                <td><?= e($row['blood_group']) ?></td>
                <td><?= e((string) $row['total_quantity']) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$stock): ?>
            <tr><td colspan="2" class="small">No stock yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

