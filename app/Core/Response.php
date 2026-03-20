<?php
declare(strict_types=1);

/**
 * Response
 *
 * Represents an HTTP response.
 */
final class Response
{
    public function redirect(string $path, int $status = 302): void
    {
        http_response_code($status);
        header("Location: $path");
        exit;
    }
}