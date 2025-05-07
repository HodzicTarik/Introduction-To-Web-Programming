<?php
require_once __DIR__ . '/../dao/ReservationsDAO.php';

class ReservationService {
    private $reservationDao;

    public function __construct() {
        $this->reservationDao = new ReservationsDAO();
    }

    public function getAllReservations() {
        return $this->reservationDao->getAll();
    }

    public function getReservationById($id) {
        return $this->reservationDao->getById($id);
    }

    public function createReservation($data) {
        return $this->reservationDao->insert($data);
    }

    public function updateReservation($id, $data) {
        return $this->reservationDao->update($id, $data);
    }

    public function deleteReservation($id) {
        return $this->reservationDao->delete($id);
    }
}
?>
