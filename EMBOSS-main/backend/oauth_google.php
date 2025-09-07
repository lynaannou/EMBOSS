<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

function fail($msg, $http=400){
  http_response_code($http);
  echo json_encode(['ok'=>false,'error'=>$msg], JSON_UNESCAPED_SLASHES);
  exit;
}

$CLIENT_ID = '570889304064-hrnd34ln9t5hav5ktj43vde6ldbc692n.apps.googleusercontent.com';

// Accept both x-www-form-urlencoded and JSON bodies
$ctype = $_SERVER['CONTENT_TYPE'] ?? '';
$token = '';
if (stripos($ctype, 'application/json') !== false) {
  $in = json_decode(file_get_contents('php://input'), true) ?: [];
  $token = $in['credential'] ?? '';
} else {
  $token = $_POST['credential'] ?? '';
}
if (!$token) fail('missing token');

// Verify ID token with Google
$resp = @file_get_contents('https://oauth2.googleapis.com/tokeninfo?id_token=' . urlencode($token));
if ($resp === false) fail('google verification request failed', 502);
$payload = json_decode($resp, true) ?: [];
if (!is_array($payload)) fail('invalid verification payload', 502);

// Basic checks
if (($payload['aud'] ?? '') !== $CLIENT_ID) fail('audience mismatch', 401);
if (!($payload['email_verified'] ?? false)) fail('email not verified', 401);

// Extract fields
$email   = $payload['email']   ?? '';
$name    = trim((string)($payload['name'] ?? ''));
$picture = $payload['picture'] ?? '';

$parts  = preg_split('/\s+/', $name) ?: [];
$nom    = $parts[0] ?? '';
$prenom = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : '';

try {
  // Load DB from db.php (it must RETURN a PDO)
  $db = require __DIR__ . '/db.php';
  if ($db instanceof PDO) {
    $pdo = $db;
  } elseif (isset($pdo) && $pdo instanceof PDO) {
    // (fallback if db.php defines $pdo without returning it)
  } else {
    fail('db connection not available', 500);
  }

  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Upsert user
  $stmt = $pdo->prepare('SELECT iduser FROM "User" WHERE email = ? LIMIT 1');
  $stmt->execute([$email]);
  $iduser = $stmt->fetchColumn();

  if ($iduser) {
    $upd = $pdo->prepare('UPDATE "User" SET nom = ?, prenom = ? WHERE iduser = ?');
    $upd->execute([$nom, $prenom, $iduser]);
    $created = false;
  } else {
    $randPass = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
    // Do NOT touch phone (your column is BIGINT) – leave it NULL in DB or make it TEXT
    $ins = $pdo->prepare('INSERT INTO "User" (nom, prenom, email, password) VALUES (?, ?, ?, ?)');
    $ins->execute([$nom, $prenom, $email, $randPass]);
    $created = true;
  }

  echo json_encode([
    'ok'      => true,
    'user'    => ['email'=>$email, 'name'=>$name, 'picture'=>$picture],
    'created' => $created
  ], JSON_UNESCAPED_SLASHES);
  exit;

} catch (Throwable $e) {
  // While developing, surface the exact DB error to the browser:
  error_log('[oauth_google] ' . $e->getMessage());
  fail('db error: ' . $e->getMessage(), 500);
}
session_start();
$_SESSION['email'] = $email;
$_SESSION['role']  = (strtolower($email) === 'embossmetalservices@gmail.com') ? 'admin' : 'user';

echo json_encode([
  'ok'      => true,
  'user'    => ['email'=>$email, 'name'=>$name, 'picture'=>$picture],
  'role'    => $_SESSION['role'],            // 👈 include role if you like
  'created' => $created
]);