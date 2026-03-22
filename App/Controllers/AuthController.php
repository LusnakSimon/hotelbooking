<?php

namespace App\Controllers;

use App\Configuration;
use App\Models\User;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;
use Framework\Http\Responses\ViewResponse;

/**
 * Class AuthController
 *
 * This controller handles authentication actions such as login, logout, and redirection to the login page. It manages
 * user sessions and interactions with the authentication system.
 *
 * @package App\Controllers
 */
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
    /**
     * Redirects to the login page.
     *
     * This action serves as the default landing point for the authentication section of the application, directing
     * users to the login URL specified in the configuration.
     *
     * @return Response The response object for the redirection to the login page.
     */
    public function index(Request $request): Response
    {
        return $this->redirect(Configuration::LOGIN_URL);
    }

    /**
     * Authenticates a user and processes the login request.
     *
     * This action handles user login attempts. If the login form is submitted, it attempts to authenticate the user
     * with the provided credentials. Upon successful login, the user is redirected to the admin dashboard.
     * If authentication fails, an error message is displayed on the login page.
     *
     * @return Response The response object which can either redirect on success or render the login view with
     *                  an error message on failure.
     * @throws Exception If the parameter for the URL generator is invalid throws an exception.
     */
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

        $message = $logged === false ? 'Bad email or password' : null;
        return $this->html(compact('message'));
    }

    public function register(Request $request): Response
    {
        // DEBUG: log request info to help diagnose silent form submissions
        try {
            $debugPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'storage';
            if (!is_dir($debugPath)) {
                @mkdir($debugPath, 0777, true);
            }
            $log = sprintf("[%s] METHOD=%s isPost=%s hasEmail=%s hasSubmit=%s POST=%s\n",
                date('c'),
                $_SERVER['REQUEST_METHOD'] ?? 'n/a',
                $request->isPost() ? '1' : '0',
                $request->hasValue('email') ? '1' : '0',
                $request->hasValue('submit') ? '1' : '0',
                json_encode($_POST)
            );
            @file_put_contents($debugPath . DIRECTORY_SEPARATOR . 'register_debug.txt', $log, FILE_APPEND);
        } catch (\Throwable $e) {
            // ignore logging errors
        }

        if ($request->isPost() || $request->hasValue('submit') || $request->hasValue('email')) {
            $email = strtolower(trim((string)$request->value('email')));
            $password = (string)$request->value('password');
            $confirm = (string)$request->value('confirm_password');

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return $this->html(['error' => 'Invalid email address.', 'email' => $email]);
            }

                if (strlen($password) < 8 || !preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*\\W)/', $password)) {
                    return $this->html(['error' => 'Password must be at least 8 chars and include upper, lower, digit and special char.', 'email' => $email]);
            }

                if ($password !== $confirm) {
                    return $this->html(['error' => 'Passwords do not match.', 'email' => $email]);
            }

                if (User::getCount('email = ?', [$email]) > 0) {
                    return $this->html(['error' => 'Email is already registered.', 'email' => $email]);
            }

            $user = new User();
            $user->setEmail($email);
            $user->setPasswordHash(password_hash($password, PASSWORD_DEFAULT));
            $user->setRole('guest');

            try {
                $user->save();
                // Auto-login the new user and redirect to home
                $this->app->getAuthenticator()->login($email, $password);
                return $this->redirect($this->url('home.index'));
            } catch (\Throwable $e) {
                 return $this->html(['error' => 'Failed to create account.', 'email' => $email]);
            }
        }

        return $this->html();
    }

    /**
     * Logs out the current user.
     *
     * This action terminates the user's session and redirects them to a view. It effectively clears any authentication
     * tokens or session data associated with the user.
     *
     * @return ViewResponse The response object that renders the logout view.
     */
    public function logout(Request $request): Response
    {
        $this->app->getAuthenticator()->logout();
        return $this->redirect(Configuration::LOGIN_URL);
    }
}
