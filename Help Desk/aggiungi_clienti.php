<?php
require_once 'config.php';
checkAuth();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $clienteData = [
        "nome" => $_POST['nome'],
        "email" => $_POST['email'],
        "partitaIva" => $_POST['partitaIva'],
        "codiceFiscale" => $_POST['codiceFiscale'],
        "indirizzo" => $_POST['indirizzo'],
        "telefono" => $_POST['telefono']
    ];

    $ch = curl_init(API_BASE_URL . "/clienti");
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS => json_encode($clienteData)
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    switch($httpCode) {
        case 201:
            header("Location: lista_clienti.php?success=1");
            exit();
        case 409:
            $message = "Errore: Email, Codice Fiscale o Telefono già esistente nel sistema.";
            break;
        case 400:
            $message = "Errore: Verifica che tutti i campi siano compilati correttamente.";
            break;
        default:
            $message = "Errore durante l'aggiunta del cliente. Riprova.";
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aggiungi Cliente</title>
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
            <h1>Aggiungi Nuovo Cliente</h1>

            <?php if (isset($message)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group mb-3">
                    
                    <input type="text" class="form-control" name="nome" placeholder="Nome" required>
                </div>

                <div class="form-group mb-3">
                    
                    <input type="email" class="form-control" name="email" placeholder="email"  required>
                </div>

                <div class="form-group mb-3">
                    <input type="text" class="form-control" name="partitaIva" placeholder="Partita IVA" maxlength="11" required>
                </div>

                <div class="form-group mb-3">
                    
                    <input type="text" class="form-control" name="codiceFiscale" placeholder="Codice Fiscale" maxlength="16" required>
                </div>

                <div class="form-group mb-3">
                    
                    <input type="text" class="form-control" name="indirizzo" placeholder="Indirizzo" required>
                </div>

                <div class="form-group mb-4">
                    
                    <input type="tel" class="form-control" name="telefono" placeholder="Telefono" maxlength="10" required>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Aggiungi Cliente</button>
                    <a href="lista_clienti.php" class="btn btn-outline-primary">Torna alla lista clienti</a>
                </div>
            </form>
        </div>
    </div>

    <script src="./bootstrap-italia/js/bootstrap-italia.bundle.min.js"></script>
</body>
</html> 