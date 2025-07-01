<?php
$pdo = require_once 'db.php';
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Activer les erreurs PHP (utile pour debug local)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Récupération des champs texte
$productName = $_POST['product_name'] ?? null;
$productDescription = $_POST['product_description'] ?? null;

if (!$productName || !$productDescription) {
    http_response_code(400);
    echo "❌ Champs obligatoires manquants.";
    exit;
}

// Récupération du contenu binaire du fichier image
$imageData = null;
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
    $imageData = file_get_contents($_FILES['photo']['tmp_name']);
    if ($imageData === false) {
        http_response_code(500);
        echo "❌ Impossible de lire l’image.";
        exit;
    }
}

// Insertion en base de données
$sql = "INSERT INTO model (nommodel, texte, photo) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(1, $productName);
$stmt->bindParam(2, $productDescription);
$stmt->bindParam(3, $imageData, $imageData !== null ? PDO::PARAM_LOB : PDO::PARAM_NULL);

if ($stmt->execute()) {
    echo "✅ Produit ajouté avec succès.";
} else {
    echo "❌ Erreur lors de l'enregistrement.";
}
exit;
