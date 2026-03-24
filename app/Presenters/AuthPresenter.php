<?php

final class AuthPresenter extends Presenter
{
    public function login(): void
    {
        $error = null;

        if ($this->request->method() === 'POST') {

            // CSRF protection
            if (!Csrf::verify($this->request->post('_csrf'))) {
                http_response_code(403);
                exit;
            }

            $email = trim($this->request->post('email'));

            if ($email === '') {
                $error = 'Email is required.';
            } else {
                $user = UserModel::findByEmail($email);

                if (!$user) {
                    $error = 'No user found with this email.';
                } else {
                    Auth::login($user);
                    $this->redirect(BASE_PATH . '/');
                    return;
                }
            }
        }

        $this->render('auth/login', [
            'error' => $error,
            'csrf'  => Csrf::token(),
        ]);
    }

    public function logout(): void
    {
        // CSRF protection
        if (!Csrf::verify($this->request->post('_csrf'))) {
            http_response_code(403);
            exit;
        }

        Auth::logout();
        $this->redirect(BASE_PATH .'/');
    }

    public function register(): void
    {
        $error = null;

        if ($this->request->method() === 'POST') {

            // CSRF protection
            if (!Csrf::verify($this->request->post('_csrf'))) {
                http_response_code(403);
                exit;
            }

            $fullName = trim($this->request->post('full_name'));
            $email    = trim($this->request->post('email'));

            if ($fullName === '' || $email === '') {
                $error = 'Full name and email are required.';
            } elseif (UserModel::emailExists($email)) {
                // requirement: redirect to login if user exists
                $this->redirect(BASE_PATH . '/login');
                return;
            } else {
                UserModel::create($fullName, $email);
                $this->redirect(BASE_PATH . '/login');
                return;
            }
        }

        $this->render('auth/register', [
            'error' => $error,
            'csrf'  => Csrf::token(),
        ]);
    }

}
