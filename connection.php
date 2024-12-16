<?php
$host = 'mysql-ourkitchen.alwaysdata.net'; // ou '127.0.0.1'
$db = 'ourkitchen_users';
$user = '379708';
$password = 'Cergy.2024'; // ou votre mot de passe si vous en avez un

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error : " . $e->getMessage();
}





