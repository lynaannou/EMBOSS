<?php
// backend/update_contactable.php
header('Content-Type: application/json');
$pdo = require_once 'db.php';
$email = $_POST['email'] ?? '';
$contactable = isset($_POST['contactable']) ? (int)$_POST['contactable'] : null;
if (!$email || $contactable === null) { echo json_encode(['success' => false, 'error' => 'missing']); exit; }
$stmt = $pdo->prepare('UPDATE "User" SET contactable = ? WHERE email = ?');
$ok = $stmt->execute([$contactable, $email]);
echo json_encode(['success' => (bool)$ok]);
