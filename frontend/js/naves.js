const API_VUELOS = "http://127.0.0.1:8001";document.addEventListener("DOMContentLoaded", () => {
    cargarNaves();
});


function cargarNaves() {
    fetch(API_URL)
        .then(res => res.json())
        .then(data => mostrarNaves(data))
        .catch(err => console.error("Error cargando naves:", err));
}

function mostrarNaves(naves) {
    const tabla = document.getElementById("tablaNaves");
    tabla.innerHTML = "";

    naves.forEach(nave => {
        const fila = `
            <tr>
                <td>${nave.id}</td>
                <td>${nave.name}</td>
                <td>${nave.capacity}</td>
                <td>${nave.model}</td>
                <td>
                    <button onclick="editarNave(${nave.id}, '${nave.name}', ${nave.capacity}, '${nave.model}')">Editar</button>
                    <button onclick="eliminarNave(${nave.id})">Eliminar</button>
                </td>
            </tr>
        `;
        tabla.innerHTML += fila;
    });
}


document.getElementById("naveForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const id = document.getElementById("naveId").value;
    const name = document.getElementById("name").value;
    const capacity = document.getElementById("capacity").value;
    const model = document.getElementById("model").value;

    const datos = { name, capacity, model };

    
    if (id) {
        fetch(`${API_URL}/${id}`, {
            method: "PUT",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(datos)
        })
        .then(res => res.json())
        .then(() => {
            alert("Nave actualizada correctamente.");
            resetForm();
            cargarNaves();
        });

    } else {
        // Crear nave
        fetch(API_URL, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(datos)
        })
        .then(res => res.json())
        .then(() => {
            alert("Nave registrada correctamente.");
            resetForm();
            cargarNaves();
        });
    }
});



function editarNave(id, name, capacity, model) {
    document.getElementById("naveId").value = id;
    document.getElementById("name").value = name;
    document.getElementById("capacity").value = capacity;
    document.getElementById("model").value = model;
}


function eliminarNave(id) {
    if (!confirm("¿Seguro que deseas eliminar esta nave?")) return;

    fetch(`${API_URL}/${id}`, { method: "DELETE" })
        .then(res => res.json())
        .then(() => {
            alert("Nave eliminada correctamente.");
            cargarNaves();
        });
}


function resetForm() {
    document.getElementById("naveId").value = "";
    document.getElementById("name").value = "";
    document.getElementById("capacity").value = "";
    document.getElementById("model").value = "";
}