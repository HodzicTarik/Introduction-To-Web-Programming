<?php
require_once 'BaseDao.php';

class UsersDAO extends BaseDao {
    public function __construct() {
        parent::__construct('users');
    }

    public function getUserByEmail($email) {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
