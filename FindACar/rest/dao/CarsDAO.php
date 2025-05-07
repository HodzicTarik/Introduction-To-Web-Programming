<?php
require_once 'BaseDao.php';

class CarsDAO extends BaseDao {
    public function __construct() {
        parent::__construct('cars');
        // test konekcije
    if (!$this->connection) {
        die("❌ Connection failed");
    }
    }

    public function getAvailableCars() {
        try {
            $stmt = $this->connection->query("SELECT * FROM cars WHERE available = 1");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
?>