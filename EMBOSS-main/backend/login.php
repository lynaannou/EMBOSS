<?php
// login.php
require_once 'db.php'; // ta connexion PDO

$email = $_POST['user_email'];
$password = $_POST['user_password'];

$sql = "SELECT * FROM public.\"User\" WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    echo json_encode([
        'success' => true,
        'profileImage' => '', // Tu peux mettre une URL si tu stockes ça en base
        'provider' => 'local'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => "Identifiants incorrects."
    ]);
}
?>
