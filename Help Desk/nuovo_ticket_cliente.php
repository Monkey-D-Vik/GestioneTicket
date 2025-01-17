<?php
require_once 'config.php';
checkAuth();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $clienteId = $_SESSION['cliente_id'];
    $ticketData = [
        "descrizione" => $_POST['descrizione']
    ];

    $ch = curl_init(API_BASE_URL . "/ticket?clienteId=" . $clienteId);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS => json_encode($ticketData)
    ]);

    $response = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($statusCode == 201 || $statusCode == 200) {
        header("Location: ticket_cliente.php");
        exit();
    }
    $error = "Errore nella creazione del ticket";
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuovo Ticket</title>
    <link rel="stylesheet" href="bootstrap-italia/css/bootstrap-italia.min.css">
    <link rel="stylesheet" href="css/style.css">
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
                <h1>Nuovo Ticket</h1>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group mb-4">
                    <textarea name="descrizione" id="descrizione" rows="4" class="form-control" required placeholder="Descrizione"></textarea>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Invia Ticket</button>
                    <button type="button" onclick="window.location.href='ticket_cliente.php'" 
                            class="btn btn-outline-primary">Annulla</button>
                </div>
            </form>
        </div>
    </div>
    <?php include 'footer.php'; ?>

    <script src="bootstrap-italia/js/bootstrap-italia.bundle.min.js"></script>
</body>
</html> 