<?php

namespace App\Controllers;

use App\Configuration;
use App\Models\User;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

class AuthController extends BaseController
{
    public function authorize(Request $request, string $action): bool
    {
        switch ($action) {
            case 'login':
            case 'register':
                return !$this->user->isLoggedIn();
            case 'logout':
                return $this->user->isLoggedIn();
            default:
                return false;
        }
    }

    public function index(Request $request): Response
    {
        return $this->redirect(Configuration::LOGIN_URL);
    }

    public function login(Request $request): Response
    {
        $message = null;
        if ($request->isPost()) {
            $email = strtolower(trim($request->value('email')));
            $password = $request->value('password');
            if ($this->app->getAuthenticator()->login($email, $password)) {
                return $this->redirect($this->url('home.index'));
            }
            $message = 'Invalid email or password';
        }
        return $this->html(compact('message'));
    }

    public function register(Request $request): Response
    {
        if ($request->isPost()) {
            $email = strtolower(trim($request->value('email')));
            $password = $request->value('password');
            $confirm = $request->value('confirm_password');

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->json(['success' => false, 'error' => 'Invalid email address.']);
            }
            if (strlen($password) < 8 ||
                !preg_match('/[a-z]/', $password) ||
                !preg_match('/[A-Z]/', $password) ||
                !preg_match('/[0-9]/', $password) ||
                !preg_match('/[^A-Za-z0-9]/', $password)) {
                return $this->json(['success' => false, 'error' => 'Password must be at least 8 chars and include upper, lower, digit and special char.']);
            }
            if ($password !== $confirm) {
                return $this->json(['success' => false, 'error' => 'Passwords do not match.']);
            }
            if (User::getCount('email = ?', [$email]) > 0) {
                return $this->json(['success' => false, 'error' => 'Email is already registered.']);
            }

            $user = new User();
            $user->setEmail($email);
            $user->setPasswordHash(password_hash($password, PASSWORD_DEFAULT));
            $user->setRole('guest');
            $user->save();
            $this->app->getAuthenticator()->login($email, $password);
            return $this->json(['success' => true]);
        }

        return $this->html();
    }

    public function logout(Request $request): Response
    {
        $this->app->getAuthenticator()->logout();
        return $this->redirect(Configuration::LOGIN_URL);
    }
}
