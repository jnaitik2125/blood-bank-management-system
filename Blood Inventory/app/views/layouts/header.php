<?php
declare(strict_types=1);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($appName) ?></title>
    <link rel="stylesheet" href="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/assets/style.css">
</head>
<body>
<div class="topbar">
    <div class="wrap">
        <div class="brand"><?= e($appName) ?></div>
        <div class="nav">
            <?php if ($currentUser): ?>
                <a href="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/dashboard">Dashboard</a>
                <?php if ($currentUser['role'] === 'admin'): ?>
                    <a href="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/users">Users</a>
                    <a href="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/reports">Reports</a>
                <?php endif; ?>
                <?php if (in_array($currentUser['role'], ['admin','receptionist'], true)): ?>
                    <a href="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/donors">Donors</a>
                    <a href="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/requests">Requests</a>
                <?php endif; ?>
                <?php if (in_array($currentUser['role'], ['admin','collection_staff'], true)): ?>
                    <a href="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/donations">Donations</a>
                    <a href="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/inventory">Inventory</a>
                <?php endif; ?>
                <a href="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/logout">Logout</a>
            <?php else: ?>
                <a href="<?= e(rtrim((string) config('base_path', ''), '/')) ?>/login">Login</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="wrap">
    <?php if ($msg = flash('success')): ?>
        <div class="flash ok"><?= e($msg) ?></div>
    <?php endif; ?>
    <?php if ($msg = flash('error')): ?>
        <div class="flash err"><?= e($msg) ?></div>
    <?php endif; ?>

