<?php
declare(strict_types=1);

class DonorController extends Controller
{
    private Donor $donors;

    public function __construct()
    {
        $this->donors = new Donor();
    }

    public function index(): void
    {
        $query = trim((string) ($_GET['q'] ?? ''));
        $bloodGroup = trim((string) ($_GET['blood_group'] ?? ''));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = max(1, (int) ($_GET['per_page'] ?? 10));
        $edit = null;
        $id = (int) ($_GET['edit'] ?? 0);
        if ($id > 0) {
            $edit = $this->donors->find($id);
        }
        $pagination = $this->donors->searchPaginated($query, $bloodGroup, $page, $perPage);
        $donors = $pagination['data'];

        if ($this->isAjax()) {
            $html = $this->renderPartial('donors/_list', compact('donors', 'pagination', 'query', 'bloodGroup', 'currentUser'));
            $this->json(['ok' => true, 'html' => $html]);
        }
        $this->view('donors/index', compact('donors', 'edit', 'query', 'bloodGroup', 'pagination'));
    }

    public function store(): void
    {
        $this->requireCsrf();
        $name = trim((string) $this->request('name'));
        $age = (int) $this->request('age');
        $gender = (string) $this->request('gender');
        $bloodGroup = (string) $this->request('blood_group');
        $contact = trim((string) $this->request('contact'));
        $lastDonationDate = trim((string) $this->request('last_donation_date'));

        if (
            $name === '' || mb_strlen($name) > 120 || $age < 18 || $age > 65
            || !in_array($gender, ['male', 'female', 'other'], true)
            || !is_valid_blood_group($bloodGroup)
            || !is_valid_phone($contact)
            || ($lastDonationDate !== '' && !is_valid_date($lastDonationDate))
        ) {
            if ($this->isAjax()) {
                $this->json(['ok' => false, 'message' => 'Invalid donor data.'], 422);
            }
            flash('error', 'Invalid donor data.');
            redirect('/donors');
        }
        $this->donors->create([
            'name' => $name,
            'age' => $age,
            'gender' => $gender,
            'blood_group' => $bloodGroup,
            'contact' => $contact,
            'address' => trim((string) $this->request('address')),
            'last_donation_date' => $lastDonationDate ?: null,
        ]);
        if ($this->isAjax()) {
            $this->json(['ok' => true, 'message' => 'Donor registered successfully.']);
        }
        flash('success', 'Donor registered.');
        redirect('/donors');
    }

    public function update(int $id): void
    {
        $this->requireCsrf();
        $name = trim((string) $this->request('name'));
        $age = (int) $this->request('age');
        $gender = (string) $this->request('gender');
        $bloodGroup = (string) $this->request('blood_group');
        $contact = trim((string) $this->request('contact'));
        $lastDonationDate = trim((string) $this->request('last_donation_date'));
        if (
            $name === '' || mb_strlen($name) > 120 || $age < 18 || $age > 65
            || !in_array($gender, ['male', 'female', 'other'], true)
            || !is_valid_blood_group($bloodGroup)
            || !is_valid_phone($contact)
            || ($lastDonationDate !== '' && !is_valid_date($lastDonationDate))
        ) {
            if ($this->isAjax()) {
                $this->json(['ok' => false, 'message' => 'Invalid donor data.'], 422);
            }
            flash('error', 'Invalid donor data.');
            redirect('/donors');
        }
        $this->donors->update($id, [
            'name' => $name,
            'age' => $age,
            'gender' => $gender,
            'blood_group' => $bloodGroup,
            'contact' => $contact,
            'address' => trim((string) $this->request('address')),
            'last_donation_date' => $lastDonationDate ?: null,
        ]);
        if ($this->isAjax()) {
            $this->json(['ok' => true, 'message' => 'Donor updated successfully.']);
        }
        flash('success', 'Donor updated.');
        redirect('/donors');
    }

    public function delete(int $id): void
    {
        $this->requireCsrf();
        $this->donors->delete($id);
        flash('success', 'Donor deleted.');
        redirect('/donors');
    }
}
