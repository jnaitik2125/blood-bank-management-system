<?php
declare(strict_types=1);

class AuthController extends Controller
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            redirect('/dashboard');
        }
        $this->view('auth/login');
    }

    public function login(): void
    {
        $this->requireCsrf();
        $emailInput = trim((string) $this->request('email'));
        $email = is_valid_email($emailInput) ? $emailInput : false;
        $password = (string) $this->request('password');
        if (!$email || $password === '') {
            $this->storeOldInput();
            flash('error', 'Invalid email or password format.');
            redirect('/login');
        }
        if (!Auth::attempt($email, $password)) {
            $this->storeOldInput();
            flash('error', 'Invalid credentials or inactive user.');
            redirect('/login');
        }
        redirect('/dashboard');
    }

    public function logout(): void
    {
        Auth::logout();
        redirect('/login');
    }
}
