<?php
header('Content-Type: application/json');
try {
  $db = require __DIR__ . '/db.php';
  $pdo = ($db instanceof PDO) ? $db : (isset($pdo) && $pdo instanceof PDO ? $pdo : null);
  if (!$pdo) throw new Exception('db.php did not return a PDO');

  $one = $pdo->query('SELECT 1')->fetchColumn();
  echo json_encode(['ok'=>true,'db'=>(int)$one]);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['ok'=>false,'error'=>$e->getMessage()]);
}
