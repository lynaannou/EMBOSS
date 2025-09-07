<?php
session_start();
$pdo = require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['user_email'] ?? '';
    $password = $_POST['user_password'] ?? '';

    // Si on vient du formulaire d'inscription
    if (isset($_POST['user_name']) && isset($_POST['user_lastname'])) {
        $name = $_POST['user_name'];
        $lastname = $_POST['user_lastname'];
        $phone = $_POST['user_phone'] ?? '';
        $passwordConfirm = $_POST['user_password_confirm'] ?? '';

        if ($password !== $passwordConfirm) {
            die("⛔ Les mots de passe ne correspondent pas.");
        }

        // Vérifier si l'utilisateur existe déjà
        $check = $pdo->prepare("SELECT * FROM \"User\" WHERE email = ?");

        $check->execute([$email]);
        if ($check->rowCount() > 0) {
            die("⛔ Cet email est déjà utilisé.");
        }

        // Hasher le mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

       $token = bin2hex(random_bytes(16));
$expiration = date("Y-m-d H:i:s", time() + 600); // 10 minutes

// Supprimer tout ancien token du même email
$pdo->prepare("DELETE FROM email_verifications WHERE email = ?")->execute([$email]);

// Enregistrer le token dans email_verifications
$stmt = $pdo->prepare("INSERT INTO email_verifications (email, token, expiration) VALUES (?, ?, ?)");
$stmt->execute([$email, $token, $expiration]);

// Construire le lien de vérification
$verifyLink = "http://localhost:8000/frontend/HTML/verify.html?token=$token" .
    "&name=" . urlencode($name) .
    "&lastname=" . urlencode($lastname) .
    "&phone=" . urlencode($phone) .
    "&email=" . urlencode($email) .
    "&password=" . urlencode($hashedPassword);

// Envoyer l'email
echo json_encode([
  "success" => true,
  "email" => $email,
  "name" => $name,
  "verifyLink" => $verifyLink
]);
exit;

echo "✅ Un lien de vérification vous a été envoyé par email.";
// Vérifie si le champ contactable est transmis et coché
$contactable = isset($_POST['contactable']) ? true : false;

if ($contactable) {
    $stmt = $pdo->prepare("INSERT INTO contact (nom, prenom, email, phone, contactable) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $lastname, $email, $phone, true]);
}

exit;

    }

    // Sinon, on est en mode connexion
    else {
        $stmt = $pdo->prepare("SELECT * FROM \"User\" WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['iduser'];
            $_SESSION['user_name'] = $user['nom'];
            header("Location: ../../frontend/HTML/catalogue.php");
            localStorage.setItem('loggedIn', 'true');
localStorage.setItem('loginProvider', 'google'); // or 'facebook'
localStorage.setItem('loginProvider', 'facebook');
            exit;
        } else {
            die("⛔ Email ou mot de passe incorrect.");
        }
    }
}
?>
