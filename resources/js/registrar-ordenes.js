window.mostrarDetallesOrden = function(orderId) {
    fetch(`/ordenes/${orderId}/detalles`, {
        method: "GET",
        credentials: "same-origin",
        headers: {
            "Accept": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
        }
    })
    .then(res => res.json())
    .then(data => {
        let contenidoHTML = "";

        if (data.detalles && data.detalles.length > 0) {
            const detallesHtml = data.detalles.map(d => `
                <tr>
                    <td>${d.producto}</td>
                    <td>${d.cantidad}</td>
                    <td>$${d.precio}</td>
                </tr>
            `).join("");

            contenidoHTML = `
                <h2 style="margin-left: 1rem; margin-bottom: 1rem;">
                    Detalle de la orden N°${orderId} del proveedor ${data.proveedor}
                </h2>
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                        </tr>
                    </thead>
                    <tbody>${detallesHtml}</tbody>
                </table>
            `;
        } else {
            contenidoHTML = `<div style="display: flex; justify-content: center; align-items: center; height: 100%;">No hay detalles para esta orden.</div>`;
        }

        abrirModal("detalle-orden", "overlay-orden", contenidoHTML);
    })
    .catch(error => {
        console.error(error);
        alert("Error al cargar los detalles de la orden.");
    });
}

function abrirModal(modalId, overlayId, contenidoHTML) {
    const modal = document.getElementById(modalId);
    const overlay = document.getElementById(overlayId);

    if (contenidoHTML) {
        modal.querySelector("#contenedor-informacion").innerHTML = contenidoHTML;
    }

    modal.classList.add("mostrar");
    overlay.classList.add("mostrar");

    // Cerrar modal
    const cerrarBtn = modal.querySelector(".cerrar-detalle");
    cerrarBtn?.addEventListener("click", () => cerrarModal(modalId, overlayId));
    overlay?.addEventListener("click", () => cerrarModal(modalId, overlayId));
}

function cerrarModal(modalId, overlayId) {
    const modal = document.getElementById(modalId);
    const overlay = document.getElementById(overlayId);

    modal.classList.remove("mostrar");
    overlay.classList.remove("mostrar");
    modal.querySelector("#contenedor-informacion").innerHTML = "";
}

window.generarFactura = function(orderId) {
    fetch(`/ordenes/${orderId}/factura`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(data => {
        console.log(data);
         if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Factura generada!',
                text: 'La factura se creó correctamente ✅',
                showConfirmButton: true
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Factura existente',
                text: 'Ya existe una factura para esta orden ⚠️',
    
                showConfirmButton: true
            });
        }
    })
    .catch(function(error) {
        console.error("Error:", error);
    });
};
