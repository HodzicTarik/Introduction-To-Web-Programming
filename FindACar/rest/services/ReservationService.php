<?php
require_once __DIR__ . '/../dao/ReservationsDAO.php';
require_once __DIR__ . '/../dao/CarsDAO.php'; 

class ReservationService {
    private $reservationDao;
    private $carDao; // Nova DAO instanca

    public function __construct() {
        $this->reservationDao = new ReservationsDAO();
        $this->carDao = new CarsDAO(); // Inicijalizuj
    }

    public function getAllReservations() {
        return $this->reservationDao->getAll();
    }

    public function getReservationById($id) {
        return $this->reservationDao->getById($id);
    }

public function createReservation($data) {
    error_log("🟡 createReservation pozvana!");
    error_log("📦 Primljeni podaci: " . json_encode($data));

    try {
        // 🔁 Mapiranje frontend polja u DB nazive
        $data['start_date'] = $data['date_from'] ?? $data['start_date'];
        $data['end_date'] = $data['date_to'] ?? $data['end_date'];

        // ✅ Validacija
        if (!isset($data['user_id']) || !isset($data['car_id']) || !isset($data['start_date']) || !isset($data['end_date']) || !isset($data['total_price'])) {
            throw new Exception("Nedostaju potrebni podaci za rezervaciju.");
        }

        // ✅ Insert u bazu
        $result = $this->reservationDao->insert($data);

        // ✅ Postavi auto kao unavailable
        if ($result && isset($data['car_id'])) {
            $this->carDao->set_availability($data['car_id'], 0);
            error_log("✅ Auto postavljen kao unavailable: " . $data['car_id']);
        }

        return $result;

    } catch (Exception $e) {
        error_log("❌ Greška u ReservationService: " . $e->getMessage());
        Flight::halt(500, "Greška prilikom rezervacije: " . $e->getMessage());
    }
}





    public function updateReservation($id, $data) {
        return $this->reservationDao->update($id, $data);
    }

    public function deleteReservation($id) {
        return $this->reservationDao->delete($id);
    }
}
?>
