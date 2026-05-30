<?php
declare(strict_types=1);

session_start();

require __DIR__ . '/../core/Helpers.php';
require __DIR__ . '/../core/Database.php';
require __DIR__ . '/../core/Model.php';
require __DIR__ . '/../core/Auth.php';
require __DIR__ . '/../core/Csrf.php';
require __DIR__ . '/../core/Controller.php';
require __DIR__ . '/../core/App.php';

// Models
require __DIR__ . '/../app/models/User.php';
require __DIR__ . '/../app/models/Donor.php';
require __DIR__ . '/../app/models/BloodUnit.php';
require __DIR__ . '/../app/models/Donation.php';
require __DIR__ . '/../app/models/BloodRequest.php';
require __DIR__ . '/../app/models/InventoryLog.php';

// Controllers
require __DIR__ . '/../app/controllers/AuthController.php';
require __DIR__ . '/../app/controllers/DashboardController.php';
require __DIR__ . '/../app/controllers/UserController.php';
require __DIR__ . '/../app/controllers/DonorController.php';
require __DIR__ . '/../app/controllers/DonationController.php';
require __DIR__ . '/../app/controllers/InventoryController.php';
require __DIR__ . '/../app/controllers/RequestController.php';
require __DIR__ . '/../app/controllers/ReportController.php';

set_error_handler(function (int $severity, string $message, string $file, int $line): bool {
    if (!(error_reporting() & $severity)) {
        return false;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
});

try {
    (new App())->run();
} catch (Throwable $e) {
    http_response_code(500);
    if ((bool) ($_ENV['APP_DEBUG'] ?? true)) {
        echo '<pre>' . e($e->getMessage()) . "\n" . e($e->getFile()) . ':' . e((string) $e->getLine()) . "\n\n" . e($e->getTraceAsString()) . '</pre>';
        exit;
    }
    echo 'Server error';
}

