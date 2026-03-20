<?php
declare(strict_types=1);

/**
 * NotFoundException
 *
 * Thrown when no route matches the incoming request.
 * Caught by the Front Controller to render a 404 page.
 */
final class NotFoundException extends Exception
{
}