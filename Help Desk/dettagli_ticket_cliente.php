<?php
require_once 'config.php';
checkAuth();

if (!isset($_GET['id'])) {
    header("Location: ticket_cliente.php");
    exit();
}

$ticketId = (int)$_GET['id'];

// Recupera dettagli ticket
$ch = curl_init(API_BASE_URL . "/ticket/" . $ticketId);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$ticket = json_decode($response, true);
curl_close($ch);

// Verifica che il ticket appartenga al cliente
if ($ticket['clienteId'] != $_SESSION['cliente_id']) {
    header("Location: ticket_cliente.php");
    exit();
}

// Recupera aggiornamenti
$ch = curl_init(API_BASE_URL . "/aggiornamenti/ticket/" . $ticketId);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$aggiornamenti = json_decode($response, true) ?: [];
curl_close($ch);

// Recupera risoluzione se presente
$ch = curl_init(API_BASE_URL . "/risoluzioni/ticket/" . $ticketId);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$risoluzione = curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200 ? json_decode($response, true) : null;
curl_close($ch);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dettagli Ticket #<?php echo $ticketId; ?></title>
    <link rel="stylesheet" href="./bootstrap-italia/css/bootstrap-italia.min.css">
    <link rel="stylesheet" href="./bootstrap-italia/fonts/Titillium_Web/titillium-web.css">
    <link rel="stylesheet" href="./bootstrap-italia/fonts/Roboto_Mono/roboto-mono.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <div class="container">
        <header class="main-header">
            <div class="nav-container">
                <h3 class="nav-brand">Portale Cliente</h3>
                <ul class="nav-menu">
                    <li><a href="ticket_cliente.php">I Miei Ticket</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </header>

        <div class="header">
            <h1>Dettagli Ticket #<?php echo $ticketId; ?></h1>
        </div>

        <div class="section">
            <h3>Informazioni Ticket</h3>
            <div class="details-grid">
                <p><strong>Stato:</strong> <?php echo $ticket['statoNome']; ?></p>
                <p><strong>Data Creazione:</strong> <?php echo $ticket['dataCreazione']; ?></p>
                <p><strong>Descrizione:</strong> <?php echo $ticket['descrizione']; ?></p>
            </div>
        </div>

        <div class="section">
            <h3>Aggiornamenti</h3>
            <?php if (!empty($aggiornamenti)): ?>
                <div class="updates-list">
                    <?php foreach ($aggiornamenti as $aggiornamento): ?>
                        <div class="update-item">
                            <p><strong>Data:</strong> <?php echo $aggiornamento['dataAggiornamento']; ?></p>
                            <p><strong>Tecnico:</strong> <?php echo $aggiornamento['tecnicoNome']; ?></p>
                            <p><strong>Note:</strong> <?php echo $aggiornamento['descrizione']; ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Nessun aggiornamento disponibile</p>
            <?php endif; ?>
        </div>

        <div class="button-group">
            <button onclick="window.location.href='ticket_cliente.php'" 
                    class="btn btn-outline-primary">Torna alla Lista</button>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html> 