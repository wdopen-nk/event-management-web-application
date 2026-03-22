<?php
declare(strict_types=1);

final class View
{
    public static function render(string $view, array $params = []): void
    {
        extract($params, EXTR_SKIP);

        ob_start();
        require __DIR__ . '/../Views/' . $view . '.php';
        $content = ob_get_clean();

        require __DIR__ . '/../Views/layout.php';
    }

    public static function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}