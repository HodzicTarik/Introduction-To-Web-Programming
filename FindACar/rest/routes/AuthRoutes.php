<?php

Flight::group('/auth', function () {

    Flight::route('POST /register', function () {
        $data = Flight::request()->data->getData();

        $response = Flight::auth_service()->register($data);

        if ($response['success']) {
            Flight::json([
                'message' => 'Registered',
                'data' => $response['data']
            ]);
        } else {
            Flight::halt(400, $response['error']);
        }
    });

    Flight::route('POST /login', function () {
        $data = Flight::request()->data->getData();

        $response = Flight::auth_service()->login($data);

        if ($response['success']) {
            Flight::json([
                'message' => 'Logged in',
                'data' => $response['data']
            ]);
        } else {
            Flight::halt(401, $response['error']);
        }
    });

});
