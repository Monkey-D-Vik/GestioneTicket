<?php
require_once 'config.php';
checkAuth();

$ch = curl_init(API_BASE_URL . "/ticket/all");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['Accept: application/json']
]);

$response = curl_exec($ch);
$tickets = json_decode($response, true) ?: [];
curl_close($ch);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Amministratore</title>
    <link rel="stylesheet" href="./bootstrap-italia/css/bootstrap-italia.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <header class="main-header">
        <div class="nav-container">
            <h3 class="nav-brand">Portale Admin</h3>
            <ul class="nav-menu">
                <li><a href="dashboard.php" class="active">Lista Ticket</a></li>
                <li><a href="lista_clienti.php">Lista Clienti</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </header>

    <div class="container">
        <?php if (isset($_GET['msg'])): ?>
            <?php if ($_GET['msg'] == 'risoluzione_aggiunta'): ?>
                <div class="alert alert-success">
                    Risoluzione aggiunta con successo
                </div>
            <?php elseif ($_GET['msg'] == 'ticket_modificato'): ?>
                <div class="alert alert-success">
                    Ticket modificato con successo
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="section">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Stato</th>
                            <th>Data Creazione</th>
                            <th>Descrizione</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tickets as $ticket): ?>
                            <tr>
                                <td><?php echo $ticket['id']; ?></td>
                                <td><?php echo $ticket['clienteNome']; ?></td>
                                <td><?php echo $ticket['statoNome']; ?></td>
                                <td><?php echo $ticket['dataCreazione']; ?></td>
                                <td><?php echo $ticket['descrizione']; ?></td>
                                <td>
                                    <div class="button-group">
                                        <a href="storico_ticket.php?id=<?php echo $ticket['id']; ?>" 
                                           class="btn btn-outline-primary btn-sm">Storico</a>
                                        <a href="modifica_ticket.php?id=<?php echo $ticket['id']; ?>" 
                                           class="btn btn-primary btn-sm">Modifica</a>
                                        <?php if ($ticket['statoId'] != 3): ?>
                                            <a href="risoluzione_ticket.php?id=<?php echo $ticket['id']; ?>" 
                                               class="btn btn-success btn-sm">Risolvi</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>

    <script src="./bootstrap-italia/js/bootstrap-italia.bundle.min.js"></script>
</body>
</html> 