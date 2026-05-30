<?php
declare(strict_types=1);

class DonationController extends Controller
{
    private Donation $donations;
    private Donor $donors;
    private BloodUnit $units;
    private InventoryLog $logs;

    public function __construct()
    {
        $this->donations = new Donation();
        $this->donors = new Donor();
        $this->units = new BloodUnit();
        $this->logs = new InventoryLog();
    }

    public function index(): void
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = max(1, (int) ($_GET['per_page'] ?? 10));
        $pagination = $this->donations->paginatedWithRelations($page, $perPage);
        $records = $pagination['data'];
        $donors = $this->donors->all();
        $staff = (new User())->all(['role' => 'collection_staff']);

        if ($this->isAjax()) {
            $html = $this->renderPartial('donations/_list', [
                'records' => $records,
                'pagination' => $pagination,
                'currentUser' => Auth::user(),
            ]);
            $this->json(['ok' => true, 'html' => $html]);
        }

        $this->view('donations/index', compact('records', 'donors', 'staff', 'pagination'));
    }

    public function store(): void
    {
        $this->requireCsrf();
        $donorId = (int) $this->request('donor_id');
        $staffId = (int) $this->request('collection_staff_id');
        $bloodGroup = trim((string) $this->request('blood_group'));
        $quantity = (float) $this->request('quantity');
        $date = (string) ($this->request('date') ?: date('Y-m-d'));

        $donor = $this->donors->find($donorId);
        $staffUser = (new User())->find($staffId);

        $staffRoleOk = $staffUser && ($staffUser['role'] ?? '') === 'collection_staff' && (int) ($staffUser['status'] ?? 0) === 1;
        if (
            !$donor || !$staffRoleOk
            || !is_valid_blood_group($bloodGroup)
            || $quantity <= 0 || $quantity > 10
            || !is_valid_date($date)
        ) {
            if ($this->isAjax()) {
                $this->json(['ok' => false, 'message' => 'Invalid donation data.'], 422);
            }
            flash('error', 'Invalid donation data.');
            redirect('/donations');
        }
        $this->donations->create([
            'donor_id' => $donorId,
            'blood_group' => $bloodGroup,
            'quantity' => $quantity,
            'collection_staff_id' => $staffId,
            'date' => $date,
        ]);
        $this->units->create([
            'blood_group' => $bloodGroup,
            'quantity' => $quantity,
            'collection_date' => $date,
            'expiry_date' => date('Y-m-d', strtotime($date . ' +35 days')),
            'status' => 'available',
        ]);
        $this->logs->create([
            'action' => 'donation_added',
            'blood_group' => $bloodGroup,
            'quantity' => $quantity,
            'performed_by' => Auth::user()['id'],
            'timestamp' => date('Y-m-d H:i:s'),
        ]);
        (new Donor())->update($donorId, ['last_donation_date' => $date]);
        flash('success', 'Donation recorded and stock updated.');
        if ($this->isAjax()) {
            $this->json(['ok' => true, 'message' => 'Donation recorded and stock updated.']);
        }
        redirect('/donations');
    }

    public function delete(int $id): void
    {
        $this->requireCsrf();
        $this->donations->delete($id);
        flash('success', 'Donation deleted. Inventory entries remain for audit safety.');
        if ($this->isAjax()) {
            $this->json(['ok' => true, 'message' => 'Donation deleted. Inventory entries remain for audit safety.']);
        }
        redirect('/donations');
    }
}
