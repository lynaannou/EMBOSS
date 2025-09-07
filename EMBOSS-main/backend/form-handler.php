<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars($_POST["name"] ?? "");
    $email = htmlspecialchars($_POST["email"] ?? "");
    
    echo "👋 Hello, $name! Your email is $email.";
} else {
    echo "No data submitted!";
}
?>
