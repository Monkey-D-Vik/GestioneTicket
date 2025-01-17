<?php
require_once 'config.php';
checkAuth();

if (isset($_GET['email'])) {
    $email = urlencode($_GET['email']);
    
    $ch = curl_init(API_BASE_URL . "/clienti/" . $email);
    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST => "DELETE",
        CURLOPT_RETURNTRANSFER => true
    ]);
    
    $response = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($statusCode == 204 || $statusCode == 200) {
        header("Location: lista_clienti.php?message=cliente_eliminato");
    } else {
        header("Location: lista_clienti.php?message=errore_eliminazione");
    }
    exit();
}

header("Location: lista_clienti.php");
exit();
?> 