<?php
require_once 'BaseDao.php';

class ReservationsDAO extends BaseDao {
    public function __construct() {
        parent::__construct('reservations');
    }

    public function getReservationsByUser($userId) {
        $stmt = $this->connection->prepare("SELECT * FROM reservations WHERE user_id = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
