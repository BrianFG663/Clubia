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
        const detalleDiv = document.getElementById("detalle-socios");
        const overlay = document.getElementById("overlay");

        if (data.mensaje === true) {
            const sociosHtml = data.socios.map(s => `
                <tr>
                    <td>${s.nombre} ${s.apellido}</td>
                    <td>${s.dni}</td>
                    <td>${s.email ?? ''}</td>
                    <td>${s.telefono ?? ''}</td>
                    <td>
                    <button onclick="bajaSocio(${s.id}, ${data.subactividad.id})">
                        <i class="fa-solid fa-user-xmark"></i>
                    </button>
                </td>
                </tr>
            `).join("");

            document.getElementById("contenedor-informacion").innerHTML = `
                <h2>Socios en sub actividad: ${data.subactividad.nombre}</h2>
                <table class="tabla table-auto w-full">
                    <thead>
                        <tr>
                            <th>Nombre completo</th>
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
            document.getElementById("contenedor-informacion").innerHTML = `<div>No hay socios en esta sub actividad.</div>`;
        }

        // Mostrar modal y overlay juntos
        detalleDiv.classList.add("mostrar");
        detalleDiv.classList.remove("hidden");
        overlay.classList.add("mostrar");
        overlay.classList.remove("hidden");
    });
};

document.addEventListener("DOMContentLoaded", () => {
    const cerrarBtn = document.getElementById("cerrar-detalle");
    const detalleDiv = document.getElementById("detalle-socios");
    const overlay = document.getElementById("overlay");

    if (cerrarBtn && detalleDiv && overlay) {
        cerrarBtn.addEventListener("click", () => {
            detalleDiv.classList.remove("mostrar");
            detalleDiv.classList.add("hidden");
            overlay.classList.remove("mostrar");
            overlay.classList.add("hidden");
            document.getElementById("contenedor-informacion").innerHTML = "";
        });

        // Cerrar modal si se clickea en el overlay también
        overlay.addEventListener("click", () => {
            detalleDiv.classList.remove("mostrar");
            detalleDiv.classList.add("hidden");
            overlay.classList.remove("mostrar");
            overlay.classList.add("hidden");
            document.getElementById("contenedor-informacion").innerHTML = "";
        });
    }
});

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





