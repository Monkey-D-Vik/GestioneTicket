<?php
require_once 'config.php';
checkAuth();

$message = '';
$ticket = null;

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Recupera il ticket
    $ch = curl_init(API_BASE_URL . "/ticket/" . $id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($statusCode === 200) {
        $ticket = json_decode($response, true);
    } else {
        $error = "Ticket non trovato";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $ticket) {
    $ticketData = [
        "descrizione" => $_POST['descrizione'],
        "statoId" => (int)$_POST['statoId']
    ];

    // Aggiorna il ticket
    $ch = curl_init(API_BASE_URL . "/ticket/" . $ticket['id']);
    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST => "PUT",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => json_encode($ticketData),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json']
    ]);
    
    $response = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($statusCode == 200) {
        header("Location: dashboard.php?msg=ticket_modificato");
        exit();
    } else {
        $error = "Errore durante l'aggiornamento del ticket.";
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica Ticket</title>
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
        <div class="section">
            <?php if ($ticket): ?>
                <h1>Modifica Ticket #<?php echo $ticket['id']; ?></h1>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <textarea name="descrizione" class="form-control" rows="4" 
                                placeholder="Descrizione del problema" required><?php echo $ticket['descrizione']; ?></textarea>
                    </div>

                    <div class="mb-4">
                        <select name="statoId" class="form-control" required>
                            <option value="1" <?php echo $ticket['statoId'] == 1 ? 'selected' : ''; ?>>Aperto</option>
                            <option value="2" <?php echo $ticket['statoId'] == 2 ? 'selected' : ''; ?>>In Lavorazione</option>
                            <option value="3" <?php echo $ticket['statoId'] == 3 ? 'selected' : ''; ?>>Chiuso</option>
                        </select>
                    </div>

                    <div class="button-group">
                        <button type="submit" class="btn btn-primary">Salva Modifiche</button>
                        <button type="button" onclick="window.location.href='dashboard.php'" 
                                class="btn btn-outline-primary">Annulla</button>
                    </div>
                </form>
            <?php else: ?>
                <div class="alert alert-danger">
                    <?php echo isset($error) ? $error : 'Ticket non trovato'; ?>
                </div>
                <div class="button-group">
                    <button onclick="window.location.href='dashboard.php'" 
                            class="btn btn-outline-primary">Torna alla Dashboard</button>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src="./bootstrap-italia/js/bootstrap-italia.bundle.min.js"></script>
</body>
</html> 