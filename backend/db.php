<?php 
$host = 'localhost';
$port = '5432';
$dbname = 'emboss';
$user = 'postgres';
$password = '24Avril2006/';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo; // ✅ key part
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>
