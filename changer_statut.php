<?php
include 'config.php';

if (isset($_POST['id'], $_POST['statut'])) {
    $id = intval($_POST['id']);
    $statut = $_POST['statut'];

    // Sécurité : ne changer qu'à un statut autorisé
    $statuts_autorises = ['ouvert', 'en cours', 'résolu'];

    if (in_array($statut, $statuts_autorises)) {
        $stmt = $pdo->prepare("UPDATE tickets SET statut = ? WHERE id = ?");
        $stmt->execute([$statut, $id]);
    }
}

header("Location: index.php");
exit;
