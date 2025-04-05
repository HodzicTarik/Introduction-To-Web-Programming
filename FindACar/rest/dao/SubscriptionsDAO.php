<?php
require_once 'BaseDao.php';

class SubscriptionsDAO extends BaseDao {
    public function __construct() {
        parent::__construct('subscriptions');
    }

    public function getActiveSubscriptions() {
        $stmt = $this->connection->query("SELECT * FROM subscriptions WHERE end_date > NOW()");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
