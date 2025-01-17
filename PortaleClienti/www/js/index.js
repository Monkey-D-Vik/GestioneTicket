/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */

// Wait for the deviceready event before using any of Cordova's device APIs.
// See https://cordova.apache.org/docs/en/latest/cordova/events/events.html#deviceready
document.addEventListener('deviceready', () => {
    console.log('Running cordova-' + cordova.platformId + '@' + cordova.version);
}, false);

const API_URL = 'http://localhost:8080/api';

// Gestione Login
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', handleLogin);
    }

    const newTicketForm = document.getElementById('newTicketForm');
    if (newTicketForm) {
        newTicketForm.addEventListener('submit', handleNewTicket);
    }

    // Verifica se l'utente è già loggato
    const clienteId = localStorage.getItem('clienteId');
    if (!clienteId && !location.pathname.includes('index.html')) {
        window.location.href = 'index.html';
        return;
    }

    // Carica i ticket nella dashboard
    if (location.pathname.includes('dashboard.html')) {
        loadTickets();
    }

    // Carica i dettagli del ticket nella pagina dettagli
    if (location.pathname.includes('dettagli-ticket.html')) {
        const urlParams = new URLSearchParams(window.location.search);
        const ticketId = urlParams.get('id');
        if (ticketId) {
            loadTicketDetails(ticketId);
        }
    }
});

async function handleLogin(e) {
    e.preventDefault();
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    try {
        const response = await fetch(`${API_URL}/utenti/login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                username: username,
                password: password 
            })
        });

        if (response.ok) {
            const data = await response.json();
            if (!data.amministratore && data.cliente && data.cliente.id) {
                localStorage.setItem('clienteId', data.cliente.id);
                window.location.href = 'dashboard.html';
            } else {
                alert('Accesso non autorizzato. Solo i clienti possono accedere a questo portale.');
            }
        } else {
            alert('Credenziali non valide');
        }
    } catch (error) {
        console.error('Errore durante il login:', error);
        alert('Errore durante il login');
    }
}

async function loadTickets() {
    const clienteId = localStorage.getItem('clienteId');
    if (!clienteId) {
        window.location.href = 'index.html';
        return;
    }

    try {
        const response = await fetch(`${API_URL}/ticket/cliente/${clienteId}`);
        const tickets = await response.json();
        
        const ticketList = document.getElementById('ticketList');
        ticketList.innerHTML = `
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Stato</th>
                        <th>Data Creazione</th>
                        <th>Descrizione</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    ${tickets.length ? tickets.map(ticket => `
                        <tr>
                            <td>${ticket.id}</td>
                            <td>${ticket.statoNome}</td>
                            <td>${ticket.dataCreazione}</td>
                            <td>${ticket.descrizione}</td>
                            <td>
                                <div class="button-group">
                                    <button onclick="location.href='dettagli-ticket.html?id=${ticket.id}'" 
                                            class="btn btn-primary btn-sm">
                                        Dettagli
                                    </button>
                                    ${ticket.statoId === 1 ? `
                                        <button onclick="deleteTicket(${ticket.id})" 
                                                class="btn btn-danger btn-sm">
                                            Elimina
                                        </button>
                                    ` : ''}
                                </div>
                            </td>
                        </tr>
                    `).join('') : `
                        <tr>
                            <td colspan="5" class="text-center">Nessun ticket trovato</td>
                        </tr>
                    `}
                </tbody>
            </table>
        `;
    } catch (error) {
        console.error('Errore nel caricamento dei ticket:', error);
        alert('Errore nel caricamento dei ticket');
    }
}

async function handleNewTicket(e) {
    e.preventDefault();
    const clienteId = localStorage.getItem('clienteId');
    if (!clienteId) {
        window.location.href = 'index.html';
        return;
    }

    const descrizione = document.getElementById('descrizione').value;
    
    try {
        // Aggiungiamo il clienteId come parametro URL
        const response = await fetch(`${API_URL}/ticket?clienteId=${clienteId}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                descrizione: descrizione
            })
        });

        if (response.ok) {
            window.location.href = 'dashboard.html';
        } else {
            alert('Errore nella creazione del ticket');
        }
    } catch (error) {
        console.error('Errore nella creazione del ticket:', error);
        alert('Errore nella creazione del ticket');
    }
}

async function loadTicketDetails(ticketId) {
    try {
        // Carica dettagli ticket
        const ticketResponse = await fetch(`${API_URL}/ticket/${ticketId}`);
        const ticket = await ticketResponse.json();

        // Carica aggiornamenti
        const updatesResponse = await fetch(`${API_URL}/aggiornamenti/ticket/${ticketId}`);
        const updates = await updatesResponse.json();

        // Carica risoluzione se presente
        const resolutionResponse = await fetch(`${API_URL}/risoluzioni/ticket/${ticketId}`);
        const resolution = resolutionResponse.ok ? await resolutionResponse.json() : null;

        // Mostra dettagli
        document.getElementById('ticketDetails').innerHTML = `
            <h2>Ticket #${ticket.id}</h2>
            <p>Stato: ${ticket.statoNome}</p>
            <p>Data Creazione: ${ticket.dataCreazione}</p>
            <p>Descrizione: ${ticket.descrizione}</p>
        `;

        // Mostra aggiornamenti
        document.getElementById('ticketUpdates').innerHTML = `
            <h3>Aggiornamenti</h3>
            ${updates.length ? updates.map(update => `
                <div class="update">
                    <p>Data: ${update.dataAggiornamento}</p>
                    <p>Tecnico: ${update.tecnicoNome}</p>
                    <p>Note: ${update.descrizione}</p>
                </div>
            `).join('') : '<p>Nessun aggiornamento</p>'}
        `;

        // Mostra risoluzione se presente
        if (resolution) {
            document.getElementById('ticketResolution').innerHTML = `
                <h3>Risoluzione</h3>
                <p>Data: ${resolution.dataRisoluzione}</p>
                <p>Tecnico: ${resolution.tecnicoNome}</p>
                <p>Note: ${resolution.note}</p>
            `;
        }

        // Mostra pulsante elimina solo se il ticket è aperto
        const deleteBtn = document.getElementById('deleteBtn');
        if (ticket.statoId === 1) { // Stato "Aperto"
            deleteBtn.style.display = 'block';
        }
    } catch (error) {
        console.error('Errore nel caricamento dei dettagli:', error);
        alert('Errore nel caricamento dei dettagli');
    }
}

async function deleteTicket() {
    const urlParams = new URLSearchParams(window.location.search);
    const ticketId = urlParams.get('id');
    
    if (confirm('Sei sicuro di voler eliminare questo ticket?')) {
        try {
            const response = await fetch(`${API_URL}/ticket/${ticketId}`, {
                method: 'DELETE'
            });

            if (response.ok) {
                window.location.href = 'dashboard.html';
            } else {
                alert('Errore nell\'eliminazione del ticket');
            }
        } catch (error) {
            console.error('Errore nell\'eliminazione del ticket:', error);
            alert('Errore nell\'eliminazione del ticket');
        }
    }
}

function logout() {
    localStorage.removeItem('clienteId');
    window.location.href = 'index.html';
}
