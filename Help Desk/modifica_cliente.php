<?php
require_once 'config.php';
checkAuth();

$message = '';
$cliente = null;

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $ch = curl_init(API_BASE_URL . "/clienti/" . $id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $cliente = json_decode($response, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $clienteData = [
        "id" => (int)$_POST['id'],
        "nome" => $_POST['nome'],
        "email" => $_POST['email'],
        "partitaIva" => $_POST['partitaIva'],
        "codiceFiscale" => $_POST['codiceFiscale'],
        "indirizzo" => $_POST['indirizzo'],
        "telefono" => $_POST['telefono']
    ];

    $ch = curl_init(API_BASE_URL . "/clienti/" . $clienteData['id']);
    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST => "PUT",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => json_encode($clienteData),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json']
    ]);
    
    $response = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($statusCode == 200) {
        header("Location: lista_clienti.php?message=cliente_aggiornato");
        exit();
    } else {
        $message = "Errore durante l'aggiornamento del cliente.";
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Modifica Cliente</title>
</head>
<body>
    <h1>Modifica Cliente</h1>

    <?php if ($message): ?>
        <p style="color: red;"><?php echo $message; ?></p>
    <?php endif; ?>

    <?php if ($cliente): ?>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $cliente['id']; ?>">
            
            <div>
                <label for="nome">Nome:</label>
                <input type="text" name="nome" value="<?php echo $cliente['nome']; ?>" required>
            </div>
            <br>
            <div>
                <label for="email">Email:</label>
                <input type="email" name="email" value="<?php echo $cliente['email']; ?>" required>
            </div>
            <br>
            <div>
                <label for="partitaIva">Partita IVA:</label>
                <input type="text" name="partitaIva" value="<?php echo $cliente['partitaIva']; ?>" required>
            </div>
            <br>
            <div>
                <label for="codiceFiscale">Codice Fiscale:</label>
                <input type="text" name="codiceFiscale" value="<?php echo $cliente['codiceFiscale']; ?>" required>
            </div>
            <br>
            <div>
                <label for="indirizzo">Indirizzo:</label>
                <input type="text" name="indirizzo" value="<?php echo $cliente['indirizzo']; ?>" required>
            </div>
            <br>
            <div>
                <label for="telefono">Telefono:</label>
                <input type="tel" name="telefono" value="<?php echo $cliente['telefono']; ?>" required>
            </div>
            <br>
            <button type="submit">Aggiorna Cliente</button>
        </form>
    <?php else: ?>
        <p>Cliente non trovato.</p>
    <?php endif; ?>

    <br>
    <a href="lista_clienti.php">Torna alla lista clienti</a>
    <?php include 'footer.php'; ?>
</body>
</html> 