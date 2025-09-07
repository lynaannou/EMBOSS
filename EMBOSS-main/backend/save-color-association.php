<?php
$pdo = require_once 'db.php';

$idmatiere = $_POST['idmatiere'];
$couleur = $_POST['couleur_hex'];

// Vérifie si la couleur existe déjà
$stmt = $pdo->prepare("SELECT idcouleur FROM couleur WHERE nomcouleur = ?");
$stmt->execute([$couleur]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    $idcouleur = $result['idcouleur'];
} else {
    // Ajouter la couleur si elle n'existe pas
    $stmtInsert = $pdo->prepare("INSERT INTO couleur (nomcouleur) VALUES (?) RETURNING idcouleur");
    $stmtInsert->execute([$couleur]);
    $idcouleur = $stmtInsert->fetch(PDO::FETCH_ASSOC)['idcouleur'];
}

// Vérifie si l'association existe déjà
$check = $pdo->prepare("SELECT * FROM association WHERE idmatiere = ? AND idcouleur = ?");
$check->execute([$idmatiere, $idcouleur]);
if (!$check->fetch()) {
    // Crée l'association
    $stmtAssoc = $pdo->prepare("INSERT INTO association (idmatiere, idcouleur) VALUES (?, ?)");
    $stmtAssoc->execute([$idmatiere, $idcouleur]);
    echo "Couleur associée avec succès.";
} else {
    echo "Cette matière a déjà cette couleur.";
}
?>
