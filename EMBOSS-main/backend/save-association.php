<?php
$pdo = require_once 'db.php';
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Affichage debug
echo "<pre>";
print_r($_POST);
echo "</pre>";

// Récupération des données du formulaire
$selectedMatiereIds = $_POST['matieres'] ?? [];
$colorsRaw = $_POST['colors'] ?? '[]';
$colorHexArray = json_decode($colorsRaw, true);

// Vérifications
if (!is_array($colorHexArray)) {
    die("❌ Format des couleurs invalide.");
}

if (empty($selectedMatiereIds) || empty($colorHexArray)) {
    die("❌ Aucune matière ou couleur reçue.");
}

// Boucle sur chaque couleur
foreach ($colorHexArray as $hex) {
    $hex = trim($hex, '"');

    // Vérifie si la couleur existe
    $stmt = $pdo->prepare("SELECT idcouleur FROM couleur WHERE nomcouleur = ?");
    $stmt->execute([$hex]);
    $idcouleur = $stmt->fetchColumn();

    // Sinon, insérer la couleur
    if (!$idcouleur) {
        $stmt = $pdo->prepare("INSERT INTO couleur (nomcouleur) VALUES (?) RETURNING idcouleur");
        $stmt->execute([$hex]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $idcouleur = $result['idcouleur'] ?? null;
    }

    // Pour chaque matière sélectionnée, créer l'association
    if ($idcouleur) {
        foreach ($selectedMatiereIds as $idmatiere) {
            $stmt = $pdo->prepare("INSERT INTO association (idmatiere, idcouleur) VALUES (?, ?)");
            $stmt->execute([$idmatiere, $idcouleur]);
            echo "✔️ Association ajoutée : Matière $idmatiere - Couleur $idcouleur<br>";
        }
    }
}

exit;
