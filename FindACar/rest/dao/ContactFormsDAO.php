<?php
require_once 'BaseDao.php';

class ContactFormsDAO extends BaseDao {
    public function __construct() {
        parent::__construct('contact_forms');
    }

    public function getUnreadMessages() {
        $stmt = $this->connection->query("SELECT * FROM contact_forms WHERE status = 'unread'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
