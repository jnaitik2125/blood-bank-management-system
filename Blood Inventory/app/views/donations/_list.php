<div class="card">
    <h2 style="margin:0 0 8px">Donations</h2>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Donor</th>
            <th>Blood Group</th>
            <th>Quantity</th>
            <th>Staff</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($records as $r): ?>
            <tr>
                <td><?= e((string) $r['id']) ?></td>
                <td><?= e($r['date']) ?></td>
                <td><?= e($r['donor_name']) ?></td>
                <td><?= e($r['blood_group']) ?></td>
                <td><?= e((string) $r['quantity']) ?></td>
                <td><?= e($r['staff_name']) ?></td>
                <td>
                    <?php if (($currentUser['role'] ?? '') === 'admin'): ?>
                        <form method="post" data-ajax-form="donations" action="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/donations/<?= e((string) $r['id']) ?>/delete" style="display:inline">
                            <?= Csrf::input() ?>
                            <button class="btn danger" type="submit" onclick="return confirm('Delete donation record?')">Delete</button>
                        </form>
                    <?php else: ?>
                        <span class="small">—</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$records): ?>
            <tr><td colspan="7" class="small">No donation records found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    <?= paginate_links((int) $pagination['page'], (int) $pagination['total_pages'], '/donations', ['per_page' => $pagination['per_page']]) ?>
</div>
