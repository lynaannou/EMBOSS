<?php
$pdo = require_once 'db.php';

$token = $_POST['token'] ?? '';
$email = $_POST['email'] ?? '';
$name = $_POST['name'] ?? '';
$lastname = $_POST['lastname'] ?? '';
$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';

// Vérifie que le token est encore valide
$stmt = $pdo->prepare("SELECT * FROM email_verifications WHERE token = ? AND expiration > NOW()");
$stmt->execute([$token]);
$entry = $stmt->fetch();

if ($entry && $entry['email'] === $email) {
    // Créer l’utilisateur
    $stmt = $pdo->prepare("INSERT INTO \"User\" (nom, prenom, phone, email, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $lastname, $phone, $email, $password]);

    // Supprimer le token
    $pdo->prepare("DELETE FROM email_verifications WHERE token = ?")->execute([$token]);

    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}
?>
