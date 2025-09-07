<?php
// Adjust credentials to yours
$host = 'localhost';
$port = '5432';
$db   = 'emboss';        // your database name
$user = 'postgres';      // your db user
$pass = 'amira'; // your db password

$dsn = "pgsql:host=$host;port=$port;dbname=$db;options='--client_encoding=UTF8'";
$pdo = new PDO($dsn, $user, $pass, [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

return $pdo;   // 👈 IMPORTANT: return the PDO instance
