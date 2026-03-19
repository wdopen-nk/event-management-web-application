<?php
declare(strict_types=1);

// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

define('BASE_PATH', '/~53737289/semestral-project-test');

/**
 * Front Controller
 * ----------------
 * All HTTP requests enter the application through this file.
 */

// --------------------------------------------------
// 1) Bootstrap
// --------------------------------------------------

session_start();

require_once __DIR__ . '/app/Core/Request.php';
require_once __DIR__ . '/app/Core/Response.php';
require_once __DIR__ . '/app/Core/Router.php';
require_once __DIR__ . '/app/Core/View.php';
require_once __DIR__ . '/app/Core/Auth.php';
require_once __DIR__ . '/app/Core/Csrf.php';
require_once __DIR__ . '/app/Core/DB.php';
require_once __DIR__ . '/app/Core/NotFoundException.php';

// Presenters
require_once __DIR__ . '/app/Presenters/Presenter.php';
require_once __DIR__ . '/app/Presenters/EventsPresenter.php';
require_once __DIR__ . '/app/Presenters/AuthPresenter.php';
require_once __DIR__ . '/app/Presenters/SettingsPresenter.php';

// Models
require_once __DIR__ . '/app/Models/EventModel.php';
require_once __DIR__ . '/app/Models/UserModel.php';
require_once __DIR__ . '/app/Models/RegistrationModel.php';

// --------------------------------------------------
// 2) Create core objects
// --------------------------------------------------

$request  = Request::fromGlobals();
$response = new Response();
$router   = new Router($request, $response);

// --------------------------------------------------
// 3) Define routes
// --------------------------------------------------

// Home / landing page
$router->get('/', 'EventsPresenter@home');

// Events
$router->get('/events', 'EventsPresenter@list');
$router->get('/events/new', 'EventsPresenter@create');
$router->post('/events/new', 'EventsPresenter@create');

$router->get('/events/mine', 'EventsPresenter@mine');

$router->get('/events/{id}', 'EventsPresenter@detail');

$router->get('/events/{id}/edit', 'EventsPresenter@edit');
$router->post('/events/{id}/edit', 'EventsPresenter@edit');

$router->get('/events/{id}/register', 'EventsPresenter@register');
$router->post('/events/{id}/register', 'EventsPresenter@register');

$router->post('/events/{id}/cancel', 'EventsPresenter@cancel');

// Authentication
$router->get('/login', 'AuthPresenter@login');
$router->post('/login', 'AuthPresenter@login');

$router->get('/register', 'AuthPresenter@register');
$router->post('/register', 'AuthPresenter@register');

$router->post('/logout', 'AuthPresenter@logout');

// Settings
$router->get('/settings', 'SettingsPresenter@index');
$router->post('/settings', 'SettingsPresenter@index');

// --------------------------------------------------
// 4) Dispatch with error handling
// --------------------------------------------------

try {
    $router->dispatch();
} 

catch (NotFoundException $e) {
    http_response_code(404);
    View::render('errors/404');

} 

catch (Throwable $e) {
    http_response_code(500);
    View::render('errors/500');
}
