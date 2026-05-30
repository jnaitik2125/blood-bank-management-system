<?php
declare(strict_types=1);

class InventoryController extends Controller
{
    private BloodUnit $units;
    private InventoryLog $logs;

    public function __construct()
    {
        $this->units = new BloodUnit();
        $this->logs = new InventoryLog();
    }

    public function index(): void
    {
        $this->units->markExpired();
        $bloodGroup = trim((string) ($_GET['blood_group'] ?? ''));
        $status = trim((string) ($_GET['status'] ?? ''));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = max(1, (int) ($_GET['per_page'] ?? 10));
        $pagination = $this->units->searchablePaginated($bloodGroup, $status, $page, $perPage);
        $units = $pagination['data'];
        $stock = $this->units->stockByGroup();
        $edit = null;
        $id = (int) ($_GET['edit'] ?? 0);
        if ($id > 0) {
            $edit = $this->units->find($id);
        }
        if ($this->isAjax()) {
            $html = $this->renderPartial('inventory/_list', [
                'units' => $units,
                'stock' => $stock,
                'edit' => $edit,
                'bloodGroup' => $bloodGroup,
                'status' => $status,
                'pagination' => $pagination,
                'currentUser' => Auth::user(),
            ]);
            $this->json(['ok' => true, 'html' => $html]);
        }

        $this->view('inventory/index', compact('units', 'stock', 'edit', 'bloodGroup', 'status', 'pagination'));
    }

    public function store(): void
    {
        $this->requireCsrf();
        $bloodGroup = (string) $this->request('blood_group');
        $quantity = (float) $this->request('quantity');
        $collectionDate = trim((string) $this->request('collection_date'));
        $expiryDate = trim((string) $this->request('expiry_date'));
        $status = (string) $this->request('status', 'available');
        if (
            !is_valid_blood_group($bloodGroup) || $quantity <= 0 || $quantity > 10
            || !is_valid_date($collectionDate) || !is_valid_date($expiryDate)
            || strtotime($expiryDate) <= strtotime($collectionDate)
            || !in_array($status, ['available', 'used', 'expired'], true)
        ) {
            if ($this->isAjax()) {
                $this->json(['ok' => false, 'message' => 'Invalid inventory details.'], 422);
            }
            flash('error', 'Invalid inventory details.');
            redirect('/inventory');
        }
        $this->units->create([
            'blood_group' => $bloodGroup,
            'quantity' => $quantity,
            'collection_date' => $collectionDate,
            'expiry_date' => $expiryDate,
            'status' => $status,
        ]);
        $this->logs->create([
            'action' => 'manual_inventory_add',
            'blood_group' => $bloodGroup,
            'quantity' => $quantity,
            'performed_by' => Auth::user()['id'],
            'timestamp' => date('Y-m-d H:i:s'),
        ]);
        flash('success', 'Inventory unit added.');
        if ($this->isAjax()) {
            $this->json(['ok' => true, 'message' => 'Inventory unit added.']);
        }
        redirect('/inventory');
    }

    public function update(int $id): void
    {
        $this->requireCsrf();
        $bloodGroup = (string) $this->request('blood_group');
        $quantity = (float) $this->request('quantity');
        $collectionDate = trim((string) $this->request('collection_date'));
        $expiryDate = trim((string) $this->request('expiry_date'));
        $status = (string) $this->request('status');
        if (
            !is_valid_blood_group($bloodGroup) || $quantity <= 0 || $quantity > 10
            || !is_valid_date($collectionDate) || !is_valid_date($expiryDate)
            || strtotime($expiryDate) <= strtotime($collectionDate)
            || !in_array($status, ['available', 'used', 'expired'], true)
        ) {
            if ($this->isAjax()) {
                $this->json(['ok' => false, 'message' => 'Invalid inventory data.'], 422);
            }
            flash('error', 'Invalid inventory data.');
            redirect('/inventory');
        }
        $this->units->update($id, [
            'blood_group' => $bloodGroup,
            'quantity' => $quantity,
            'collection_date' => $collectionDate,
            'expiry_date' => $expiryDate,
            'status' => $status,
        ]);
        flash('success', 'Inventory unit updated.');
        if ($this->isAjax()) {
            $this->json(['ok' => true, 'message' => 'Inventory unit updated.']);
        }
        redirect('/inventory');
    }

    public function delete(int $id): void
    {
        $this->requireCsrf();
        $unit = $this->units->find($id);
        if ($unit) {
            $this->logs->create([
                'action' => 'inventory_deleted',
                'blood_group' => $unit['blood_group'],
                'quantity' => (float) $unit['quantity'],
                'performed_by' => Auth::user()['id'],
                'timestamp' => date('Y-m-d H:i:s'),
            ]);
        }
        $this->units->delete($id);
        flash('success', 'Inventory unit deleted.');
        if ($this->isAjax()) {
            $this->json(['ok' => true, 'message' => 'Inventory unit deleted.']);
        }
        redirect('/inventory');
    }
}
