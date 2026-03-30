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
        $logged = null;
        if ($request->isPost()) {
            $email = strtolower(trim((string)$request->value('email')));
            $password = (string)$request->value('password');
            $logged = $this->app->getAuthenticator()->login($email, $password);
            if ($logged) {
                return $this->redirect($this->url('home.index'));
            }
        }

        $message = $logged === false ? 'Invalid email or password' : null;
        return $this->html(compact('message'));
    }

    public function register(Request $request): Response
    {
        if ($request->isPost()) {
            $email = strtolower(trim((string)$request->value('email')));
            $password = (string)$request->value('password');
            $confirm = (string)$request->value('confirm_password');

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email address.';
                if ($request->isAjax()) return $this->json(['success' => false, 'error' => $error]);
                return $this->html(['error' => $error, 'email' => $email]);
            }

            if (strlen($password) < 8 || !preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W)/', $password)) {
                $error = 'Password must be at least 8 chars and include upper, lower, digit and special char.';
                if ($request->isAjax()) return $this->json(['success' => false, 'error' => $error]);
                return $this->html(['error' => $error, 'email' => $email]);
            }

            if ($password !== $confirm) {
                $error = 'Passwords do not match.';
                if ($request->isAjax()) return $this->json(['success' => false, 'error' => $error]);
                return $this->html(['error' => $error, 'email' => $email]);
            }

            if (User::getCount('email = ?', [$email]) > 0) {
                $error = 'Email is already registered.';
                if ($request->isAjax()) return $this->json(['success' => false, 'error' => $error]);
                return $this->html(['error' => $error, 'email' => $email]);
            }

            $user = new User();
            $user->setEmail($email);
            $user->setPasswordHash(password_hash($password, PASSWORD_DEFAULT));
            $user->setRole('guest');

            try {
                $user->save();
                $this->app->getAuthenticator()->login($email, $password);
                if ($request->isAjax()) return $this->json(['success' => true]);
                return $this->redirect($this->url('home.index'));
            } catch (\Throwable $e) {
                $error = 'Failed to create account.';
                if ($request->isAjax()) return $this->json(['success' => false, 'error' => $error]);
                return $this->html(['error' => $error, 'email' => $email]);
            }
        }

        return $this->html();
    }

    public function logout(Request $request): Response
    {
        $this->app->getAuthenticator()->logout();
        return $this->redirect(Configuration::LOGIN_URL);
    }
}
