<div class="split">
    <div class="card">
        <h2 style="margin:0 0 8px"><?= $edit ? 'Edit User' : 'Create User' ?></h2>
        <form method="post" action="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/users/<?= $edit ? e((string) $edit['id']) . '/update' : 'store' ?>">
            <?= Csrf::input() ?>
            <label>Name</label>
            <input name="name" value="<?= e($edit['name'] ?? '') ?>" required>

            <label>Email</label>
            <input type="email" name="email" value="<?= e($edit['email'] ?? '') ?>" required>

            <label>Role</label>
            <select name="role" required>
                <?php
                $roles = ['admin' => 'Admin', 'receptionist' => 'Receptionist', 'collection_staff' => 'Collection Staff'];
                $cur = $edit['role'] ?? 'receptionist';
                foreach ($roles as $k => $label) {
                    echo '<option value="' . e($k) . '"' . ($cur === $k ? ' selected' : '') . '>' . e($label) . '</option>';
                }
                ?>
            </select>

            <label>Status</label>
            <select name="status">
                <option value="1" <?= (($edit['status'] ?? 1) == 1) ? 'selected' : '' ?>>Active</option>
                <option value="0" <?= (($edit['status'] ?? 1) == 0) ? 'selected' : '' ?>>Inactive</option>
            </select>

            <label>Password <?= $edit ? '<span class="small">(leave blank to keep)</span>' : '' ?></label>
            <input type="password" name="password" <?= $edit ? '' : 'required' ?>>

            <div style="margin-top:10px">
                <button class="btn ok" type="submit"><?= $edit ? 'Update' : 'Create' ?></button>
                <?php if ($edit): ?>
                    <a class="btn" href="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/users">Cancel</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="card">
        <h2 style="margin:0 0 8px">Users</h2>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= e((string) $u['id']) ?></td>
                    <td><?= e($u['name']) ?></td>
                    <td><?= e($u['email']) ?></td>
                    <td><?= e($u['role']) ?></td>
                    <td><?= ((int) $u['status'] === 1) ? 'Active' : 'Inactive' ?></td>
                    <td>
                        <a class="btn" href="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/users?edit=<?= e((string) $u['id']) ?>">Edit</a>
                        <?php if (($currentUser['id'] ?? 0) !== $u['id']): ?>
                            <form method="post" action="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/users/<?= e((string) $u['id']) ?>/delete" style="display:inline">
                                <?= Csrf::input() ?>
                                <button class="btn danger" type="submit" onclick="return confirm('Delete this user?')">Delete</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$users): ?>
                <tr><td colspan="6" class="small">No users found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
        <?= paginate_links((int) $pagination['page'], (int) $pagination['total_pages'], '/users', ['per_page' => $pagination['per_page']]) ?>
    </div>
</div>

