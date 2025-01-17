<?php
require_once 'config.php';
checkAuth();

if (!isset($_GET['id'])) {
    header("Location: ticket_cliente.php");
    exit();
}

$ticketId = (int)$_GET['id'];

// Verifica che il ticket esista e appartenga al cliente
$ch = curl_init(API_BASE_URL . "/ticket/" . $ticketId);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$ticket = json_decode($response, true);
curl_close($ch);

if (!$ticket || $ticket['clienteId'] != $_SESSION['cliente_id'] || $ticket['statoId'] != 1) {
    header("Location: ticket_cliente.php");
    exit();
}

// Elimina il ticket
$ch = curl_init(API_BASE_URL . "/ticket/" . $ticketId);
curl_setopt_array($ch, [
    CURLOPT_CUSTOMREQUEST => "DELETE",
    CURLOPT_RETURNTRANSFER => true
]);

$response = curl_exec($ch);
$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Reindirizza con messaggio appropriato
if ($statusCode == 200) {
    header("Location: ticket_cliente.php?msg=eliminato");
} else {
    header("Location: ticket_cliente.php?error=1");
}
exit();
?> 