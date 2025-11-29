const API_VUELOS = "http://127.0.0.1:8001";
const token = localStorage.getItem("token");

// Función segura para convertir JSON
async function safeJson(res) {
    try { return await res.json(); }
    catch { return {}; }
}


async function loadFlights() {
    const tbody = document.getElementById("flights-table");

    const res = await fetch(`${API_VUELOS}/vuelos/all`);
    const vuelos = await res.json();

    tbody.innerHTML = "";

    vuelos.forEach(v => {
        tbody.innerHTML += `
            <tr>
                <td>${v.id}</td>
                <td>${v.nave_id}</td>
                <td>${v.origin}</td>
                <td>${v.destination}</td>
                <td>${v.departure}</td>
                <td>${v.arrival}</td>
                <td>${v.price}</td>
                <td>
                    <button onclick="crearReserva(${v.id})">Reservar</button>
                </td>
            </tr>
        `;
    });
}

async function crearReserva(flight_id) {

    const res = await fetch(`${API_VUELOS}/reservas/create`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token
        },
        body: JSON.stringify({ flight_id })
    });

    const data = await safeJson(res);

    alert(data.message || data.error);

    loadReservas();
}


async function loadReservas() {
    const tbody = document.getElementById("reservas-table");

    const res = await fetch(`${API_VUELOS}/reservas/mias`, {
        headers: {
            "Authorization": "Bearer " + token
        }
    });

    const reservas = await res.json();
    tbody.innerHTML = "";

    reservas.forEach(r => {
        tbody.innerHTML += `
            <tr>
                <td>${r.id}</td>
                <td>${r.flight_id}</td>
                <td>${r.status}</td>
                <td>${r.reserved_at}</td>
                <td>
                    ${r.status === "activa" 
                        ? `<button onclick="cancelarReserva(${r.id})">Cancelar</button>`
                        : "—"}
                </td>
            </tr>
        `;
    });
}


async function cancelarReserva(id) {
    if (!confirm("¿Deseas cancelar esta reserva?")) return;

    const res = await fetch(`${API_VUELOS}/reservas/cancel/${id}`, {
        method: "PUT",
        headers: {
            "Authorization": "Bearer " + token
        }
    });

    const data = await safeJson(res);

    alert(data.message || data.error);
    loadReservas();
}


document.addEventListener("DOMContentLoaded", () => {
    loadFlights();
    loadReservas();
});
