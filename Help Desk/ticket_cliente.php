<?php
require_once 'config.php';
checkAuth();

if ($_SESSION['user_type'] !== 'cliente') {
    header("Location: index.php");
    exit();
}

$clienteId = $_SESSION['cliente_id'];

// Recupera i ticket del cliente
$ch = curl_init(API_BASE_URL . "/ticket/cliente/" . $clienteId);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$tickets = json_decode($response, true) ?: [];
curl_close($ch);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>I Miei Ticket</title>
    <link rel="stylesheet" href="./bootstrap-italia/css/bootstrap-italia.min.css">
    <link rel="stylesheet" href="./bootstrap-italia/fonts/Titillium_Web/titillium-web.css">
    <link rel="stylesheet" href="./bootstrap-italia/fonts/Roboto_Mono/roboto-mono.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<header class="main-header">
        <div class="nav-container">
            <h3 class="nav-brand">Portale Cliente</h3>
            <ul class="nav-menu">
                <li><a href="ticket_cliente.php">I Miei Ticket</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </header>
    <div class="container">

        <div class="section">
            <div class="header">
                <h1>I Miei Ticket</h1>
                <div class="nav-buttons">
                    <a href="nuovo_ticket_cliente.php" class="btn btn-primary">Nuovo Ticket</a>
                </div>
            </div>

            <?php if (!empty($tickets)): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
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
                                <td><?php echo $ticket['statoNome']; ?></td>
                                <td><?php echo $ticket['dataCreazione']; ?></td>
                                <td><?php echo $ticket['descrizione']; ?></td>
                                <td>
                                    <div class="button-group">
                                        <a href="storico_ticket.php?id=<?php echo $ticket['id']; ?>" 
                                           class="btn btn-outline-primary btn-sm">Dettagli</a>
                                        <?php if ($ticket['statoId'] == 1): ?>
                                            <button onclick="if(confirm('Sei sicuro di voler eliminare questo ticket?')) window.location.href='elimina_ticket_cliente.php?id=<?php echo $ticket['id']; ?>'"
                                                    class="btn btn-danger btn-sm">Elimina</button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center">Nessun ticket trovato.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php include 'footer.php';?>
</body>
</html> 