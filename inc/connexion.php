<?php
$host = 'localhost'; // Le serveur est le même que celui de PhpMyAdmin
$dbname = 'a2425_salami'; // Remplace bien par ton nom exact si différent
$username = 'salami'; // Ton login (exactement comme dans PhpMyAdmin)
$password = '8d5c684' ; // Ton mot de passe (sans le point à la fin)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base : " . $e->getMessage());
}

