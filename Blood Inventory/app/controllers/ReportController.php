<?php
declare(strict_types=1);

class ReportController extends Controller
{
    public function index(): void
    {
        $db = Database::connection();
        $donations = $db->query('
            SELECT d.date, d.blood_group, SUM(d.quantity) AS total_quantity
            FROM donations d
            GROUP BY d.date, d.blood_group
            ORDER BY d.date DESC, d.blood_group
            LIMIT 100
        ')->fetchAll();

        $requests = $db->query('
            SELECT r.date, r.blood_group, r.status, SUM(r.quantity) AS total_quantity
            FROM requests r
            GROUP BY r.date, r.blood_group, r.status
            ORDER BY r.date DESC, r.blood_group
            LIMIT 200
        ')->fetchAll();

        $logs = $db->query('
            SELECT l.*, u.name AS performed_by_name
            FROM inventory_logs l
            LEFT JOIN users u ON u.id = l.performed_by
            ORDER BY l.id DESC
            LIMIT 200
        ')->fetchAll();

        $this->view('reports/index', compact('donations', 'requests', 'logs'));
    }
}
