<?php
// backend/update_user.php
header('Content-Type: application/json');
$pdo = require_once 'db.php';


$email = $_POST['email'] ?? '';
$nom = $_POST['nom'] ?? '';
$prenom = $_POST['prenom'] ?? '';
$phone = $_POST['phone'] ?? '';


if (!$email) { echo json_encode(['success' => false, 'error' => 'missing email']); exit; }


$stmt = $pdo->prepare('UPDATE "User" SET nom = ?, prenom = ?, phone = ? WHERE email = ?');
$ok = $stmt->execute([$nom, $prenom, $phone, $email]);


echo json_encode(['success' => $ok]);