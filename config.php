<?php
// Infos de connexion à ta base
$host = 'localhost';
$dbname = 'gestion_tickets';
$username = 'root';
$password = 'root'; // Mets le mot de passe si tu en as un

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Affiche une erreur en cas de problème
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
