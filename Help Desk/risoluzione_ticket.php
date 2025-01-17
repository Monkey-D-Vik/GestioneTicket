<?php
require_once 'config.php';
checkAuth();

// Verifica che sia un amministratore
if ($_SESSION['user_type'] !== 'amministratore') {
    header("Location: ticket_cliente.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$ticketId = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['admin_id'])) {
        $error = "Errore: ID amministratore non trovato";
    } else {
        // Prima aggiungiamo la risoluzione
        $risoluzioneData = [
            "note" => $_POST['note']
        ];

        $url = API_BASE_URL . "/risoluzioni/ticket/" . $ticketId . "?tecnicoId=" . $_SESSION['admin_id'];
        
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode($risoluzioneData)
        ]);

        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($statusCode == 201 || $statusCode == 200) {
            // Dopo aver aggiunto la risoluzione, aggiorniamo lo stato del ticket a "Chiuso" (3)
            $ticketData = [
                "statoId" => 3
            ];

            $ch = curl_init(API_BASE_URL . "/ticket/" . $ticketId);
            curl_setopt_array($ch, [
                CURLOPT_CUSTOMREQUEST => "PUT",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
                CURLOPT_POSTFIELDS => json_encode($ticketData)
            ]);

            $response = curl_exec($ch);
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($statusCode == 200) {
                header("Location: dashboard.php?msg=risoluzione_aggiunta");
                exit();
            }
        }
        $error = "Errore nell'aggiunta della risoluzione";
    }
}

// Recupera i dettagli del ticket
$ch = curl_init(API_BASE_URL . "/ticket/" . $ticketId);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$ticket = json_decode($response, true);
curl_close($ch);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risoluzione Ticket #<?php echo $ticketId; ?></title>
    <link rel="stylesheet" href="./bootstrap-italia/css/bootstrap-italia.min.css">
    <link rel="stylesheet" href="./bootstrap-italia/fonts/Titillium_Web/titillium-web.css">
    <link rel="stylesheet" href="./bootstrap-italia/fonts/Roboto_Mono/roboto-mono.css">
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
        <div class="header">
            <h1>Risoluzione Ticket #<?php echo $ticketId; ?></h1>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="section">
            <h3>Dettagli Ticket</h3>
            <div class="details-grid">
                <p><strong>Cliente:</strong> <?php echo $ticket['clienteNome']; ?></p>
                <p><strong>Stato:</strong> <?php echo $ticket['statoNome']; ?></p>
                <p><strong>Descrizione:</strong> <?php echo $ticket['descrizione']; ?></p>
            </div>
        </div>

        <div class="section">
            <form method="POST">
                <div class="form-group">
                    <textarea name="note" id="note" rows="4" class="form-control" placeholder="Note" required></textarea>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Aggiungi Risoluzione</button>
                    <button type="button" onclick="window.location.href='dashboard.php'" 
                            class="btn btn-outline-primary">Annulla</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html> 