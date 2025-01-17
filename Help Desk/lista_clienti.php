<?php
require_once 'config.php';
checkAuth();

$ch = curl_init(API_BASE_URL . '/clienti/all');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['Accept: application/json']
]);

$response = curl_exec($ch);
$clienti = json_decode($response, true) ?: [];
curl_close($ch);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Clienti</title>
    <link rel="stylesheet" href="./bootstrap-italia/css/bootstrap-italia.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <header class="main-header">
        <div class="nav-container">
            <h3 class="nav-brand">Portale Admin</h3>
            <ul class="nav-menu">
                <li><a href="dashboard.php">Lista Ticket</a></li>
                <li><a href="lista_clienti.php" class="active">Lista Clienti</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </header>

    <div class="container">
        <div class="header">
            <h1>Elenco Clienti</h1>
            <div class="nav-buttons">
                <a href="aggiungi_clienti.php" class="btn btn-primary">Aggiungi Cliente</a>
            </div>
        </div>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success" role="alert">
                <?php 
                    switch($_GET['message']) {
                        case 'utente_creato':
                            echo "Utente creato con successo!";
                            break;
                        case 'cliente_aggiunto':
                            echo "Cliente aggiunto con successo!";
                            break;
                    }
                ?>
            </div>
        <?php endif; ?>

        <div class="section">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Nome</th>
                            <th scope="col">Email</th>
                            <th scope="col">Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($clienti)): ?>
                            <?php foreach ($clienti as $cliente): ?>
                                <tr>
                                    <td><?php echo $cliente['nome']; ?></td>
                                    <td><?php echo $cliente['email']; ?></td>
                                    <td>
                                        <div class="button-group">
                                            <a href="dettagli_cliente.php?id=<?php echo $cliente['id']; ?>" 
                                               class="btn btn-outline-primary btn-sm">Dettagli</a>
                                            <a href="collega_utente.php?cliente_id=<?php echo $cliente['id']; ?>" 
                                               class="btn btn-primary btn-sm">Crea Utente</a>
                                            <a href="elimina_cliente.php?email=<?php echo $cliente['email']; ?>" 
                                               onclick="return confirm('Sei sicuro di voler eliminare questo cliente?');"
                                               class="btn btn-danger btn-sm">Elimina</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>

    <script src="./bootstrap-italia/js/bootstrap-italia.bundle.min.js"></script>
</body>
</html> 