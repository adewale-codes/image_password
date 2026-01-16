<?php
require "../includes/db.php";
require "../includes/security.php";
require "../includes/session.php";

if (!isset($_SESSION["user"]) || !isset($_SESSION["l1"])) {
    http_response_code(403);
    exit("Unauthorized flow");
}

$data = json_decode(file_get_contents("php://input"), true);

$sequence = $data["level2_sequence"];

if (count($sequence) !== 3) {
    http_response_code(400);
    exit("Invalid sequence");
}

$username = $_SESSION["user"];

$stmt = $pdo->prepare("SELECT l2_hash FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user || !verify_hash(canon_level3($sequence), $user["l2_hash"])) {
    http_response_code(401);
    exit("Level 2 failed");
}

$_SESSION["l2"] = true;
echo "Level 2 passed";
