<?php
require_once __DIR__ . '/../vendor/autoload.php';

// 🛡️ CORS za frontend pristup
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authentication");

// ✅ FLIGHTPHP dio
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/Database.php';

// 🔧 Servisi
require_once __DIR__ . '/services/AuthService.php';
require_once __DIR__ . '/services/Roles.php';
require_once __DIR__ . '/services/UserService.php';
require_once __DIR__ . '/services/CarService.php';
require_once __DIR__ . '/services/SubscriptionService.php';
require_once __DIR__ . '/services/ReservationService.php';
require_once __DIR__ . '/services/ContactFormService.php';

// 🔐 Middleware
require_once __DIR__ . '/middleware/AuthMiddleware.php';

// 🔧 Registracija servisa
Flight::register('auth_service', "AuthService");
Flight::register('auth_middleware', "AuthMiddleware");
Flight::register('user_service', "UserService");
Flight::register('car_service', "CarService");
Flight::register('subscription_service', "SubscriptionService");
Flight::register('reservation_service', "ReservationService");
Flight::register('contact_form_service', "ContactFormService");

// 🧱 Globalna JWT zaštita, osim za javne rute
Flight::before('start', function(&$params, &$output){
    $public_paths = ['/auth/login', '/auth/register', '/cars/available'];
    $path = Flight::request()->url;

    // Skip token validation for public paths
    foreach ($public_paths as $public_path) {
        if (strpos($path, $public_path) === 0) return;
    }

    // ✅ OPTIONS request (CORS preflight) – dozvoli odmah
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }

    // ✅ Robustno dohvaćanje Authorization headera
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? null;

    if (!$authHeader) {
        Flight::halt(401, "Missing Authorization header");
    }

    // Podrška za "Bearer <token>" format
    $token = str_starts_with($authHeader, 'Bearer ') ? substr($authHeader, 7) : $authHeader;

    try {
        Flight::auth_middleware()->verifyToken($token); // dekodira + setuje user
    } catch (Exception $e) {
        Flight::halt(401, "Invalid or expired token: " . $e->getMessage());
    }
});



// 📍 Rute (Flight-style)
require_once __DIR__ . '/routes/AuthRoutes.php';
require_once __DIR__ . '/routes/userRoutes.php';
require_once __DIR__ . '/routes/carRoutes.php';
require_once __DIR__ . '/routes/subscriptionRoutes.php';
require_once __DIR__ . '/routes/reservationRoutes.php';
require_once __DIR__ . '/routes/contactFormRoutes.php';

// 📍 FlightPHP postavke
Flight::set('flight.base_url', '');
Flight::start();
exit;

// 🟦 Fallback za BaseDao (Milestone 1 & 2)
require_once 'dao/BaseDao.php';

$entities = ['cars', 'users', 'reservations', 'subscriptions', 'contact_forms'];
$daos = [];
foreach ($entities as $entity) {
    $daos[$entity] = new BaseDao($entity);
}

$parts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$entity = $parts[count($parts) - 2] ?? null;
$id = $parts[count($parts) - 1] ?? null;

if (!is_numeric($id)) {
    $entity = $id;
    $id = null;
}

$entity = strtolower($entity);
$method = $_SERVER['REQUEST_METHOD'];

if (!isset($daos[$entity])) {
    http_response_code(404);
    echo json_encode(["error" => "Entity '$entity' not found"]);
    exit;
}

$dao = $daos[$entity];

try {
    switch ($method) {
        case 'GET':
            echo json_encode(is_numeric($id) ? $dao->getById($id) : $dao->getAll());
            break;
        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);
            echo json_encode($dao->insert($data));
            break;
        case 'PUT':
        case 'PATCH':
            if (!$id) throw new Exception("ID is required for update", 400);
            $data = json_decode(file_get_contents("php://input"), true);
            echo json_encode($dao->update($id, $data));
            break;
        case 'DELETE':
            if (!$id) throw new Exception("ID is required for delete", 400);
            echo json_encode($dao->delete($id));
            break;
        case 'OPTIONS':
            echo json_encode(["message" => "CORS preflight OK"]);
            break;
        default:
            throw new Exception("Method not allowed", 405);
    }
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode(["error" => $e->getMessage()]);
}
