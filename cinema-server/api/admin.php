<?php
// cinema-server/api/admin.php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit(0); }

require_once __DIR__ . '/../connection/db.php';
require_once __DIR__ . '/../controllers/AdminController.php';
require_once __DIR__ . '/../controllers/MovieController.php';
require_once __DIR__ . '/../controllers/ShowtimeController.php';

function verifyAdmin(mysqli $db, int $userId): bool {
    $stmt = $db->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $result && $result['role'] === 'admin';
}

if (!isset($_GET['user_id'])) { http_response_code(401); echo json_encode(['message' => 'Authentication required.']); exit(); }
if (!verifyAdmin($conn, (int)$_GET['user_id'])) { http_response_code(403); echo json_encode(['message' => 'Permission Denied.']); exit(); }

$adminController = new AdminController($conn);
$movieController = new MovieController($conn);
$showtimeController = new ShowtimeController($conn);

$action = $_GET['action'] ?? '';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        switch ($action) {
            case 'getMovies': $movieController->nowPlaying(); break;
            case 'getHalls': $adminController->getHalls(); break;
            case 'getShowtimes': $showtimeController->handleGetRequest(); break;
            default: http_response_code(400); echo json_encode(['message' => 'Invalid GET action.']); break;
        }
        break;
    case 'POST':
        switch ($action) {
            case 'addMovie': $adminController->addMovie(); break;
            case 'updateMovie': $adminController->updateMovie(); break;
            case 'addShowtime': $adminController->addShowtime(); break;
            case 'updateShowtime': $adminController->updateShowtime(); break;
            default: http_response_code(400); echo json_encode(['message' => 'Invalid POST action.']); break;
        }
        break;
    case 'DELETE':
        switch ($action) {
            case 'deleteMovie': $adminController->deleteMovie((int)$_GET['movie_id']); break;
            case 'deleteShowtime': $adminController->deleteShowtime((int)$_GET['showtime_id']); break;
            default: http_response_code(400); echo json_encode(['message' => 'Invalid DELETE action.']); break;
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}
