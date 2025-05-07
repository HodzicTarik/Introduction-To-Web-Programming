<?php
require_once __DIR__ . '/../dao/ContactFormsDAO.php';

class ContactFormService {
    private $contactFormDao;

    public function __construct() {
        $this->contactFormDao = new ContactFormsDAO();
    }

    public function getAllContacts() {
        return $this->contactFormDao->getAll();
    }

    public function getContactById($id) {
        return $this->contactFormDao->getById($id);
    }

    public function createContact($data) {
        return $this->contactFormDao->insert($data);
    }

    public function updateContact($id, $data) {
        return $this->contactFormDao->update($id, $data);
    }

    public function deleteContact($id) {
        return $this->contactFormDao->delete($id);
    }
}
?>
