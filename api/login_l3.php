<?php
require "../includes/db.php";
require "../includes/security.php";
require "../includes/session.php";

if (
    !isset($_SESSION["user"]) ||
    !isset($_SESSION["l1"]) ||
    !isset($_SESSION["l2"])
) {
    http_response_code(403);
    exit("Unauthorized flow");
}

$data = json_decode(file_get_contents("php://input"), true);

$points = $data["level3_points"];

if (count($points) !== 3) {
    http_response_code(400);
    exit("Invalid click data");
}

$username = $_SESSION["user"];

$stmt = $pdo->prepare("SELECT l3_hash FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

$secret = quantize_points($points);

if (!$user || !verify_hash($secret, $user["l3_hash"])) {
    http_response_code(401);
    exit("Level 3 failed");
}

$_SESSION["authenticated"] = true;
echo "Login successful";
