<?php
require_once __DIR__ . '/../vendor/autoload.php'; 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Ispravno učitavanje URI
$request_uri = $_SERVER['REQUEST_URI'];

// Popravi detekciju ako je u URL-u prisutan index.php
if (strpos($request_uri, '/index.php/api/') !== false) {
    $request_uri = str_replace('/index.php', '', $request_uri);
}

// Provjeri da li ide prema FlightPHP API rutama
if (strpos($request_uri, '/api/') !== false) {
    // 🟩 NOVI FLIGHTPHP API (Milestone 3)
    require_once __DIR__ . '/routes/userRoutes.php';
    require_once __DIR__ . '/routes/carRoutes.php';
    require_once __DIR__ . '/routes/subscriptionRoutes.php';
    require_once __DIR__ . '/routes/reservationRoutes.php';
    require_once __DIR__ . '/routes/contactFormRoutes.php';

    Flight::set('flight.base_url', '/TarikHodzic/Introduction-To-Web-Programming/FindACar/rest/index.php');
    Flight::start();
    exit;
}

// 🟦 STARI CUSTOM API (Milestone 1 i 2)

require_once 'config.php';
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

// ✅ Pretvaranje entiteta u lowercase da izbjegnemo "Tabela 'Cars' ne postoji"
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
?>
