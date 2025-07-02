<?php
// cinema-server/api/account.php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit(0); }

try {
    require_once __DIR__ . '/../connection/db.php';
    require_once __DIR__ . '/../controllers/AccountController.php';
    if (!isset($conn)) { throw new Exception("DB connection failed."); }
    $controller = new AccountController($conn);

    if (!isset($_GET['user_id'])) { http_response_code(403); echo json_encode(['message' => 'Forbidden: User ID is required.']); exit(); }
    $userId = (int)$_GET['user_id'];
    
    $action = $_GET['action'] ?? 'getProfile';

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if ($action === 'getBookings') {
            $controller->getBookings($userId);
        } else {
            $controller->getProfile($userId);
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->updateProfile($userId);
    } else { http_response_code(405); echo json_encode(['message' => 'Method Not Allowed']); }

} catch (Throwable $e) {
    error_log("Fatal API Error in account.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['message' => 'A fatal server error occurred.', 'error' => $e->getMessage()]);
}
