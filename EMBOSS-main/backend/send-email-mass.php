<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // si tu as installé PHPMailer via Composer

$pdo = require_once 'db.php';

$subject = $_POST['subject'] ?? '';
$message = $_POST['message'] ?? '';

if (!$subject || !$message) {
    http_response_code(400);
    echo "❌ Sujet ou message manquant.";
    exit;
}

// Récupération des destinataires
$sql = "SELECT email FROM contact WHERE contactable = true";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$emails = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Configuration de PHPMailer
$mail = new PHPMailer(true);
$mail->CharSet = 'UTF-8';
$mail->Encoding = 'base64';
try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'testingemboss@gmail.com'; // Ton email
    $mail->Password   = 'msub ibjp pteu vtgm'; // Ton mot de passe ou mot de passe d'application
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('testingemboss@gmail.com', 'EMBOSS MÉTAL SERVICES');
    $mail->Subject = $subject;
    $mail->Body = $message . "\n\nÀ très bientôt,\nL’équipe EMBOSS MÉTAL SERVICES\n\nLe reflet d’une matière, l’éclat d’une lumière.";


    // Pour chaque destinataire
    foreach ($emails as $email) {
        $mail->addAddress($email);
    }

    $mail->send();
    echo "✅ Email envoyé à tous les contacts.";
} catch (Exception $e) {
    echo "❌ Erreur : {$mail->ErrorInfo}";
}
