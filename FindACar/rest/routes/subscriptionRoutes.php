<?php
require_once __DIR__ . '/../services/SubscriptionService.php';

Flight::route('GET /api/subscriptions', function() {
    $service = new SubscriptionService();
    Flight::json($service->getAllSubscriptions());
});

Flight::route('GET /api/subscriptions/@id', function($id) {
    $service = new SubscriptionService();
    Flight::json($service->getSubscriptionById($id));
});

Flight::route('POST /api/subscriptions', function() {
    $data = Flight::request()->data->getData();
    $service = new SubscriptionService();
    Flight::json($service->createSubscription($data));
});

Flight::route('PUT /api/subscriptions/@id', function($id) {
    $data = Flight::request()->data->getData();
    $service = new SubscriptionService();
    Flight::json($service->updateSubscription($id, $data));
});

Flight::route('DELETE /api/subscriptions/@id', function($id) {
    $service = new SubscriptionService();
    Flight::json($service->deleteSubscription($id));
});
