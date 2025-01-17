<?php
require_once 'config.php';
checkAuth();

if (!isset($_GET['cliente_id']) && !isset($_POST['cliente_id'])) {
    header("Location: lista_clienti.php?error=no_client_id");
    exit();
}

$cliente_id = (int)(isset($_POST['cliente_id']) ? $_POST['cliente_id'] : $_GET['cliente_id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userData = [
        "username" => $_POST['username'],
        "password" => $_POST['password'],
        "amministratore" => false
    ];

    $ch = curl_init(API_BASE_URL . "/utenti/" . $cliente_id);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($userData),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json']
    ]);
    
    $response = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($statusCode == 201) {
        header("Location: lista_clienti.php?message=utente_creato");
        exit();
    } else {
        $error = "Errore durante la creazione dell'utente";
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crea Utente</title>
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
            <h1>Crea Utente per Cliente ID: <?php echo $cliente_id; ?></h1>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="cliente_id" value="<?php echo $cliente_id; ?>">
                
                <div class="form-group mb-3">
                    <input type="text" class="form-control" name="username" placeholder="Username" required>
                </div>

                <div class="form-group mb-4">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Crea Utente</button>
                    <a href="lista_clienti.php" class="btn btn-outline-primary">Torna alla lista clienti</a>
                </div>
            </form>
        </div>
    </div>
    <?php include 'footer.php'; ?>

    <script src="./bootstrap-italia/js/bootstrap-italia.bundle.min.js"></script>
</body>
</html> 