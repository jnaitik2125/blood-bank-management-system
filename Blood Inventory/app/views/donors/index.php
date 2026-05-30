<div class="split">
    <div class="card">
        <h2 style="margin:0 0 8px"><?= $edit ? 'Edit Donor' : 'Register Donor' ?></h2>
        <form method="post" data-ajax-form="donors" action="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/donors/<?= $edit ? e((string) $edit['id']) . '/update' : 'store' ?>">
            <?= Csrf::input() ?>
            <label>Name</label>
            <input name="name" value="<?= e($edit['name'] ?? '') ?>" required>

            <div class="row">
                <div style="flex:1;min-width:140px">
                    <label>Age</label>
                    <input type="number" name="age" min="18" value="<?= e((string) ($edit['age'] ?? '18')) ?>" required>
                </div>
                <div style="flex:1;min-width:140px">
                    <label>Gender</label>
                    <?php $g = $edit['gender'] ?? 'male'; ?>
                    <select name="gender" required>
                        <option value="male" <?= $g === 'male' ? 'selected' : '' ?>>Male</option>
                        <option value="female" <?= $g === 'female' ? 'selected' : '' ?>>Female</option>
                        <option value="other" <?= $g === 'other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                <div style="flex:1;min-width:140px">
                    <label>Blood Group</label>
                    <?php $bg = $edit['blood_group'] ?? ''; ?>
                    <select name="blood_group" required>
                        <?php
                        $groups = ['A+','A-','B+','B-','AB+','AB-','O+','O-'];
                        foreach ($groups as $grp) {
                            echo '<option value="' . e($grp) . '"' . ($bg === $grp ? ' selected' : '') . '>' . e($grp) . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <label>Contact</label>
            <input name="contact" pattern="^\+?[0-9]{10,15}$" value="<?= e($edit['contact'] ?? '') ?>" required>

            <label>Address</label>
            <textarea name="address"><?= e($edit['address'] ?? '') ?></textarea>

            <label>Last Donation Date</label>
            <input type="date" name="last_donation_date" value="<?= e($edit['last_donation_date'] ?? '') ?>">

            <div style="margin-top:10px">
                <button class="btn ok" type="submit"><?= $edit ? 'Update' : 'Create' ?></button>
                <?php if ($edit): ?>
                    <a class="btn" href="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/donors">Cancel</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div id="donors-list-container">
        <?php require __DIR__ . '/_list.php'; ?>
    </div>
</div>

