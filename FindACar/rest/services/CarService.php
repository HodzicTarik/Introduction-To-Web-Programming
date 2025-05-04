<?php
require_once __DIR__ . '/../dao/CarsDAO.php';

class CarService {
    private $carDao;

    public function __construct() {
        $this->carDao = new CarsDAO();
    }

    public function getAllCars() {
        return $this->carDao->getAll();
    }

    public function getCarById($id) {
        return $this->carDao->getById($id);
    }

    public function createCar($data) {
        return $this->carDao->insert($data);
    }

    public function updateCar($id, $data) {
        return $this->carDao->update($id, $data);
    }

    public function deleteCar($id) {
        return $this->carDao->delete($id);
    }
}
?>
