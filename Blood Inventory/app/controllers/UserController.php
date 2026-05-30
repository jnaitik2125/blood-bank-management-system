<?php
declare(strict_types=1);

class UserController extends Controller
{
    private User $users;

    public function __construct()
    {
        $this->users = new User();
    }

    public function index(): void
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = max(1, (int) ($_GET['per_page'] ?? 10));
        $edit = null;
        $id = (int) ($_GET['edit'] ?? 0);
        if ($id > 0) {
            $edit = $this->users->find($id);
        }
        $pagination = $this->users->paginated($page, $perPage);
        $users = $pagination['data'];
        $this->view('users/index', compact('users', 'edit', 'pagination'));
    }

    public function store(): void
    {
        $this->requireCsrf();
        $name = trim((string) $this->request('name'));
        $emailRaw = trim((string) $this->request('email'));
        $email = is_valid_email($emailRaw) ? $emailRaw : false;
        $role = (string) $this->request('role');
        $status = (int) $this->request('status', 1);
        $password = (string) $this->request('password');

        if ($name === '' || !$email || !in_array($role, ['admin', 'receptionist', 'collection_staff'], true) || strlen($password) < 8) {
            flash('error', 'Please provide valid user details.');
            redirect('/users');
        }

        $this->users->create([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role,
            'status' => $status ? 1 : 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        flash('success', 'User created successfully.');
        redirect('/users');
    }

    public function update(int $id): void
    {
        $this->requireCsrf();
        $name = trim((string) $this->request('name'));
        $emailRaw = trim((string) $this->request('email'));
        $email = is_valid_email($emailRaw) ? $emailRaw : false;
        $role = (string) $this->request('role');
        $status = (int) $this->request('status', 1);
        $password = (string) $this->request('password');
        if ($name === '' || !$email || !in_array($role, ['admin', 'receptionist', 'collection_staff'], true)) {
            flash('error', 'Invalid user data.');
            redirect('/users');
        }
        $data = ['name' => $name, 'email' => $email, 'role' => $role, 'status' => $status ? 1 : 0];
        if ($password !== '') {
            if (strlen($password) < 8) {
                flash('error', 'Password must be at least 8 characters.');
                redirect('/users');
            }
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
        $this->users->update($id, $data);
        flash('success', 'User updated successfully.');
        redirect('/users');
    }

    public function delete(int $id): void
    {
        $this->requireCsrf();
        if (Auth::user()['id'] === $id) {
            flash('error', 'You cannot delete your own account.');
            redirect('/users');
        }
        $this->users->delete($id);
        flash('success', 'User deleted.');
        redirect('/users');
    }
}
