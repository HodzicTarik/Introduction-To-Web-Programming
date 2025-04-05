<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require_once 'config.php';
require_once 'dao/BaseDao.php';

$entities = ['cars', 'users', 'reservations', 'subscriptions', 'contact_forms'];
$daos = [];
foreach ($entities as $entity) {
    $daos[$entity] = new BaseDao($entity);
}

$request_uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$entity = $request_uri[count($request_uri) - 2] ?? null;
$id = $request_uri[count($request_uri) - 1] ?? null;

if (!is_numeric($id)) {
    $entity = $id;
    $id = null;
}

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
