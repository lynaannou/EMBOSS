<?php
// backend/ensure_user.php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);

try {
  $pdo = require_once 'db.php';
  if (!$pdo) throw new Exception('db.php did not return PDO');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Accept form or JSON
  $ctype = $_SERVER['CONTENT_TYPE'] ?? '';
  if (stripos($ctype, 'application/json') !== false) {
    $in = json_decode(file_get_contents('php://input'), true) ?: [];
  } else {
    $in = $_POST;
  }

  $email = trim($in['email'] ?? '');
  $name  = trim($in['name']  ?? '');
  $phone = trim($in['phone'] ?? '');

  if ($email === '') { http_response_code(400); echo json_encode(['success'=>false,'error'=>'missing email']); exit; }

  // already exists?
  $check = $pdo->prepare('SELECT iduser FROM "User" WHERE email = ? LIMIT 1');
  $check->execute([$email]);
  if ($check->fetch(PDO::FETCH_ASSOC)) { echo json_encode(['success'=>true,'created'=>false]); exit; }

  // split name -> nom / prenom
  $nom = ''; $prenom = '';
  if ($name !== '') {
    $parts = preg_split('/\s+/', $name);
    $nom = $parts[0] ?? '';
    if (count($parts) > 1) { $prenom = implode(' ', array_slice($parts, 1)); }
  }

  // find which columns exist (phone vs telephone, etc.)
  $colsStmt = $pdo->prepare("
    SELECT lower(column_name) AS c
    FROM information_schema.columns
    WHERE table_schema = 'public' AND lower(table_name) = lower('User')
  ");
  $colsStmt->execute();
  $cols = array_map(fn($r) => $r['c'], $colsStmt->fetchAll(PDO::FETCH_ASSOC));
  $has = fn($c) => in_array(strtolower($c), $cols, true);

  $fields = ['email'];
  $values = ['?'];
  $params = [$email];

  if ($has('nom'))    { $fields[] = 'nom';    $values[] = '?'; $params[] = $nom; }
  if ($has('prenom')) { $fields[] = 'prenom'; $values[] = '?'; $params[] = $prenom; }
  if ($has('phone'))      { $fields[] = 'phone';      $values[] = '?'; $params[] = $phone; }
  elseif ($has('telephone')) { $fields[] = 'telephone'; $values[] = '?'; $params[] = $phone; }
  if ($has('contactable')) { $fields[] = 'contactable'; $values[] = '?'; $params[] = 1; }
  if ($has('password')) {
    $fields[] = 'password'; $values[] = '?';
    $params[] = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
  }

  // minimal safety: if only email exists, still insert email
  $sql = 'INSERT INTO "User" ('.implode(',',$fields).') VALUES ('.implode(',',$values).')';
  $ins = $pdo->prepare($sql);
  $ok = $ins->execute($params);

  echo json_encode(['success'=> (bool)$ok, 'created'=> (bool)$ok]);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['success'=>false,'error'=>$e->getMessage()]);
}
