<?php
// backend/get_profile.php
header('Content-Type: application/json');
$pdo = require_once 'db.php';


$email = $_POST['email'] ?? '';
if (!$email) { echo json_encode(['success' => false, 'error' => 'missing email']); exit; }


$stmt = $pdo->prepare('SELECT iduser, nom, prenom, phone, email FROM "User" WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);


echo json_encode(['success' => (bool)$user, 'user' => $user]);