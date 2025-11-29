const API_VUELOS = "http://127.0.0.1:8001/vuelos";


function logout() {
  localStorage.removeItem("token");
  window.location.href = "index.html";
}


function showCreateForm() {
  document.getElementById("create-box").style.display = "block";
}


async function createFlight() {
    const body = {
        nave_id: document.getElementById("nave-id").value,
        origin: document.getElementById("origin").value,
        destination: document.getElementById("destination").value,
        departure: document.getElementById("departure").value,
        arrival: document.getElementById("arrival").value,
        price: document.getElementById("price").value,
    };
    const res = await fetch(API_VUELOS + "/create", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(body)
    });

    let data = {};
    try { data = await res.json(); } catch (e) {}

    if (!res.ok) {
        alert(data.error || "Error al registrar vuelo");
        return;
    }

    alert("Vuelo registrado ✔");

    document.getElementById("create-box").style.display = "none";

    loadFlights();
}


async function safeJson(response) {
  try {
    return await response.json();
  } catch (e) {
    return {};
  }
}


async function loadFlights() {
  const tbody = document.getElementById("flights-body");
  if (!tbody) {
    console.warn("No existe #flights-body en el DOM");
    return;
  }

  try {
    const res = await fetch(API_VUELOS + "/all", {
      headers: {
        
      }
    });

    if (!res.ok) {
      console.error("fetch /all status:", res.status);
      tbody.innerHTML = `<tr><td colspan="8">Error al cargar vuelos (status ${res.status})</td></tr>`;
      return;
    }

    const vuelos = await res.json();

    tbody.innerHTML = "";

    if (!Array.isArray(vuelos) || vuelos.length === 0) {
      tbody.innerHTML = `<tr><td colspan="8">No hay vuelos.</td></tr>`;
      return;
    }

    vuelos.forEach(v => {
      tbody.innerHTML += `
        <tr>
          <td>${v.id ?? ""}</td>
          <td>${v.nave_id ?? ""}</td>
          <td>${v.origin ?? ""}</td>
          <td>${v.destination ?? ""}</td>
          <td>${v.departure ?? ""}</td>
          <td>${v.arrival ?? ""}</td>
          <td>${v.price ?? ""}</td>
          <td>
            <button onclick="openEdit(${v.id})">Editar</button>
            <button onclick="deleteFlight(${v.id})">Eliminar</button>
          </td>
        </tr>
      `;
    });
  } catch (err) {
    console.error("loadFlights error:", err);
    tbody.innerHTML = `<tr><td colspan="8">Error al cargar vuelos. Revisa la consola.</td></tr>`;
  }
}

async function openEdit(id) {
  document.getElementById("edit-box").style.display = "block";

  try {
    // reusar endpoint /all y buscar el id (ok para dataset pequeño)
    const res = await fetch(API_VUELOS + "/all", {});
    const vuelos = await res.json();
    const vuelo = vuelos.find(v => v.id == id);

    if (!vuelo) {
      alert("Error: vuelo no encontrado");
      return;
    }

    document.getElementById("edit-id").value = vuelo.id;
    document.getElementById("edit-nave-id").value = vuelo.nave_id ?? "";
    document.getElementById("edit-origin").value = vuelo.origin ?? "";
    document.getElementById("edit-destination").value = vuelo.destination ?? "";
    // si el backend devuelve "YYYY-MM-DD HH:MM:SS" conviértelo al input datetime-local
    document.getElementById("edit-departure").value = (vuelo.departure ?? "").replace(" ", "T");
    document.getElementById("edit-arrival").value = (vuelo.arrival ?? "").replace(" ", "T");
    document.getElementById("edit-price").value = vuelo.price ?? "";
  } catch (err) {
    console.error("openEdit error:", err);
    alert("Error al cargar datos del vuelo.");
  }
}

function closeEditForm() {
  document.getElementById("edit-box").style.display = "none";
}


async function updateFlight() {
  const id = document.getElementById("edit-id").value;
  const body = {
    nave_id: document.getElementById("edit-nave-id").value,
    origin: document.getElementById("edit-origin").value,
    destination: document.getElementById("edit-destination").value,
    departure: document.getElementById("edit-departure").value,
    arrival: document.getElementById("edit-arrival").value,
    price: document.getElementById("edit-price").value,
  };

  try {
    const res = await fetch(`${API_VUELOS}/update/${id}`, {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(body)
    });

    const data = await safeJson(res);

    if (!res.ok) {
      alert(data.error || "No se pudo actualizar el vuelo");
      return;
    }

    alert("Vuelo actualizado ✔");
    closeEditForm();
    loadFlights();
  } catch (err) {
    console.error("updateFlight error:", err);
    alert("Error al actualizar. Revisa la consola.");
  }
}


async function deleteFlight(id) {
  if (!confirm("¿Eliminar vuelo?")) return;

  try {
    const res = await fetch(`${API_VUELOS}/delete/${id}`, {
      method: "DELETE",
      headers: {
        //"Authorization": "Bearer " + localStorage.getItem("token")
      }
    });

    const data = await safeJson(res);
    alert(data.message || data.error || "Operación finalizada");
    loadFlights();
  } catch (err) {
    console.error("deleteFlight error:", err);
    alert("Error al eliminar. Revisa la consola.");
  }
}


document.addEventListener("DOMContentLoaded", loadFlights);
