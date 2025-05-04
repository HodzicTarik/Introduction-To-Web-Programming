<?php
require_once __DIR__ . '/../services/UserService.php';

Flight::route('GET /api/users', function() {
    $service = new UserService();
    Flight::json($service->getAllUsers());
});

Flight::route('GET /api/users/@id', function($id) {
    $service = new UserService();
    Flight::json($service->getUserById($id));
});

Flight::route('POST /api/users', function() {
    $data = Flight::request()->data->getData();
    $service = new UserService();
    Flight::json($service->createUser($data));
});

Flight::route('PUT /api/users/@id', function($id) {
    $data = Flight::request()->data->getData();
    $service = new UserService();
    Flight::json($service->updateUser($id, $data));
});

Flight::route('DELETE /api/users/@id', function($id) {
    $service = new UserService();
    Flight::json($service->deleteUser($id));
});
