<?php
// config.php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school_success";

try {
    // Création de la connexion avec PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    
    // Définir le mode d'erreur PDO sur Exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Si la connexion est réussie
    // echo "Connexion réussie";
} catch (PDOException $e) {
    // Si la connexion échoue
    die("La connexion a échoué : " . $e->getMessage());
}

