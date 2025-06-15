<?php 
$host = 'localhost';
$port = '5432';
$dbname = 'emboss';
$user = 'postgres';
$password = 'amira';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected succesfully";
} catch (PDOEXCEPTION $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>