# GestioneTicket

Descrizione del Progetto

Il Gestionale Ticket è un sistema completo per la gestione dei ticket di assistenza, composto da due applicazioni mobili basate su Cordova e una web app realizzata in PHP. Il progetto utilizza un backend sviluppato con Spring Boot per gestire la comunicazione con un database MySQL.

Componenti del Sistema

App Cordova per i Clienti

Permette ai clienti di:

Inviare nuovi ticket di assistenza.

Monitorare lo stato e l'andamento dei ticket inviati.

App Cordova per il Gestore dei Ticket

Permette al gestore di:

Visualizzare tutti i ticket aperti.

Aggiornare lo stato dei ticket.

Chiudere i ticket risolti.

Aggiungere nuovi clienti al sistema.

Web App PHP per il Gestore dei Ticket

Offre funzionalità simili all'app mobile del gestore, ma accessibili da un browser web:

Visualizzazione e gestione dei ticket.

Creazione e gestione di nuovi clienti (assegnazione di username e password).

Backend Spring Boot

Gestisce la logica di business e la comunicazione con il database MySQL.

Fornisce API REST per le app Cordova e la web app PHP.

Architettura del Sistema

Frontend:

App Cordova per dispositivi mobili (clienti e gestore).

Web app PHP per il gestore.

Backend:

Microservizi Spring Boot per la gestione delle richieste e connessione al database.

Database:

MySQL per archiviare informazioni su clienti, ticket e utenti.

Caratteristiche Principali

Gestione Ticket:

Creazione, aggiornamento e chiusura dei ticket.

Tracciamento dello stato e dell'andamento dei ticket.

Gestione Utenti:

Creazione e autenticazione di nuovi clienti.

Assegnazione di credenziali di accesso.

Notifiche in tempo reale:

Aggiornamenti sugli stati dei ticket per i clienti.

Requisiti

Frontend

Cordova

Necessario per buildare e deployare le applicazioni mobili.

PHP

Versione 7.x o superiore per la web app.

Backend

Java

Versione 17 o superiore.

Spring Boot

Versione 3.x o superiore.

Database

MySQL

Versione 8.x o superiore.