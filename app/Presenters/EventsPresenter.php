<?php
declare(strict_types=1);

/**
 * EventsPresenter
 *
 * Handles all event-related pages:
 *  - list of events
 *  - event detail
 *  - registration
 *  - edit
 *  - user events
 *
 * Implements the Presenter role in MVP.
 */

final class EventsPresenter extends Presenter
{
    public function list(): void
    {
        $events = EventModel::allWithOrganizer();

        $this->render('events/list', [
            'events' => $events,
            'user'   => Auth::user(),
            'csrf'   => Csrf::token(),
        ]);
    }

    public function detail(): void
    {
        $id = (int)$this->request->param('id');

        $event = EventModel::findWithOrganizer($id);
        if (!$event) {
            $this->redirect(BASE_PATH . '/events');
        }

        $workshops = EventModel::workshopsForEvent($id);

        $user = Auth::user();
        $isOwner = $user && $event['created_by'] === $user['id'];
        $isRegistered = $user && RegistrationModel::isRegistered($user['id'], $id);

        $this->render('events/detail', [
            'event'        => $event,
            'workshops'    => $workshops,
            'user'         => $user,
            'isOwner'      => $isOwner,
            'isRegistered' => $isRegistered,
            'csrf'         => Csrf::token(),
        ]);
    }

    public function edit(): void
    {
        Auth::requireLogin();

        $eventId = (int) $this->request->param('id');
        $user    = Auth::user();

        // fetch event
        $event = EventModel::findById($eventId);

        // event must exist + user must be owner
        if (!$event || $event['created_by'] !== $user['id']) {
            $this->redirect(BASE_PATH . '/events');
        }

        $error = null;

        // handle POST
        if ($this->request->method() === 'POST') {

            // CSRF protection
            if (!Csrf::verify($this->request->post('_csrf'))) {
                http_response_code(403);
                exit;
            }

            // DELETE EVENT
            if ($this->request->post('delete') !== null) {
                EventModel::delete($eventId);
                $this->redirect(BASE_PATH . '/events');
                return;
            }

            // UPDATE EVENT (manual validation)
            $name = trim($this->request->post('name'));
            $description = trim($this->request->post('description'));
            $start = $this->request->post('start_date');
            $end  = $this->request->post('end_date');

            if ($name === '' || strlen($name) > 64) {
                $error = 'Invalid name (max 64 characters).';
            } 
            
            elseif ($description === '' || strlen($description) > 1024) {
                $error = 'Invalid description (max 1024 characters).';
            } 
            
            elseif (!$start || !$end) {
                $error = 'Start and end dates are required.';
            } 
            
            elseif (strtotime($end) < strtotime($start)) {
                $error = 'End date must be equal or later than start date.';
            } 
            
            else {
                // data is valid → update
                EventModel::updateFromRequest($eventId, $this->request);
                $this->redirect(BASE_PATH . "/events/$eventId");
                return;
            }
        }

        // render edit form
        $this->render('events/edit', [
            'event'     => $event,
            'workshops' => EventModel::workshopsForEvent($eventId),
            'error'     => $error,
            'csrf'      => Csrf::token(),
            'user'      => $user,
        ]);
    }


    public function register(): void
    {
        Auth::requireLogin();

        $eventId = (int)$this->request->param('id');
        $userId  = Auth::user()['id'];

        $event = EventModel::findById($eventId);
        if (!$event) {
            $this->redirect(BASE_PATH . '/events');
        }

        $workshops = EventModel::workshopsForEvent($eventId);
        $isRegistered = RegistrationModel::isRegistered($userId, $eventId);

        $selected = RegistrationModel::selectedWorkshopIds($userId, $eventId);
        $error = null;

        if ($this->request->method() === 'POST') {

            if (!Csrf::verify($this->request->post('_csrf'))) {
                http_response_code(403);
                exit;
            }

            $chosen = $this->request->post('workshops', []);

            if (count($chosen) === 0) {
                $error = 'You must select at least one workshop.';
            } else {
                RegistrationModel::save($userId, $eventId, $chosen);
                $this->redirect(BASE_PATH . "/events/$eventId");
            }
        }

        $this->render('events/register', [
            'event'        => $event,
            'workshops'    => $workshops,
            'selected'     => $selected,
            'isRegistered' => $isRegistered,
            'error'        => $error,
            'csrf'         => Csrf::token(),
            'user'         => Auth::user(),
        ]);
    }

    public function cancel(): void
    {
        Auth::requireLogin();

        // CSRF protection
        if (!Csrf::verify($this->request->post('_csrf'))) {
            http_response_code(403);
            exit;
        }

        $eventId = (int) $this->request->param('id');
        $userId  = Auth::user()['id'];

        // Cancel registration safely
        RegistrationModel::cancel($userId, $eventId);

        // Redirect back to event detail
        $this->redirect(BASE_PATH . "/events/$eventId");
    }

    public function create(): void
    {
        Auth::requireLogin();

        $error = null;

        if ($this->request->method() === 'POST') {

            // CSRF protection
            if (!Csrf::verify($this->request->post('_csrf'))) {
                http_response_code(403);
                exit;
            }

            $name        = trim($this->request->post('name'));
            $description = trim($this->request->post('description'));
            $start       = $this->request->post('start_date');
            $end         = $this->request->post('end_date');
            $workshops   = $this->request->post('workshops', []);

            // validation
            if ($name === '' || strlen($name) > 64) {
                $error = 'Invalid name (max 64 characters).';
            } 
            
            elseif ($description === '' || strlen($description) > 1024) {
                $error = 'Invalid description (max 1024 characters).';
            } 
            
            elseif (!$start || !$end) {
                $error = 'Start and end date are required.';
            } 
            
            elseif (strtotime($start) <= time()) {
                $error = 'Start date must be in the future.';
            } 
            
            elseif (strtotime($end) < strtotime($start)) {
                $error = 'End date must be the same or later than start date.';
            } 
            
            else {
                // create event
                $eventId = EventModel::create(
                    Auth::user()['id'],
                    $name,
                    $description,
                    $start,
                    $end,
                    null, // hero image handled in model
                    $workshops
                );

                $this->redirect(BASE_PATH . "/events/$eventId");
                return;
            }
        }

        $this->render('events/create', [
            'error' => $error,
            'csrf'  => Csrf::token(),
            'user'  => Auth::user(),
        ]);
    }

    public function mine(): void
    {
        Auth::requireLogin();

        $user = Auth::user();

        // fetch events with workshops user actually registered for
        $events = EventModel::findRegisteredByUser($user['id']);

        $this->render('events/mine', [
            'events' => $events,
            'user'   => $user,
        ]);
    }

    public function home(): void
    {
        // Fetch newest events (limit 3)
        $events = EventModel::newest(3);

        // Render landing page
        $this->render('events/home', [
            'events' => $events,
            'user'   => Auth::user(),
        ]);
    }
}