<?php
declare(strict_types=1);

/**
 * Request
 *
 * Represents an HTTP request.
 * Wraps PHP superglobals to decouple application logic from globals.
 */
final class Request
{
    private string $method;
    private string $path;
    private array $query;
    private array $post;
    private array $params = [];

    private function __construct(
        string $method,
        string $path,
        array $query,
        array $post
    ) {
        $this->method = $method;
        $this->path   = $path;
        $this->query  = $query;
        $this->post   = $post;
    }

    /**
     * Factory method to create Request from PHP globals.
     */
    public static function fromGlobals(): self
    {
        $uri  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = rtrim($uri, '/') ?: '/';

        return new self(
            $_SERVER['REQUEST_METHOD'],
            $path,
            $_GET,
            $_POST
        );
    }

    public function method(): string
    {
        return $this->method;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function query(string $key, $default = null)
    {
        return $this->query[$key] ?? $default;
    }

    public function post(string $key, $default = null)
    {
        return $this->post[$key] ?? $default;
    }

    /**
     * Set a single route parameter (e.g. {id})
     */
    public function setParam(string $key, string $value): void
    {
        $this->params[$key] = $value;
    }

    public function param(string $key)
    {
        return $this->params[$key] ?? null;
    }
}
