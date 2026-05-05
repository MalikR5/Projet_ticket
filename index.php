<?php include 'config.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Tickets</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="header-content">
                <h1>🎫 Gestionnaire de Tickets</h1>
                <p class="subtitle">Système de suivi et gestion des demandes</p>
            </div>
        </header>

        <!-- Formulaire de création de ticket -->
        <section class="card create-ticket">
            <h2>➕ Nouveau Ticket</h2>
            <form action="ajouter.php" method="POST">
                <div class="form-group">
                    <label for="titre">Titre du ticket</label>
                    <input type="text" name="titre" id="titre" placeholder="Ex: Problème de connexion..." required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" placeholder="Décrivez votre problème en détail..." required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Créer le ticket</button>
            </form>
        </section>

        <section class="tickets-section">
            <div class="section-header">
                <h2>📋 Liste des Tickets</h2>
                
                <!-- Filtres de statut -->
                <form method="GET" class="filter-form">
                    <label for="filtre">Filtrer :</label>
                    <select name="filtre" id="filtre" onchange="this.form.submit()">
                        <option value="">Tous les tickets</option>
                        <option value="ouvert" <?php if(isset($_GET['filtre']) && $_GET['filtre'] == 'ouvert') echo 'selected'; ?>>🟢 Ouverts</option>
                        <option value="en cours" <?php if(isset($_GET['filtre']) && $_GET['filtre'] == 'en cours') echo 'selected'; ?>>🟡 En cours</option>
                        <option value="résolu" <?php if(isset($_GET['filtre']) && $_GET['filtre'] == 'résolu') echo 'selected'; ?>>✅ Résolus</option>
                    </select>
                </form>
            </div>

            <div class="tickets-grid">
                <?php
                // On récupère les tickets depuis la base
                $filtre = isset($_GET['filtre']) ? $_GET['filtre'] : '';
                $statuts_autorises = ['ouvert', 'en cours', 'résolu'];
                
                if (in_array($filtre, $statuts_autorises)) {
                    $stmt = $pdo->prepare("SELECT * FROM tickets WHERE statut = ? ORDER BY date_creation DESC");
                    $stmt->execute([$filtre]);
                } else {
                    $stmt = $pdo->query("SELECT * FROM tickets ORDER BY date_creation DESC");
                }
                $tickets = $stmt->fetchAll();

                if (count($tickets) > 0) {
                    foreach ($tickets as $ticket) {
                        $statutClass = 'status-' . str_replace(' ', '-', $ticket['statut']);
                        $statutEmoji = $ticket['statut'] == 'ouvert' ? '🟢' : ($ticket['statut'] == 'en cours' ? '🟡' : '✅');
                        ?>
                        <div class="ticket-card <?php echo $statutClass; ?>">
                            <div class="ticket-header">
                                <h3><?php echo htmlspecialchars($ticket['titre']); ?></h3>
                                <span class="badge <?php echo $statutClass; ?>">
                                    <?php echo $statutEmoji . ' ' . ucfirst($ticket['statut']); ?>
                                </span>
                            </div>
                            
                            <div class="ticket-body">
                                <p><?php echo nl2br(htmlspecialchars($ticket['description'])); ?></p>
                            </div>
                            
                            <div class="ticket-footer">
                                <span class="date">📅 Créé le <?php echo date('d/m/Y à H:i', strtotime($ticket['date_creation'])); ?></span>
                                
                                <div class="actions">
                                    <?php if ($ticket['statut'] == 'ouvert'): ?>
                                        <form method='POST' action='changer_statut.php' class="inline-form">
                                            <input type='hidden' name='id' value='<?php echo $ticket['id']; ?>'>
                                            <input type='hidden' name='statut' value='en cours'>
                                            <button type='submit' class='btn btn-secondary btn-sm'>▶️ En cours</button>
                                        </form>
                                    <?php elseif ($ticket['statut'] == 'en cours'): ?>
                                        <form method='POST' action='changer_statut.php' class="inline-form">
                                            <input type='hidden' name='id' value='<?php echo $ticket['id']; ?>'>
                                            <input type='hidden' name='statut' value='résolu'>
                                            <button type='submit' class='btn btn-success btn-sm'>✅ Résoudre</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="empty-state">
                        <div class="empty-icon">📭</div>
                        <h3>Aucun ticket trouvé</h3>
                        <p>
                            <?php echo $filtre ? "Aucun ticket avec le statut '" . htmlspecialchars($filtre) . "'." : "Créez votre premier ticket ci-dessus !"; ?>
                        </p>
                    </div>
                    <?php
                }
                ?>
            </div>
        </section>
    </div>
</body>
</html>
