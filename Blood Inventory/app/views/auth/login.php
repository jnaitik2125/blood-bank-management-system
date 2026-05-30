<div class="card">
    <h2 style="margin:0 0 8px">Login</h2>
    <div class="small">Use seed accounts: admin/reception/collector (password: password123)</div>
    <form method="post" action="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/login">
        <?= Csrf::input() ?>
        <div class="row">
            <div style="flex:1;min-width:240px">
                <label>Email</label>
                <input type="email" name="email" value="<?= e(old('email')) ?>" required>
            </div>
            <div style="flex:1;min-width:240px">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
        </div>
        <div style="margin-top:10px">
            <button class="btn ok" type="submit">Login</button>
        </div>
    </form>
</div>

