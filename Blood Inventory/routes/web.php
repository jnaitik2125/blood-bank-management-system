<?php
declare(strict_types=1);

return [
    ['method' => 'GET', 'path' => '/', 'handler' => [AuthController::class, 'showLogin'], 'roles' => []],
    ['method' => 'GET', 'path' => '/login', 'handler' => [AuthController::class, 'showLogin'], 'roles' => []],
    ['method' => 'POST', 'path' => '/login', 'handler' => [AuthController::class, 'login'], 'roles' => []],
    ['method' => 'GET', 'path' => '/logout', 'handler' => [AuthController::class, 'logout'], 'roles' => ['admin', 'receptionist', 'collection_staff']],

    ['method' => 'GET', 'path' => '/dashboard', 'handler' => [DashboardController::class, 'index'], 'roles' => ['admin', 'receptionist', 'collection_staff']],

    // Admin only
    ['method' => 'GET', 'path' => '/users', 'handler' => [UserController::class, 'index'], 'roles' => ['admin']],
    ['method' => 'POST', 'path' => '/users/store', 'handler' => [UserController::class, 'store'], 'roles' => ['admin']],
    ['method' => 'POST', 'path' => '/users/{id}/update', 'handler' => [UserController::class, 'update'], 'roles' => ['admin']],
    ['method' => 'POST', 'path' => '/users/{id}/delete', 'handler' => [UserController::class, 'delete'], 'roles' => ['admin']],

    // Admin + Receptionist
    ['method' => 'GET', 'path' => '/donors', 'handler' => [DonorController::class, 'index'], 'roles' => ['admin', 'receptionist']],
    ['method' => 'POST', 'path' => '/donors/store', 'handler' => [DonorController::class, 'store'], 'roles' => ['admin', 'receptionist']],
    ['method' => 'POST', 'path' => '/donors/{id}/update', 'handler' => [DonorController::class, 'update'], 'roles' => ['admin', 'receptionist']],
    ['method' => 'POST', 'path' => '/donors/{id}/delete', 'handler' => [DonorController::class, 'delete'], 'roles' => ['admin', 'receptionist']],

    ['method' => 'GET', 'path' => '/requests', 'handler' => [RequestController::class, 'index'], 'roles' => ['admin', 'receptionist']],
    ['method' => 'POST', 'path' => '/requests/store', 'handler' => [RequestController::class, 'store'], 'roles' => ['admin', 'receptionist']],
    ['method' => 'POST', 'path' => '/requests/{id}/update', 'handler' => [RequestController::class, 'update'], 'roles' => ['admin']], // approval by admin
    ['method' => 'POST', 'path' => '/requests/{id}/delete', 'handler' => [RequestController::class, 'delete'], 'roles' => ['admin']],

    // Admin + Collection staff
    ['method' => 'GET', 'path' => '/donations', 'handler' => [DonationController::class, 'index'], 'roles' => ['admin', 'collection_staff']],
    ['method' => 'POST', 'path' => '/donations/store', 'handler' => [DonationController::class, 'store'], 'roles' => ['admin', 'collection_staff']],
    ['method' => 'POST', 'path' => '/donations/{id}/delete', 'handler' => [DonationController::class, 'delete'], 'roles' => ['admin']],

    ['method' => 'GET', 'path' => '/inventory', 'handler' => [InventoryController::class, 'index'], 'roles' => ['admin', 'collection_staff']],
    ['method' => 'POST', 'path' => '/inventory/store', 'handler' => [InventoryController::class, 'store'], 'roles' => ['admin', 'collection_staff']],
    ['method' => 'POST', 'path' => '/inventory/{id}/update', 'handler' => [InventoryController::class, 'update'], 'roles' => ['admin', 'collection_staff']],
    ['method' => 'POST', 'path' => '/inventory/{id}/delete', 'handler' => [InventoryController::class, 'delete'], 'roles' => ['admin']],

    // Reports - admin only
    ['method' => 'GET', 'path' => '/reports', 'handler' => [ReportController::class, 'index'], 'roles' => ['admin']],
];

