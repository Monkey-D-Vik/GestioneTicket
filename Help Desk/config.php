<?php
define('API_BASE_URL', 'http://localhost:8080/api');

// Configurazione della sessione
ini_set('session.cookie_lifetime', 0);  // La sessione scade quando il browser viene chiuso
ini_set('session.gc_maxlifetime', 3600);  // La sessione dura 1 ora
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => false,
    'httponly' => true
]);

// Funzione per verificare se l'utente è autenticato
function checkAuth() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['user_type'])) {
        header("Location: index.php");
        exit();
    }

    // Se è un amministratore, può accedere a tutte le pagine
    if ($_SESSION['user_type'] === 'amministratore') {
        return;
    }

    // Se è un cliente, può accedere solo alle pagine cliente
    if ($_SESSION['user_type'] === 'cliente') {
        $currentPage = basename($_SERVER['PHP_SELF']);
        $allowedPages = ['ticket_cliente.php', 'storico_ticket.php', 'nuovo_ticket_cliente.php', 'elimina_ticket_cliente.php'];
        
        if (!in_array($currentPage, $allowedPages)) {
            header("Location: ticket_cliente.php");
            exit();
        }
    }
}

// Funzione per iniziare una nuova sessione
function startNewSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    session_unset();
    session_destroy();
    session_start();
}
?> 