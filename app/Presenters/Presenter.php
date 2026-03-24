<?php
declare(strict_types=1);

/**
 * Presenter
 *
 * Base class for all presenters in the application.
 * Implements shared functionality such as rendering views and redirects.
 */
abstract class Presenter
{
    protected Request $request;
    protected Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request  = $request;
        $this->response = $response;
    }

    protected function render(string $template, array $params = []): void
    {
        $params['user'] = $_SESSION['user'] ?? null;
        View::render($template, $params);
    }

    protected function redirect(string $path): void
    {
        $this->response->redirect($path);
    }
}
