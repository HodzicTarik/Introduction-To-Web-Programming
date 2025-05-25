<?php
require_once __DIR__ . '/../dao/SubscriptionsDAO.php';

class SubscriptionService {
    private $subscriptionDao;

    public function __construct() {
        $this->subscriptionDao = new SubscriptionsDAO();
    }

    public function getAllSubscriptions() {
        return $this->subscriptionDao->getAll();
    }

    public function getSubscriptionById($id) {
        return $this->subscriptionDao->getById($id);
    }

    public function createSubscription($data) {
        return $this->subscriptionDao->insert($data); // očekuje 'plan', 'price', 'description', itd.
    }

    public function updateSubscription($id, $data) {
        return $this->subscriptionDao->update($id, $data); // očekuje sve podatke za ažuriranje
    }

    public function deleteSubscription($id) {
        return $this->subscriptionDao->delete($id);
    }

    public function getTemplateSubscriptions() {
    return $this->subscriptionDao->getTemplatesOnly();
}

}
?>
