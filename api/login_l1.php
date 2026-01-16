<?php
require "../includes/db.php";
require "../includes/security.php";
require "../includes/session.php";

$data = json_decode(file_get_contents("php://input"), true);

$username = strtolower(trim($data["username"]));
$l1 = $data["level1_images"];

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user || !verify_hash(canon_level1($l1), $user["l1_hash"])) {
    http_response_code(401);
    exit("Level 1 failed");
}

$_SESSION["user"] = $username;
$_SESSION["l1"] = true;

echo "Level 1 passed";
