<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $pdo = require_once 'db.php';

    $prenom = $_POST['first_name'] ?? null;
    $nom = $_POST['last_name'] ?? null;
    $phone = $_POST['phone'] ?? null;
    $email = $_POST['email'] ?? null;
    $sujet = $_POST['sujet'] ?? null;
    $message = $_POST['message'] ?? null;
    $contactable = isset($_POST['contactable']) ? true : false;

    $iduser = null;

    // Insertion dans contact
    $stmt = $pdo->prepare("INSERT INTO contact (iduser, nom, prenom, email, phone, contactable)
                           VALUES (:iduser, :nom, :prenom, :email, :phone, :contactable)
                           RETURNING idcontact");
    $stmt->execute([
        'iduser' => $iduser,
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'phone' => $phone,
        'contactable' => $contactable
    ]);

    $idcontact = $stmt->fetchColumn();

    // Insertion du message
    $stmt = $pdo->prepare("INSERT INTO message (message, idcontact)
                           VALUES (:message, :idcontact)");
    $stmt->execute([
        'message' => $message,
        'idcontact' => $idcontact
    ]);

    echo json_encode(["status" => "success"]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
