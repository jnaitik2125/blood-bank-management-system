<div class="split">
    <div class="card">
        <h2 style="margin:0 0 8px"><?= $edit ? 'Update Request' : 'Create Blood Request' ?></h2>
        <form method="post" data-ajax-form="requests" action="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/requests/<?= $edit ? e((string) $edit['id']) . '/update' : 'store' ?>">
            <?= Csrf::input() ?>
            <label>Patient Name</label>
            <input name="patient_name" value="<?= e($edit['patient_name'] ?? '') ?>" required>

            <div class="row">
                <div style="flex:1;min-width:160px">
                    <label>Blood Group</label>
                    <?php $bg = $edit['blood_group'] ?? 'A+'; ?>
                    <select name="blood_group" required>
                        <?php
                        $groups = ['A+','A-','B+','B-','AB+','AB-','O+','O-'];
                        foreach ($groups as $grp) echo '<option value="' . e($grp) . '"' . ($bg === $grp ? ' selected' : '') . '>' . e($grp) . '</option>';
                        ?>
                    </select>
                </div>
                <div style="flex:1;min-width:160px">
                    <label>Quantity</label>
                    <input type="number" min="0.5" step="0.5" name="quantity" value="<?= e((string) ($edit['quantity'] ?? '1')) ?>" required>
                </div>
            </div>
            <?php if (($currentUser['role'] ?? '') === 'admin' && $edit): ?>
                <label>Status</label>
                <select name="status" required>
                    <?php foreach (['pending','approved','rejected'] as $s): ?>
                        <option value="<?= e($s) ?>" <?= (($edit['status'] ?? '') === $s) ? 'selected' : '' ?>><?= e(ucfirst($s)) ?></option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
            <div style="margin-top:10px">
                <button class="btn ok" type="submit"><?= $edit ? 'Update' : 'Create' ?></button>
                <?php if ($edit): ?>
                    <a class="btn" href="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/requests">Cancel</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div id="requests-list-container">
        <?php require __DIR__ . '/_list.php'; ?>
    </div>
</div>

