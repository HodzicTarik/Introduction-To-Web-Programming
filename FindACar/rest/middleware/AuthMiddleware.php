<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {

    private function extractToken() {
        $headers = getallheaders();
        
        if (!isset($headers["Authorization"])) {
            Flight::halt(401, "Missing Authorization header.");
        }

        return str_replace("Bearer ", "", $headers["Authorization"]);
    }

    public function verifyToken($token = null){
        if (!$token) {
            $token = $this->extractToken(); // automatski uzmi iz headera ako nije proslijeđen
        }

        try {
            $decoded = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));
        } catch (Exception $e) {
            Flight::halt(401, "Invalid token: " . $e->getMessage());
        }

        // Postavi dekodovanog korisnika u globalni Flight kontekst
        Flight::set('user', $decoded->user);
        Flight::set('jwt_token', $token);

        // ✅ Loguj korisnika kad je token validan
        error_log("✅ Token validan. Korisnik: " . json_encode($decoded->user));

        return true;
    }

    public function authorizeRole($requiredRole) {
        $user = Flight::get('user');

        if ($user === null) {
            error_log("❌ authorizeRole: user not found in Flight context.");
            Flight::halt(401, "Unauthorized: no user in context");
        }

        if ($user->role !== $requiredRole) {
            Flight::halt(403, 'Access denied: insufficient privileges');
        }
    }

    public function authorizeRoles($roles) {
        $user = Flight::get('user');

        if ($user === null) {
            error_log("❌ authorizeRoles: user not found in Flight context.");
            Flight::halt(401, "Unauthorized: no user in context");
        }

        if (!in_array($user->role, $roles)) {
            Flight::halt(403, 'Forbidden: role not allowed');
        }
    }

    public function authorizeAnyRole($allowedRoles) {
        $user = Flight::get('user');
        error_log("🟡 authorizeAnyRole - USER: " . json_encode($user));

        if ($user === null) {
            Flight::halt(401, "Unauthorized: no user found in context.");
        }

        $userRole = (string) $user->role;

        if (!in_array($userRole, $allowedRoles, true)) {
            Flight::halt(403, 'Access denied: insufficient privileges');
        }
    }
}
