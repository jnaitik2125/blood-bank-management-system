<div class="card">
    <h2 style="margin:0 0 8px">Requests</h2>
    <form method="get" class="card" style="margin:0 0 12px">
        <div class="row">
            <div style="flex:1;min-width:160px">
                <label>Status</label>
                <select name="status">
                    <option value="">All</option>
                    <?php foreach (['pending','approved','rejected'] as $s): ?>
                        <option value="<?= e($s) ?>" <?= $status === $s ? 'selected' : '' ?>><?= e(ucfirst($s)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="flex:1;min-width:160px">
                <label>Blood Group</label>
                <select name="blood_group">
                    <option value="">All</option>
                    <?php
                    $groups = ['A+','A-','B+','B-','AB+','AB-','O+','O-'];
                    foreach ($groups as $grp) echo '<option value="' . e($grp) . '"' . ($bloodGroup === $grp ? ' selected' : '') . '>' . e($grp) . '</option>';
                    ?>
                </select>
            </div>
            <div style="align-self:end">
                <button class="btn" type="submit">Filter</button>
                <a class="btn" href="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/requests">Reset</a>
            </div>
        </div>
    </form>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Patient</th>
            <th>Group</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Date</th>
            <th>Requested By</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($requests as $r): ?>
            <tr>
                <td><?= e((string) $r['id']) ?></td>
                <td><?= e($r['patient_name']) ?></td>
                <td><?= e($r['blood_group']) ?></td>
                <td><?= e((string) $r['quantity']) ?></td>
                <td><?= e($r['status']) ?></td>
                <td><?= e($r['date']) ?></td>
                <td><?= e($r['requested_by_name'] ?? '-') ?></td>
                <td>
                    <?php if (($currentUser['role'] ?? '') === 'admin'): ?>
                        <a class="btn" href="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/requests?edit=<?= e((string) $r['id']) ?>">Edit</a>
                        <form method="post" action="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/requests/<?= e((string) $r['id']) ?>/delete" style="display:inline">
                            <?= Csrf::input() ?>
                            <button class="btn danger" type="submit" onclick="return confirm('Delete request?')">Delete</button>
                        </form>
                    <?php else: ?>
                        <span class="small">View only</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$requests): ?>
            <tr><td colspan="8" class="small">No requests found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    <?= paginate_links((int) $pagination['page'], (int) $pagination['total_pages'], '/requests', ['status' => $status, 'blood_group' => $bloodGroup, 'per_page' => $pagination['per_page']]) ?>
</div>
