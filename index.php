<?php include 'config.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Tickets</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Gestionnaire de Tickets</h1>

    <!-- Formulaire de création de ticket -->
    <form action="ajouter.php" method="POST">
        <label for="titre">Titre :</label>
        <input type="text" name="titre" id="titre" required>

        <label for="description">Description :</label>
        <textarea name="description" id="description" required></textarea>

        <button type="submit">Créer le ticket</button>
    </form>

    <hr>

    <h2>Liste des tickets</h2>

<!-- Filtres de statut -->
<form method="GET" style="margin-bottom: 20px;">
    <label for="filtre">Filtrer par statut :</label>
    <select name="filtre" id="filtre" onchange="this.form.submit()">
        <option value="">-- Tous --</option>
        <option value="ouvert" <?php if(isset($_GET['filtre']) && $_GET['filtre'] == 'ouvert') echo 'selected'; ?>>Ouvert</option>
        <option value="en cours" <?php if(isset($_GET['filtre']) && $_GET['filtre'] == 'en cours') echo 'selected'; ?>>En cours</option>
        <option value="résolu" <?php if(isset($_GET['filtre']) && $_GET['filtre'] == 'résolu') echo 'selected'; ?>>Résolu</option>
    </select>
</form>


    <?php
    // On récupère les tickets depuis la base
    $filtre = isset($_GET['filtre']) ? $_GET['filtre'] : '';
    $statuts_autorises = ['ouvert', 'en cours', 'résolu'];
    
    if (in_array($filtre, $statuts_autorises)) {
        $stmt = $pdo->prepare("SELECT * FROM tickets WHERE statut = ? ORDER BY date_creation DESC");
        $stmt->execute([$filtre]);
    } else {
        $stmt = $pdo->query("SELECT * FROM tickets ORDER BY date_creation DESC");
    }    $tickets = $stmt->fetchAll();

    if (count($tickets) > 0) {
        foreach ($tickets as $ticket) {
            echo "<div class='ticket'>";
            echo "<h3>" . htmlspecialchars($ticket['titre']) . "</h3>";
            echo "<p><strong>Statut :</strong> " . $ticket['statut'] . "</p>";
            echo "<p>" . nl2br(htmlspecialchars($ticket['description'])) . "</p>";
            echo "<p><em>Créé le : " . $ticket['date_creation'] . "</em></p>";
        
            // Bouton de changement de statut
            if ($ticket['statut'] == 'ouvert') {
                echo "<form method='POST' action='changer_statut.php'>
                        <input type='hidden' name='id' value='{$ticket['id']}'>
                        <input type='hidden' name='statut' value='en cours'>
                        <button type='submit'>Passer en cours</button>
                      </form>";
            } elseif ($ticket['statut'] == 'en cours') {
                echo "<form method='POST' action='changer_statut.php'>
                        <input type='hidden' name='id' value='{$ticket['id']}'>
                        <input type='hidden' name='statut' value='résolu'>
                        <button type='submit'>Passer en résolu</button>
                      </form>";
            }
        
            echo "</div><hr>";
        }
        
    } else {
        echo "<p>Aucun ticket pour le moment.</p>";
    }
    ?>
</body>
</html>
