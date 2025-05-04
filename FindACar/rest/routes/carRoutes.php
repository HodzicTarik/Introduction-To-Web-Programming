<?php
require_once __DIR__ . '/../services/CarService.php';

Flight::route('GET /api/cars', function() {
    $service = new CarService();
    Flight::json($service->getAllCars());
});

Flight::route('GET /api/cars/@id', function($id) {
    $service = new CarService();
    Flight::json($service->getCarById($id));
});

Flight::route('POST /api/cars', function() {
    $data = Flight::request()->data->getData();
    $service = new CarService();
    Flight::json($service->createCar($data));
});

Flight::route('PUT /api/cars/@id', function($id) {
    $data = Flight::request()->data->getData();
    $service = new CarService();
    Flight::json($service->updateCar($id, $data));
});

Flight::route('DELETE /api/cars/@id', function($id) {
    $service = new CarService();
    Flight::json($service->deleteCar($id));
});
