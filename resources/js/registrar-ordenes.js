document.getElementById('filtroNombre').addEventListener('input', function() {
    const filtro = this.value.toLowerCase();
    const filas = document.querySelectorAll('#tablaCuerpo tr');
    filas.forEach(fila => {
        const nombreProveedor = fila.cells[2].textContent.toLowerCase(); // índice 2 = columna Proveedor
        fila.style.display = nombreProveedor.includes(filtro) ? '' : 'none';
    });
});


window.mostrarDetallesOrden = function(orderId) {
    console.log('click');
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
        const detalleDiv = document.getElementById("detalle-orden");
        const overlay = document.getElementById("overlay");
        console.log(data);

        if (data.detalles && data.detalles.length > 0) {
            const detallesHtml = data.detalles.map(d => `
                <tr>
                    <td>${d.producto}</td>
                    <td>${d.cantidad}</td>
                    <td>$${d.precio}</td>
                </tr>
            `).join("");

            document.getElementById("contenedor-informacion").innerHTML = `
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
            document.getElementById("contenedor-informacion").innerHTML = `<div style="display: flex; justify-content: center; align-items: center; height: 100%;">No hay detalles para esta orden.</div>`;
        }

        detalleDiv.classList.add("mostrar");  
        overlay.classList.add("mostrar");      
    })
    .catch(error => {
        console.error(error);
        alert("Error al cargar los detalles de la orden.");
    });
}


window.cerrarModal = function() {
    document.getElementById("detalle-orden").classList.remove("mostrar");
    document.getElementById("overlay").classList.remove("mostrar");
};

// Listener para el botón cerrar
document.getElementById("cerrar-detalle").addEventListener("click", window.cerrarModal);

// Listener para el overlay también para cerrar modal al clickear fuera
document.getElementById("overlay").addEventListener("click", window.cerrarModal);


