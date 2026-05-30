<div class="card">
    <h2 style="margin:0 0 8px">Record Donation</h2>
    <form method="post" data-ajax-form="donations" action="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/donations/store">
        <?= Csrf::input() ?>
        <div class="row">
            <div style="flex:2;min-width:240px">
                <label>Donor</label>
                <select name="donor_id" required>
                    <option value="">Select donor</option>
                    <?php foreach ($donors as $d): ?>
                        <option value="<?= e((string) $d['id']) ?>"><?= e($d['name']) ?> (<?= e($d['blood_group']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="flex:1;min-width:160px">
                <label>Blood Group</label>
                <select name="blood_group" required>
                    <?php
                    $groups = ['A+','A-','B+','B-','AB+','AB-','O+','O-'];
                    foreach ($groups as $grp) echo '<option value="' . e($grp) . '">' . e($grp) . '</option>';
                    ?>
                </select>
            </div>
            <div style="flex:1;min-width:160px">
                <label>Quantity (units)</label>
                <input type="number" step="0.5" min="0.5" name="quantity" required>
            </div>
            <div style="flex:2;min-width:220px">
                <label>Collection Staff</label>
                <select name="collection_staff_id" required>
                    <option value="">Select staff</option>
                    <?php foreach ($staff as $s): ?>
                        <option value="<?= e((string) $s['id']) ?>"><?= e($s['name']) ?> (<?= e($s['email']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="flex:1;min-width:160px">
                <label>Date</label>
                <input type="date" name="date" value="<?= e(date('Y-m-d')) ?>" required>
            </div>
        </div>
        <div style="margin-top:10px">
            <button class="btn ok" type="submit">Save Donation</button>
        </div>
    </form>
</div>

<div id="ajax-banner-donations" class="flash err" style="display:none"></div>
<div id="donations-list-container">
    <?php require __DIR__ . '/_list.php'; ?>
</div>

