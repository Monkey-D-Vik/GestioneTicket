<?php
require_once 'config.php';
startNewSession();

$url = API_BASE_URL . "/utenti/login";

$loginData = [
    "username" => $_POST['username'],
    "password" => $_POST['password']
];

error_log("Login attempt with: " . print_r($loginData, true));

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => json_encode($loginData)
]);

$response = curl_exec($ch);
$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$responseData = json_decode($response, true);

curl_close($ch);

error_log("Login response: " . print_r($responseData, true));
error_log("Status code: " . $statusCode);

if ($statusCode == 200) {
    $_SESSION['username'] = $_POST['username'];
    
    if ($responseData['amministratore'] == 1) {
        $_SESSION['user_type'] = 'amministratore';
        $_SESSION['admin_id'] = $responseData['admin']['id'];
        error_log("Setting admin session: " . print_r($_SESSION, true));
        header("Location: dashboard.php");
    } else {
        $_SESSION['user_type'] = 'cliente';
        $_SESSION['cliente_id'] = $responseData['cliente']['id'];
        error_log("Setting client session: " . print_r($_SESSION, true));
        header("Location: ticket_cliente.php");
    }
    exit();
} else {
    error_log("Login failed with status: " . $statusCode);
    header("Location: index.php?error=1");
    exit();
}
?> 