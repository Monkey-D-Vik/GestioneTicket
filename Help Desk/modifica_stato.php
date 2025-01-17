<?php
require_once 'config.php';
checkAuth();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ticketId = (int)$_POST['ticket_id'];
    $statoId = (int)$_POST['stato_' . $ticketId];

    $data = [
        "statoId" => $statoId
    ];

    $ch = curl_init(API_BASE_URL . "/ticket/" . $ticketId);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "PUT",
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS => json_encode($data)
    ]);

    $response = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($statusCode == 200) {
        header('Location: dashboard.php');
        exit();
    }
}

header('Location: dashboard.php?error=1');
exit(); 