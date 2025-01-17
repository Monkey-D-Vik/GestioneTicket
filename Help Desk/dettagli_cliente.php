<?php
require_once 'config.php';
checkAuth();

$clienteId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$error = '';
$cliente = null;
$tickets = [];

if ($clienteId) {
    // Recupera i dettagli del cliente usando l'ID
    $ch = curl_init(API_BASE_URL . "/clienti/" . $clienteId);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($statusCode === 200) {
        $cliente = json_decode($response, true);
        
        // Recupera i ticket del cliente
        $ch = curl_init(API_BASE_URL . "/ticket/cliente/" . $clienteId);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        
        $tickets = json_decode($response, true) ?: [];
    } else {
        $error = "Cliente non trovato";
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dettagli Cliente</title>
    <link rel="stylesheet" href="./bootstrap-italia/css/bootstrap-italia.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <header class="main-header">
        <div class="nav-container">
            <h3 class="nav-brand">Portale Admin</h3>
            <ul class="nav-menu">
                <li><a href="dashboard.php">Lista Ticket</a></li>
                <li><a href="lista_clienti.php">Lista Clienti</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </header>

    <div class="container">
        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php elseif ($cliente): ?>
            <div class="section">
                <h2>Informazioni Cliente</h2>
                <div class="details-grid">
                    <p><strong>Nome:</strong> <?php echo $cliente['nome']; ?></p>
                    <p><strong>Email:</strong> <?php echo $cliente['email']; ?></p>
                    <p><strong>Partita IVA:</strong> <?php echo $cliente['partitaIva']; ?></p>
                    <p><strong>Codice Fiscale:</strong> <?php echo $cliente['codiceFiscale']; ?></p>
                    <p><strong>Indirizzo:</strong> <?php echo $cliente['indirizzo']; ?></p>
                    <p><strong>Telefono:</strong> <?php echo $cliente['telefono']; ?></p>
                </div>
            </div>

            <div class="section">
                <h2>Ticket del Cliente</h2>
                <?php if (!empty($tickets)): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Descrizione</th>
                                    <th>Stato</th>
                                    <th>Data Creazione</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tickets as $ticket): ?>
                                    <tr>
                                        <td><?php echo $ticket['id']; ?></td>
                                        <td><?php echo $ticket['descrizione']; ?></td>
                                        <td><?php echo $ticket['statoNome']; ?></td>
                                        <td><?php echo $ticket['dataCreazione']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p>Nessun ticket trovato per questo cliente.</p>
                <?php endif; ?>
            </div>

            <div class="button-group">
                <a href="lista_clienti.php" class="btn btn-outline-primary">Torna alla Lista Clienti</a>
            </div>
        <?php endif; ?>
    </div>
    <?php include 'footer.php'; ?>

    <script src="./bootstrap-italia/js/bootstrap-italia.bundle.min.js"></script>
</body>
</html> 