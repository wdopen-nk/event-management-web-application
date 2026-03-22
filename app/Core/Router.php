<?php
declare(strict_types=1);

final class Router
{
    private Request $request;
    private Response $response;

    /** @var array<string, array<int, array{pattern:string, handler:string}>> */
    private array $routes = [
        'GET'  => [],
        'POST' => [],
    ];

    public function __construct(Request $request, Response $response)
    {
        $this->request  = $request;
        $this->response = $response;
    }

    public function get(string $path, string $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, string $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, string $handler): void
    {
        // convert /events/{id}/edit → regex
        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[$method][] = [
            'pattern' => $pattern,
            'handler' => $handler,
        ];
    }

    public function dispatch(): void
    {
        $method = $this->request->method();
        $path   = $this->request->path();

        if (defined('BASE_PATH') && str_starts_with($path, BASE_PATH)) {
            $path = substr($path, strlen(BASE_PATH));
        }

        // normalize empty path
        if ($path === '') {
            $path = '/';
        }

        foreach ($this->routes[$method] ?? [] as $route) {

            if (preg_match($route['pattern'], $path, $matches)) {

                // extract named params
                foreach ($matches as $key => $value) {
                    if (!is_int($key)) {
                        $this->request->setParam($key, $value);
                    }
                }

                $this->invokeHandler($route['handler']);
                return;
            }
        }

        throw new NotFoundException("Route not found: $path");
    }

    private function invokeHandler(string $handler): void
    {
        [$presenterName, $method] = explode('@', $handler);

        $class = $presenterName;

        if (!class_exists($class)) {
            throw new RuntimeException("Presenter not found: $class");
        }

        $presenter = new $class($this->request, $this->response);

        if (!method_exists($presenter, $method)) {
            throw new RuntimeException("Method $method not found in $class");
        }

        $presenter->$method();
    }
}
