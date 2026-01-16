<?php
require "../includes/db.php";
require "../includes/security.php";

$data = json_decode(file_get_contents("php://input"), true);

$username = strtolower(trim($data["username"]));
$l1 = $data["level1_images"];
$l2 = $data["level2_points"];
$l3 = $data["level3_sequence"];

if (count($l1) !== 3 || count($l2) !== 3 || count($l3) !== 3) {
    http_response_code(400);
    exit("Each level must have 3 inputs");
}

$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$username]);
if ($stmt->fetch()) {
    http_response_code(409);
    exit("User exists");
}

$l1_hash = make_hash(canon_level1($l1));
$l2_hash = make_hash(quantize_points($l2));
$l3_hash = make_hash(canon_level3($l3));

$stmt = $pdo->prepare(
    "INSERT INTO users (username, l1_hash, l2_hash, l3_hash)
     VALUES (?, ?, ?, ?)"
);
$stmt->execute([$username, $l1_hash, $l2_hash, $l3_hash]);

echo "Registered successfully";
