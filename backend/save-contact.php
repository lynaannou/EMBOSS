<?php
// Toujours renvoyer du JSON
header('Content-Type: application/json');

// Optionnel mais très utile pour le debug (à retirer en production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $pdo = require_once '../../backend/db.php'; // adapte le chemin si besoin

    $prenom = $_POST['first_name'] ?? null;
    $nom = $_POST['last_name'] ?? null;
    $phone = $_POST['phone'] ?? null;
    $email = $_POST['email'] ?? null;
    $sujet = $_POST['sujet'] ?? null;
    $message = $_POST['message'] ?? null;
    $contactable = isset($_POST['contactable']) ? true : false;

    $iduser = null;

    // ✅ Étape 1 : insertion dans la table 'contact'
    $insertContact = $pdo->prepare("
        INSERT INTO contact (iduser, nom, prenom, email, phone, contactable) 
        VALUES (:iduser, :nom, :prenom, :email, :phone, :contactable)
        RETURNING idcontact
    ");

    $insertContact->execute([
        'iduser' => $iduser,
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'phone' => $phone,
        'contactable' => $contactable
    ]);

    $idcontact = $insertContact->fetchColumn();

    // ✅ Étape 2 : insertion dans la table 'message'
    $insertMessage = $pdo->prepare("
        INSERT INTO message (message, idcontact) 
        VALUES (:message, :idcontact)
    ");

    $insertMessage->execute([
        'message' => $message,
        'idcontact' => $idcontact
    ]);

    echo json_encode([
        "status" => "success",
        "message" => "Message enregistré avec succès"
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
