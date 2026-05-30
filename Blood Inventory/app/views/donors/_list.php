<div class="card">
    <h2 style="margin:0 0 8px">Donors</h2>
    <form method="get" class="card" style="margin:0 0 12px">
        <div class="row">
            <div style="flex:2;min-width:200px">
                <label>Search (name/contact)</label>
                <input name="q" value="<?= e($query) ?>">
            </div>
            <div style="flex:1;min-width:140px">
                <label>Blood Group</label>
                <select name="blood_group">
                    <option value="">All</option>
                    <?php
                    $groups = ['A+','A-','B+','B-','AB+','AB-','O+','O-'];
                    foreach ($groups as $grp) {
                        echo '<option value="' . e($grp) . '"' . ($bloodGroup === $grp ? ' selected' : '') . '>' . e($grp) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div style="align-self:end">
                <button class="btn" type="submit">Filter</button>
                <a class="btn" href="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/donors">Reset</a>
            </div>
        </div>
    </form>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Group</th>
            <th>Contact</th>
            <th>Last Donation</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($donors as $d): ?>
            <tr>
                <td><?= e((string) $d['id']) ?></td>
                <td><?= e($d['name']) ?></td>
                <td><?= e((string) $d['age']) ?></td>
                <td><?= e($d['gender']) ?></td>
                <td><?= e($d['blood_group']) ?></td>
                <td><?= e($d['contact']) ?></td>
                <td><?= e($d['last_donation_date']) ?></td>
                <td>
                    <a class="btn" href="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/donors?edit=<?= e((string) $d['id']) ?>">Edit</a>
                    <form method="post" action="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/donors/<?= e((string) $d['id']) ?>/delete" style="display:inline">
                        <?= Csrf::input() ?>
                        <button class="btn danger" type="submit" onclick="return confirm('Delete donor?')">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$donors): ?>
            <tr><td colspan="8" class="small">No donors found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    <?= paginate_links((int) $pagination['page'], (int) $pagination['total_pages'], '/donors', ['q' => $query, 'blood_group' => $bloodGroup, 'per_page' => $pagination['per_page']]) ?>
</div>
