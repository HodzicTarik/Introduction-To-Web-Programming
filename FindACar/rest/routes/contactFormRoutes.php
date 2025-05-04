<?php
require_once __DIR__ . '/../services/ContactFormService.php';

Flight::route('GET /api/contact', function() {
    $service = new ContactFormService();
    Flight::json($service->getAllContacts());
});

Flight::route('GET /api/contact/@id', function($id) {
    $service = new ContactFormService();
    Flight::json($service->getContactById($id));
});

Flight::route('POST /api/contact', function() {
    $data = Flight::request()->data->getData();
    $service = new ContactFormService();
    Flight::json($service->createContact($data));
});

Flight::route('PUT /api/contact/@id', function($id) {
    $data = Flight::request()->data->getData();
    $service = new ContactFormService();
    Flight::json($service->updateContact($id, $data));
});

Flight::route('DELETE /api/contact/@id', function($id) {
    $service = new ContactFormService();
    Flight::json($service->deleteContact($id));
});
