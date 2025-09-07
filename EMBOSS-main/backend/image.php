<?php
require_once 'db.php';
$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("SELECT photo FROM model WHERE idmodel = :id");
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && $row['photo']) {
        $imageData = stream_get_contents($row['photo']); // ✅ fix here
        $info = getimagesizefromstring($imageData);

        if ($info && isset($info['mime'])) {
            header("Content-Type: " . $info['mime']);
        } else {
            header("Content-Type: application/octet-stream");
        }

        echo $imageData;
        exit;
    } else {
        header("Content-Type: text/plain");
        echo "No photo found for ID = $id";
        exit;
    }
}

header("Content-Type: text/plain");
echo "No ID provided";
