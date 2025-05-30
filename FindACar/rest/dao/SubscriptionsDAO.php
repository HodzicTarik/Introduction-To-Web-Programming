<?php
require_once 'BaseDao.php';

class SubscriptionsDAO extends BaseDao {
    public function __construct() {
        parent::__construct('subscriptions');
    }

    // ✅ Samo planovi koji su template (admin dashboard)
    public function getTemplatesOnly() {
        $stmt = $this->conn->query("SELECT * FROM subscriptions WHERE is_template = 1");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Sve korisničke pretplate (npr. za /subscriptions/available)
    public function getAllUserSubscriptions() {
        $stmt = $this->conn->query("SELECT * FROM subscriptions WHERE is_template = 0");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM subscriptions WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($subscription) {
        $stmt = $this->conn->prepare("INSERT INTO subscriptions (plan, price, description, user_id, start_date, end_date, is_template)
                                    VALUES (:plan, :price, :description, :user_id, :start_date, :end_date, 0)");
        $stmt->execute([
            'plan' => $subscription['plan'],
            'price' => $subscription['price'],
            'description' => $subscription['description'] ?? null,
            'user_id' => $subscription['user_id'] ?? null,
            'start_date' => $subscription['start_date'] ?? null,
            'end_date' => $subscription['end_date'] ?? null
        ]);
        return $this->getById($this->conn->lastInsertId());
    }


    public function update($id, $subscription) {
        $stmt = $this->conn->prepare("UPDATE subscriptions SET
                                      plan = :plan,
                                      price = :price,
                                      description = :description
                                      WHERE id = :id");
        $stmt->execute([
            'id' => $id,
            'plan' => $subscription['plan'],
            'price' => $subscription['price'],
            'description' => $subscription['description'] ?? null
        ]);
        return $this->getById($id);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM subscriptions WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
?>
