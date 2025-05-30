<?php
require_once 'BaseDao.php';

class ReservationsDAO extends BaseDao {
    public function __construct() {
        parent::__construct('reservations');
    }

    public function getReservationsByUser($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM reservations WHERE user_id = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($data) {
    try {
        error_log("🧾 Podaci za rezervaciju:");
        error_log(json_encode($data));

        $stmt = $this->conn->prepare("INSERT INTO reservations (user_id, car_id, start_date, end_date, total_price)
                                      VALUES (:user_id, :car_id, :start_date, :end_date, :total_price)");
        $stmt->execute([
            'user_id' => $data['user_id'],
            'car_id' => $data['car_id'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'total_price' => $data['total_price']
        ]);

        $data['id'] = $this->conn->lastInsertId();
        return $data;

    } catch (PDOException $e) {
        // OVO ISPISUJE GREŠKU NA FRONTENDU
        http_response_code(500);
    echo json_encode(["error" => "❌ BACKEND GREŠKA: " . $e->getMessage()]);
    exit;
    }
}


}
?>
