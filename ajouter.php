<?php
include 'config.php';

// Vérifie que les champs du formulaire existent et ne sont pas vides
if (isset($_POST['titre'], $_POST['description']) && !empty($_POST['titre']) && !empty($_POST['description'])) {
    $titre = htmlspecialchars($_POST['titre']);
    $description = htmlspecialchars($_POST['description']);

    // Requête SQL préparée pour éviter les injections SQL
    $stmt = $pdo->prepare("INSERT INTO tickets (titre, description) VALUES (?, ?)");
    $stmt->execute([$titre, $description]);

    // Redirection vers la page principale
    header("Location: index.php");
    exit;
} else {
    echo "Erreur : veuillez remplir tous les champs.";
}
?>
