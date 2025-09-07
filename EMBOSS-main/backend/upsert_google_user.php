<?php
// backend/upsert_google_user.php
header('Content-Type: application/json');
$pdo = require_once 'db.php';


$email = $_POST['email'] ?? '';
$name = $_POST['name'] ?? '';
$photo = $_POST['photo'] ?? '';
if (!$email) { echo json_encode(['success' => false, 'error' => 'missing email']); exit; }


// Split full name into nom/prenom best-effort
$parts = preg_split('/\s+/', trim($name));
$nom = $parts[0] ?? '';
$prenom = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : '';


// Does user exist?
$check = $pdo->prepare('SELECT iduser FROM "User" WHERE email = ? LIMIT 1');
$check->execute([$email]);
$found = $check->fetch(PDO::FETCH_ASSOC);


if ($found) { echo json_encode(['success' => true, 'created' => false]); exit; }


// Insert a placeholder password (random) to satisfy NOT NULL if present
$randPass = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
$phone = '';


$ins = $pdo->prepare('INSERT INTO "User" (nom, prenom, phone, email, password) VALUES (?, ?, ?, ?, ?)');
$ok = $ins->execute([$nom, $prenom, $phone, $email, $randPass]);


echo json_encode(['success' => $ok, 'created' => $ok]);