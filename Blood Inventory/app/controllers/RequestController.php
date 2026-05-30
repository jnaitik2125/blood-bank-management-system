<?php
declare(strict_types=1);

class RequestController extends Controller
{
    private BloodRequest $requests;
    private BloodUnit $units;
    private InventoryLog $logs;

    public function __construct()
    {
        $this->requests = new BloodRequest();
        $this->units = new BloodUnit();
        $this->logs = new InventoryLog();
    }

    public function index(): void
    {
        $status = trim((string) ($_GET['status'] ?? ''));
        $bloodGroup = trim((string) ($_GET['blood_group'] ?? ''));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = max(1, (int) ($_GET['per_page'] ?? 10));
        $pagination = $this->requests->allWithUsersPaginated($status, $bloodGroup, $page, $perPage);
        $requests = $pagination['data'];
        $edit = null;
        $id = (int) ($_GET['edit'] ?? 0);
        if ($id > 0) {
            $edit = $this->requests->find($id);
        }
        if ($this->isAjax()) {
            $currentUser = Auth::user();
            $html = $this->renderPartial('requests/_list', compact('requests', 'pagination', 'status', 'bloodGroup', 'currentUser'));
            $this->json(['ok' => true, 'html' => $html]);
        }
        $this->view('requests/index', compact('requests', 'edit', 'status', 'bloodGroup', 'pagination'));
    }

    public function store(): void
    {
        $this->requireCsrf();
        $patientName = trim((string) $this->request('patient_name'));
        $bloodGroup = trim((string) $this->request('blood_group'));
        $quantity = (float) $this->request('quantity');
        if ($patientName === '' || mb_strlen($patientName) > 120 || !is_valid_blood_group($bloodGroup) || $quantity <= 0 || $quantity > 10) {
            if ($this->isAjax()) {
                $this->json(['ok' => false, 'message' => 'Invalid request data.'], 422);
            }
            flash('error', 'Invalid request data.');
            redirect('/requests');
        }
        $this->requests->create([
            'patient_name' => $patientName,
            'blood_group' => $bloodGroup,
            'quantity' => $quantity,
            'status' => 'pending',
            'requested_by' => Auth::user()['id'],
            'date' => date('Y-m-d'),
        ]);
        if ($this->isAjax()) {
            $this->json(['ok' => true, 'message' => 'Blood request submitted.']);
        }
        flash('success', 'Blood request submitted.');
        redirect('/requests');
    }

    public function update(int $id): void
    {
        $this->requireCsrf();
        $request = $this->requests->find($id);
        if (!$request) {
            flash('error', 'Request not found.');
            redirect('/requests');
        }
        $status = (string) $this->request('status');
        if (!in_array($status, ['pending', 'approved', 'rejected'], true)) {
            if ($this->isAjax()) {
                $this->json(['ok' => false, 'message' => 'Invalid request status.'], 422);
            }
            flash('error', 'Invalid request status.');
            redirect('/requests');
        }
        $data = [
            'patient_name' => trim((string) $this->request('patient_name')),
            'blood_group' => trim((string) $this->request('blood_group')),
            'quantity' => (float) $this->request('quantity'),
            'status' => $status,
            'requested_by' => (int) $request['requested_by'],
            'date' => (string) $request['date'],
        ];
        if ($data['patient_name'] === '' || mb_strlen($data['patient_name']) > 120 || !is_valid_blood_group($data['blood_group']) || $data['quantity'] <= 0 || $data['quantity'] > 10) {
            if ($this->isAjax()) {
                $this->json(['ok' => false, 'message' => 'Invalid request data.'], 422);
            }
            flash('error', 'Invalid request data.');
            redirect('/requests');
        }

        if ($request['status'] !== 'approved' && $status === 'approved') {
            $ok = $this->units->consume($data['blood_group'], (float) $data['quantity']);
            if (!$ok) {
                if ($this->isAjax()) {
                    $this->json(['ok' => false, 'message' => 'Insufficient stock for this request.'], 422);
                }
                flash('error', 'Insufficient stock for this request.');
                redirect('/requests');
            }
            $this->logs->create([
                'action' => 'request_approved_stock_deducted',
                'blood_group' => $data['blood_group'],
                'quantity' => $data['quantity'],
                'performed_by' => Auth::user()['id'],
                'timestamp' => date('Y-m-d H:i:s'),
            ]);
        }
        $this->requests->update($id, $data);
        if ($this->isAjax()) {
            $this->json(['ok' => true, 'message' => 'Request updated.']);
        }
        flash('success', 'Request updated.');
        redirect('/requests');
    }

    public function delete(int $id): void
    {
        $this->requireCsrf();
        $this->requests->delete($id);
        flash('success', 'Request deleted.');
        redirect('/requests');
    }
}
