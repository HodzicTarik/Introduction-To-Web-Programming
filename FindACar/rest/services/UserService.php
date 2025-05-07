<?php
require_once __DIR__ . '/../dao/UsersDAO.php';

class UserService {
    private $userDao;

    public function __construct() {
        $this->userDao = new UsersDAO();
    }

    public function getAllUsers() {
        return $this->userDao->getAll();
    }

    public function getUserById($id) {
        return $this->userDao->getById($id);
    }

    public function createUser($data) {
        return $this->userDao->insert($data);
    }

    public function updateUser($id, $data) {
        return $this->userDao->update($id, $data);
    }

    public function deleteUser($id) {
        return $this->userDao->delete($id);
    }
}
?>
