<div class="split">
    <div class="card">
        <h2 style="margin:0 0 8px"><?= $edit ? 'Edit Blood Unit' : 'Add Blood Unit' ?></h2>
        <form method="post" data-ajax-form="inventory" action="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/inventory/<?= $edit ? e((string) $edit['id']) . '/update' : 'store' ?>">
            <?= Csrf::input() ?>
            <div class="row">
                <div style="flex:1;min-width:160px">
                    <label>Blood Group</label>
                    <?php $bg = $edit['blood_group'] ?? 'A+'; ?>
                    <select name="blood_group" required>
                        <?php
                        $groups = ['A+','A-','B+','B-','AB+','AB-','O+','O-'];
                        foreach ($groups as $grp) {
                            echo '<option value="' . e($grp) . '"' . ($bg === $grp ? ' selected' : '') . '>' . e($grp) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div style="flex:1;min-width:160px">
                    <label>Quantity</label>
                    <input type="number" step="0.5" min="0.5" max="10" name="quantity" value="<?= e((string) ($edit['quantity'] ?? '1')) ?>" required>
                </div>
            </div>
            <div class="row">
                <div style="flex:1;min-width:200px">
                    <label>Collection Date</label>
                    <input type="date" name="collection_date" value="<?= e($edit['collection_date'] ?? date('Y-m-d')) ?>" required>
                </div>
                <div style="flex:1;min-width:200px">
                    <label>Expiry Date</label>
                    <input type="date" name="expiry_date" value="<?= e($edit['expiry_date'] ?? date('Y-m-d', strtotime('+35 days'))) ?>" required>
                </div>
                <div style="flex:1;min-width:200px">
                    <label>Status</label>
                    <?php $st = $edit['status'] ?? 'available'; ?>
                    <select name="status" required>
                        <?php foreach (['available','used','expired'] as $s): ?>
                            <option value="<?= e($s) ?>" <?= $st === $s ? 'selected' : '' ?>><?= e($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div style="margin-top:10px">
                <button class="btn ok" type="submit"><?= $edit ? 'Update' : 'Add' ?></button>
                <?php if ($edit): ?>
                    <a class="btn" href="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/inventory">Cancel</a>
                <?php endif; ?>
            </div>
        </form>

        <div class="card" style="margin-top:12px">
            <h3 style="margin:0 0 10px">Stock By Group</h3>
            <table>
                <thead><tr><th>Blood Group</th><th>Total Quantity</th></tr></thead>
                <tbody>
                <?php foreach ($stock as $row): ?>
                    <tr><td><?= e($row['blood_group']) ?></td><td><?= e((string) $row['total_quantity']) ?></td></tr>
                <?php endforeach; ?>
                <?php if (!$stock): ?><tr><td colspan="2" class="small">No available stock.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <h2 style="margin:0 0 8px">Inventory</h2>
        <form method="get" class="card" style="margin:0 0 12px">
            <div class="row">
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
                <div style="flex:1;min-width:160px">
                    <label>Status</label>
                    <select name="status">
                        <option value="">All</option>
                        <?php foreach (['available','used','expired'] as $s): ?>
                            <option value="<?= e($s) ?>" <?= $status === $s ? 'selected' : '' ?>><?= e($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="align-self:end">
                    <button class="btn" type="submit">Filter</button>
                    <a class="btn" href="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/inventory">Reset</a>
                </div>
            </div>
        </form>

        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Group</th>
                <th>Quantity</th>
                <th>Collected</th>
                <th>Expiry</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($units as $u): ?>
                <tr>
                    <td><?= e((string) $u['id']) ?></td>
                    <td><?= e($u['blood_group']) ?></td>
                    <td><?= e((string) $u['quantity']) ?></td>
                    <td><?= e($u['collection_date']) ?></td>
                    <td><?= e($u['expiry_date']) ?></td>
                    <td><?= e($u['status']) ?></td>
                    <td>
                        <a class="btn" href="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/inventory?edit=<?= e((string) $u['id']) ?>">Edit</a>
                        <?php if (($currentUser['role'] ?? '') === 'admin'): ?>
                            <form method="post" data-ajax-form="inventory" action="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/inventory/<?= e((string) $u['id']) ?>/delete" style="display:inline">
                                <?= Csrf::input() ?>
                                <button class="btn danger" type="submit" onclick="return confirm('Delete unit?')">Delete</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$units): ?>
                <tr><td colspan="7" class="small">No inventory records found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>

        <?= paginate_links((int) $pagination['page'], (int) $pagination['total_pages'], '/inventory', ['blood_group' => $bloodGroup, 'status' => $status, 'per_page' => $pagination['per_page']]) ?>
    </div>
</div>

