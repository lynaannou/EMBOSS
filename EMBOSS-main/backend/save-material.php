<?php
echo "<pre>";
print_r($_POST);
echo "</pre>";

$pdo = require_once 'db.php';
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$nomMatiere = trim($_POST['material_name'] ?? '');
$colorsRaw = $_POST['colors'] ?? '[]';
$colors = json_decode($colorsRaw, true);

if (empty($nomMatiere)) {
    die("❌ Le nom de la matière est vide.");
}
if (!is_array($colors)) {
    die("❌ Format des couleurs invalide.");
}

// 0. Vérifier si la matière existe déjà
$stmt = $pdo->prepare("SELECT idmatiere FROM matiere WHERE nommatiere = ?");
$stmt->execute([$nomMatiere]);
$idmatiere = $stmt->fetchColumn();

// Si elle n'existe pas, l'insérer
if (!$idmatiere) {
    $stmt = $pdo->prepare("INSERT INTO matiere (nommatiere) VALUES (?) RETURNING idmatiere");
    $stmt->execute([$nomMatiere]);
    $idmatiere = $stmt->fetchColumn();
}

// 2. Gérer les couleurs
foreach ($colors as $hex) {
    $hex = trim($hex, '"');

    // Vérifier si la couleur existe déjà
    $stmt = $pdo->prepare("SELECT idcouleur FROM couleur WHERE nomcouleur = ?");
    $stmt->execute([$hex]);
    $idcouleur = $stmt->fetchColumn();

    // Si non existante, l'insérer
    if (!$idcouleur) {
        $stmt = $pdo->prepare("INSERT INTO couleur (nomcouleur) VALUES (?) RETURNING idcouleur");
        $stmt->execute([$hex]);
        $idcouleur = $stmt->fetchColumn();
    }

    // Vérifier si l'association matière + couleur existe déjà
    $stmt = $pdo->prepare("SELECT 1 FROM association WHERE idmatiere = ? AND idcouleur = ?");
    $stmt->execute([$idmatiere, $idcouleur]);

    if (!$stmt->fetch()) {
        // Créer l'association
        $stmt = $pdo->prepare("INSERT INTO association (idmatiere, idcouleur) VALUES (?, ?)");
        $stmt->execute([$idmatiere, $idcouleur]);
        echo "✔️ Matière associée à la couleur $hex<br>";
    } else {
        echo "⚠️ Association déjà existante pour $hex<br>";
    }
}

echo "✅ Matière et couleurs enregistrées.";
exit;
