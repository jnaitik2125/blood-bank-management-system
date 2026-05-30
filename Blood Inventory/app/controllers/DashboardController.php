<?php
declare(strict_types=1);

class DashboardController extends Controller
{
    public function index(): void
    {
        $db = Database::connection();
        $stats = [
            'donors' => (int) $db->query('SELECT COUNT(*) FROM donors')->fetchColumn(),
            'users' => (int) $db->query('SELECT COUNT(*) FROM users')->fetchColumn(),
            'requests' => (int) $db->query('SELECT COUNT(*) FROM requests')->fetchColumn(),
            'pending_requests' => (int) $db->query("SELECT COUNT(*) FROM requests WHERE status = 'pending'")->fetchColumn(),
            'available_units' => (float) $db->query("SELECT COALESCE(SUM(quantity),0) FROM blood_units WHERE status = 'available'")->fetchColumn(),
        ];
        $stock = (new BloodUnit())->stockByGroup();
        $this->view('dashboard/index', compact('stats', 'stock'));
    }
}
