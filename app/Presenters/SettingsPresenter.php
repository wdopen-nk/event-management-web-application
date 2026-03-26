<?php

final class SettingsPresenter extends Presenter
{
    public function index(): void
    {
        Auth::requireLogin();

        $user = Auth::user();
        $error = null;
        $success = null;

        if ($this->request->method() === 'POST') {

            // CSRF protection
            if (!Csrf::verify($this->request->post('_csrf'))) {
                http_response_code(403);
                exit;
            }

            // if (!Csrf::verify($this->request->post('_csrf'))) {
            //     throw new RuntimeException('Invalid CSRF token');
            // }

            // ===== UPDATE PROFILE =====
            if ($this->request->post('update_profile') !== null) {

                $fullName = trim($this->request->post('full_name'));

                if ($fullName === '') {
                    $error = 'Full name cannot be empty.';
                } 
                
                else {
                    UserModel::updateName($user['id'], $fullName);
                    Auth::refreshUserName($fullName);

                    $this->redirect(BASE_PATH . '/settings');
                    return;
                }
            }

            // ===== DELETE ACCOUNT =====
            if ($this->request->post('delete_account') !== null) {

                UserModel::deleteAccount($user['id']);
                Auth::logout();

                $this->redirect(BASE_PATH . '/');
                return;
            }
        }

        $this->render('settings/index', [
            'user'    => Auth::user(),
            'error'   => $error,
            'success' => $success,
            'csrf'    => Csrf::token(),
        ]);
    }
}
