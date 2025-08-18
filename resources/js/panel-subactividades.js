document.getElementById('filtroNombre').addEventListener('input', function() {
    const filtro = this.value.toLowerCase();
    const filas = document.querySelectorAll('#tablaCuerpo tr');

    filas.forEach(fila => {
        const nombreSubactividad = fila.cells[1].textContent.toLowerCase(); 
        fila.style.display = nombreSubactividad.includes(filtro) ? '' : 'none';
    });
});

window.mostrarSocios = function(subactividadId) {
    fetch("/panel-subactividades", {
        method: "POST",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
        },
        body: JSON.stringify({ id: subactividadId }),
    })
    .then(res => res.json())
    .then(data => {
        let contenido = ""; 

        if (data.mensaje === true) {
            const sociosHtml = data.socios.map(s => `
                <tr>
                    <td>${s.nombre} ${s.apellido}</td>
                    <td>${s.dni}</td>
                    <td>${s.email}</td>
                    <td>${s.telefono}</td>
                    <td style="text-align: center;">
                        <button class="btn" onclick="bajaSocio(${s.id}, ${data.subactividad.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join("");

            contenido = `
                <h2 style="margin-left: 1rem; margin-bottom: 1rem;">Socios en sub actividad: ${data.subactividad.nombre}</h2>
                <table class="tabla table-auto w-full">
                    <thead>
                        <tr>
                            <th>Nombre </th>
                            <th>DNI</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>${sociosHtml}</tbody>
                </table>
            `;
        } else {
            contenido = `<div class="mensaje" style="text-align:center; margin: 1.5rem" >No hay socios en esta subactividad.</div>`;
        }

        abrirModal("detalle-socios", "overlay-socios", contenido);
    });
};



window.bajaSocio = function(socioId, subactividadId) {
    Swal.fire({
        imageWidth: 100,
        imageHeight: 100,
        imageUrl: "/images/alertas/advertencia.png",
        title: "¿Estás seguro?",
        text: "El socio será dado de baja de esta sub actividad.",
        showCancelButton: true,
        confirmButtonColor: "#e74938",
        cancelButtonColor: "#ffd087",
        confirmButtonText: "Confirmar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("/subactividades/baja-socio", {
                method: "PATCH",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                body: JSON.stringify({
                    id: socioId,
                    subactividad_id: subactividadId
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.mensaje === true) {
                    Swal.fire({
                        icon: "success",
                        text: "Socio dado de baja correctamente.",
                        timer: 2000,
                        showConfirmButton: false
                    });
                    mostrarSocios(subactividadId);
                    setTimeout(() => {
                        location.reload();
                    }, 2100);
                } else {
                    Swal.fire({
                        icon: "error",
                        text: "No se pudo dar de baja al socio.",
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        }
    });
}




function abrirModal(modalId, overlayId, contenidoHTML) {
    const modal = document.getElementById(modalId);
    const overlay = document.getElementById(overlayId);

    if (contenidoHTML) {
        modal.querySelector("#contenedor-informacion").innerHTML = contenidoHTML;
    }

    // Mostrar modal-overlay
    modal.classList.add("mostrar");
    modal.classList.remove("hidden");
    overlay.classList.add("mostrar");
    overlay.classList.remove("hidden");

    // Cerrar modal al hacer click en el overlay
    const cerrarBtn = modal.querySelector(".cerrar-detalle");
    cerrarBtn?.addEventListener("click", () => cerrarModal(modalId, overlayId));
    overlay?.addEventListener("click", () => cerrarModal(modalId, overlayId));
}

function cerrarModal(modalId, overlayId) {
    const modal = document.getElementById(modalId);
    const overlay = document.getElementById(overlayId);

    modal.classList.remove("mostrar");
    modal.classList.add("hidden");
    overlay.classList.remove("mostrar");
    overlay.classList.add("hidden");

    modal.querySelector("#contenedor-informacion").innerHTML = "";
}
