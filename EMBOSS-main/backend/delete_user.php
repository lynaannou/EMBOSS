<?php
// backend/delete_user.php
header('Content-Type: application/json');
$pdo = require_once 'db.php';


$email = $_POST['email'] ?? '';
if (!$email) { echo json_encode(['success' => false, 'error' => 'missing email']); exit; }


$stmt = $pdo->prepare('DELETE FROM "User" WHERE email = ?');
$ok = $stmt->execute([$email]);


echo json_encode(['success' => $ok]);