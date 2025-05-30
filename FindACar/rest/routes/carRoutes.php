<?php
require_once __DIR__ . '/../services/CarService.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

$auth = new AuthMiddleware();

// ======= ZAŠTIĆENE RUTE ZA ADMINA =======
Flight::route('GET /api/cars', function () use ($auth) {
    $auth->verifyToken();
    $auth->authorizeRole("admin");

    $service = new CarService();
    Flight::json($service->getAllCars());
});

Flight::route('GET /api/cars/@id', function ($id) use ($auth) {
    $auth->verifyToken();
    $auth->authorizeRole("admin");

    $service = new CarService();
    Flight::json($service->getCarById($id));
});

Flight::route('POST /api/cars', function () use ($auth) {
    $auth->verifyToken();
    $auth->authorizeRole("admin");

    $data = Flight::request()->data->getData();
    $service = new CarService();
    Flight::json($service->createCar($data));
});

Flight::route('PUT /api/cars/@id', function ($id) use ($auth) {
    $auth->verifyToken();
    $auth->authorizeRole("admin");

    $data = Flight::request()->data->getData();
    $service = new CarService();
    Flight::json($service->updateCar($id, $data));
});

Flight::route('DELETE /api/cars/@id', function ($id) use ($auth) {
    $auth->verifyToken();
    $auth->authorizeRole("admin");

    $service = new CarService();
    Flight::json($service->deleteCar($id));
});

// ======= JAVNA RUTA ZA DOSTUPNE AUTE =======
Flight::route('GET /cars/available', function () {
    Flight::json(Flight::car_service()->getAvailableCars());
});

// ✅ RUTA ZA SPECIAL AUTE (za admin dashboard)
Flight::route('GET /api/cars/special', function () use ($auth) {
    $auth->verifyToken();

    $service = new CarService();
    Flight::json($service->getSpecialCars());
});

// ✅ RUTA ZA CARS.HTML – special cars za korisnike i admine
Flight::route('GET /api/cars/special/all', function () {
    Flight::auth_middleware()->authorizeAnyRole(["user", "admin"]);

    $service = new CarService();
    $specialCars = $service->getSpecialCars();

    Flight::json($specialCars);
});
