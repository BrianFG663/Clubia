document.getElementById('filtroNombre').addEventListener('input', function() {
    const filtro = this.value.toLowerCase();
    const filas = document.querySelectorAll('#tablaCuerpo tr');

    filas.forEach(fila => {
        const nombre = fila.cells[0].textContent.toLowerCase();
        const dni = fila.cells[1].textContent.toLowerCase();

        if (nombre.includes(filtro) || dni.includes(filtro)) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
});

window.verFacturas = function(partnerId) {
    fetch(`/invoices/impagas/${partnerId}`, {
        method: 'GET',
        credentials: 'same-origin',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(res => res.json())
    .then(data => {
        if ((!data.facturasTitular || data.facturasTitular.length === 0) &&
            (!data.facturasFamiliares || data.facturasFamiliares.length === 0)) {
            abrirModal("detalle-factura", "overlay-factura", `<div style="padding:1rem; text-align:center;">No hay facturas impagas para este socio.</div>`);
            return;
        }

        // Función para generar filas de facturas
        const generarFilas = facturas => facturas.map(f => `
            <tr>
                <td><input type="checkbox" class="check-factura" data-id="${f.id}" data-monto="${f.monto_total}"></td>
                <td>${f.id}</td>
                <td>${f.tipo_factura}</td>
                <td>${f.subActivity ?? '-'}</td>
                <td>${f.memberType ?? '-'}</td>
                <td>${f.institution ?? '-'}</td>
                <td>${f.fecha_factura}</td>
                <td>$${f.monto_total}</td>
                <td>${f.partner_name}</td>
            </tr>
        `).join('');

        let contenidoHTML = `<h2>Facturas de ${data.partner}</h2><div class="tabla-container">`;

        // Tabla del titular
        if (data.facturasTitular && data.facturasTitular.length > 0) {
            contenidoHTML += `
                <h3>Facturas del titular</h3>
                <table class="tabla">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Tipo</th>
                            <th>Subactividad</th>
                            <th>Tipo de socio</th>
                            <th>Institución</th>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Socio/Familiar</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${generarFilas(data.facturasTitular)}
                    </tbody>
                </table>
            `;
        }

        // Tabla de familiares
        if (data.facturasFamiliares && data.facturasFamiliares.length > 0) {
            contenidoHTML += `
                <h3>Facturas de familiares</h3>
                <table class="tabla">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Tipo</th>
                            <th>Subactividad</th>
                            <th>Tipo de socio</th>
                            <th>Institución</th>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Socio/Familiar</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${generarFilas(data.facturasFamiliares)}
                    </tbody>
                </table>
            `;
        }

        contenidoHTML += `</div>
            <div style="margin-top:1rem; display:flex; justify-content: space-between; align-items:center;">
                <strong>Total a pagar: $<span id="total-pagar">0.00</span></strong>
                <button id="btn-pagar-facturas" class="btn-ver">Pagar seleccionadas</button>
            </div>
        `;

        abrirModal("detalle-factura", "overlay-factura", contenidoHTML);

        // Control de checkboxes y total
        const checkboxes = document.querySelectorAll('.check-factura');
        const totalSpan = document.getElementById('total-pagar');
        checkboxes.forEach(chk => {
            chk.addEventListener('change', () => {
                const total = Array.from(checkboxes)
                    .filter(c => c.checked)
                    .reduce((sum, c) => sum + parseFloat(c.dataset.monto), 0);
                totalSpan.textContent = total.toFixed(2);
            });
        });
    })
    .catch(err => {
        console.error(err);
        alert('Error al cargar las facturas impagas');
    });
};




function abrirModal(modalId, overlayId, contenidoHTML) {
    const modal = document.getElementById(modalId);
    const overlay = document.getElementById(overlayId);

    if (contenidoHTML) {
        modal.querySelector("#contenedor-informacion").innerHTML = contenidoHTML;
    }

    modal.classList.add("mostrar");
    overlay.classList.add("mostrar");

    const cerrarBtn = modal.querySelector(".cerrar-factura");
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
