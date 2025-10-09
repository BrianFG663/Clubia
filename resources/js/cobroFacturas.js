window.buscarSocio = function () {
    const valor = document.getElementById('filtroNombre').value.trim();

    if (valor === '') {
        window.location.reload();
        return;
    } 
    fetch('/buscar/socio', {
    method: 'POST',
    credentials: 'same-origin',
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({ filtro: valor })
})

    .then((res) => res.json())
    .then(data => {
        console.log(data);
        const cuerpoTabla = document.getElementById('tablaCuerpo');

        if (data.success === false) {
            cuerpoTabla.innerHTML = `<tr><td colspan="5" style="text-align:center;">${data.message}</td></tr>`;
            return;
        }

        if (!data.socios || !data.socios.length) {
            cuerpoTabla.innerHTML = `<tr><td colspan="5" style="text-align:center;">No se encontraron socios.</td></tr>`;
            return;
        }

        const filasHTML = data.socios.map(socio => `
            <tr>
                <td>${socio.nombre} ${socio.apellido}</td>
                <td>${socio.dni}</td>
                <td>${socio.jefe_grupo ? 'Sí' : 'No'}</td>
                <td>
                    ${socio.total_impagas}
                    ${socio.total_impagas > 0 ? `
                        <button class="btn-ver" onclick="verFacturasImpagas(${socio.id})">
                            <i class="fa-solid fa-eye"></i>
                        </button>` : ''}
                </td>
                <td>
                    ${socio.total_pagas}
                    ${socio.total_pagas > 0 ? `
                        <button class="btn-ver" onclick="verFacturasPagas(${socio.id})">
                            <i class="fa-solid fa-eye"></i>
                        </button>` : ''}
                </td>
            </tr>
        `).join('');
        cuerpoTabla.innerHTML = filasHTML;
    })
    .catch(error => {
        console.error("Error al buscar socios:", error);
    });
};


window.verFacturasImpagas = function(partnerId) {
    fetch(`/facturas/impagas/${partnerId}`, {
        method: 'GET',
        credentials: 'same-origin',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(res => res.json())
    .then(data => {
        console.log('TITULAR' ,data.facturasTitular);
        console.log('FAMILIAR ',data.facturasFamiliares);

        if ((!data.facturasTitular || data.facturasTitular.length === 0) &&
            (!data.facturasFamiliares || data.facturasFamiliares.length === 0)) {
            abrirModal("detalle-factura", "overlay-factura", `<div style="padding:1rem; text-align:center;">No hay facturas impagas para este socio.</div>`);
            return;
        }

        const generarFilas = (facturas, tipo) => facturas.map(f => {
            return `
                <tr>
                    <td>${f.tipo_factura}</td>
                    <td>${f.subActivity ?? '-'}</td>
                    <td>${f.memberType ?? '-'}</td>
                    <td>${f.institution ?? '-'}</td>
                    <td>${f.fecha_factura}</td>
                    <td>$${new Intl.NumberFormat('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(f.monto_total)}</td>
                    ${tipo === 'familiar' ? `<td>${f.partner_name ?? '-'}</td>` : ''}
                    <td>
                        <label class="custom-checkbox">
                            <input type="checkbox" class="check-factura" data-id="${f.id}" data-monto="${f.monto_total}">
                            <span class="checkmark"></span>
                        </label>
                    </td>
                </tr>
            `;
        }).join('');

        let contenidoHTML = `
            <div class="header-tabla" style="display: flex; justify-content: space-between; align-items: center; margin-left: 1rem; margin-right: 3rem;">
                <h2>Facturas de ${data.partner}</h2>
                <button id="btn-toggle" class="btn-ver">Seleccionar todos</button>
            </div>`;

        // Tabla del titular
        if (data.facturasTitular?.length) {
            contenidoHTML += `
                <div class="contenedor-modal">
                    <table class="tabla">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Subactividad</th>
                                <th>Tipo de socio</th>
                                <th>Institución</th>
                                <th>Fecha</th>
                                <th>Monto</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${generarFilas(data.facturasTitular, 'titular')}
                        </tbody>
                    </table>
                </div>`;
        }

        // Tabla de familiares
        if (data.facturasFamiliares?.length) {
            contenidoHTML += `
                <h3 style="display: flex; justify-content: space-between; align-items: center; margin-left: 1rem; margin-top: 0.5rem; margin-bottom: 0.5rem;">Facturas de familiares</h3>
                <div class="contenedor-modal">
                    <table class="tabla">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Subactividad</th>
                                <th>Tipo de socio</th>
                                <th>Institución</th>
                                <th>Fecha</th>
                                <th>Monto</th>
                                <th>Socio/Familiar</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${generarFilas(data.facturasFamiliares, 'familiar')}
                        </tbody>
                    </table>
                </div>`;
        }

        contenidoHTML += `
            <div class="total-container">
                <div class="total-text">
                    <strong>Total a pagar: $<span id="total-pagar">0.00</span></strong>
                </div>
                <div class="pago-select">
                    <label for="metodo-pago" style="margin-right: 0.5rem;">Método de pago:</label>
                    <select id="metodo-pago" class="select-pago">
                        <option value="">Cargando métodos...</option>
                    </select>
                </div>
                <button id="btn-pagar-facturas" class="btn-pagar">Cobrar</button>
            </div>`;


        abrirModal("detalle-factura", "overlay-factura", contenidoHTML);


        const selectPago = document.getElementById('metodo-pago');
        if (data.paymentTypes && data.paymentTypes.length > 0) {
            selectPago.innerHTML = '<option value="">Seleccionar...</option>';
            data.paymentTypes.forEach(tipo => {
                const opcion = document.createElement('option');
                opcion.value = tipo.id;
                opcion.textContent = tipo.nombre;
                selectPago.appendChild(opcion);
            });
        } else {
            selectPago.innerHTML = '<option value="">Sin métodos disponibles</option>';
        }

      // Checkboxes y total
            const casillas = document.querySelectorAll('.check-factura');   
            const totalAPagar = document.getElementById('total-pagar');    
            casillas.forEach(casilla => {
                casilla.addEventListener('change', () => {
                    const total = Array.from(casillas)
                        .filter(c => c.checked)
                        .reduce((suma, c) => suma + parseFloat(c.dataset.monto), 0);
                    totalAPagar.textContent = total.toFixed(2);
                });
            });

            // Botón seleccionar/deseleccionar todos
            const botonAlternar = document.getElementById("btn-toggle");
            botonAlternar.addEventListener("click", () => {
                const todasSeleccionadas = Array.from(casillas).every(c => c.checked);
                casillas.forEach(c => c.checked = !todasSeleccionadas);

                // Actualizar total
                const total = Array.from(casillas)
                    .filter(c => c.checked)
                    .reduce((suma, c) => suma + parseFloat(c.dataset.monto), 0);
                totalAPagar.textContent = total.toFixed(2);

                botonAlternar.textContent = todasSeleccionadas ? "Seleccionar todas" : "Deseleccionar todas";
            });


       // Botón cobrar con SweetAlert
            const btnPagar = document.getElementById("btn-pagar-facturas");
            btnPagar.addEventListener("click", () => {
                const todasLasFacturas = document.querySelectorAll('.check-factura');
                const facturasSeleccionadas = [];

                todasLasFacturas.forEach(factura => {
                    if (factura.checked) {
                        facturasSeleccionadas.push(factura.dataset.id);
                    }
                });

                console.log("Facturas seleccionadas para pagar:", facturasSeleccionadas);

                if (facturasSeleccionadas.length === 0) {
                    Swal.fire({
                        text: "Debe seleccionar al menos una factura.",
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                    return;
                }

                const metodoPago = document.getElementById('metodo-pago').value;
                if (!metodoPago) {
                    Swal.fire({
                        text: "Debe seleccionar un método de pago.",
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                    return;
                }

                Swal.fire({
                    imageUrl: "/images/alertas/advertencia.png",
                    imageWidth: 100,
                    imageHeight: 100,
                    text: "¿Desea cobrar las facturas seleccionadas?",
                    cancelButtonText: "CANCELAR",
                    confirmButtonText: "CONFIRMAR",
                    showCancelButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('/pagos/pagar-facturas', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                facturas: facturasSeleccionadas,
                                payment_type_id: metodoPago,
                                partner_id: partnerId
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.mensaje === true) {
                                Swal.fire({
                                    text: "Facturas pagadas correctamente",
                                    showConfirmButton: false,
                                    timer: 2000,
                                    backdrop: false,
                                    allowOutsideClick: false,
                                    imageWidth: 100,
                                    imageHeight: 100,
                                    imageUrl: "/images/alertas/check.png",
                                });

                                setTimeout(() => {
                                    cerrarModal("detalle-factura", "overlay-factura");
                                    location.reload(); 
                                }, 2000);
                            } else {
                                Swal.fire({
                                    text: "No se pudieron pagar las facturas",
                                    icon: "error",
                                    confirmButtonText: "OK"
                                });
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            Swal.fire({
                                text: "Error al procesar el pago",
                                icon: "error",
                                confirmButtonText: "OK"
                            });
                        });
                    }
                });
            });
    })
    .catch(err => {
        console.error(err);
        alert('Error al cargar las facturas impagas');
    });
};


window.verFacturasPagas = function(partnerId) {
    fetch(`/facturas/pagas/${partnerId}`, {
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
            abrirModal("detalle-factura", "overlay-factura", `<div style="padding:1rem; text-align:center;">No hay facturas pagas para este socio.</div>`);
            return;
        }

        const generarFilas = (facturas, tipo) => facturas.map(f => `
            <tr>
                <td>${f.tipo_factura}</td>
                <td>${f.subActivity ?? '-'}</td>
                <td>${f.memberType ?? '-'}</td>
                <td>${f.institution ?? '-'}</td>
                <td>${f.fecha_factura}</td>
                <td>$${f.monto_total}</td>
                ${tipo === 'familiar' ? `<td>${f.partner_name ?? '-'}</td>` : ''}
            </tr>
        `).join('');

        let contenidoHTML = `
            <h2 style="display: flex; justify-content: space-between; align-items: center; margin-left: 1rem; margin-top: 0.5rem;">
                Facturas pagas de ${data.partner}
            </h2>
            `;


        if (data.facturasTitular?.length) {
            contenidoHTML += `
                <div class="contenedor-modal">
                    <table class="tabla">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Subactividad</th>
                                <th>Tipo de socio</th>
                                <th>Institución</th>
                                <th>Fecha</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${generarFilas(data.facturasTitular, 'titular')}
                        </tbody>
                    </table>
                </div>
            `;
        }

        if (data.facturasFamiliares?.length) {
            contenidoHTML += `
                <h3 style="display: flex; justify-content: space-between; align-items: center; margin-left: 1rem; margin-top: 0.5rem; margin-bottom: 0.5rem;" >Facturas de familiares</h3>
                <div class="contenedor-modal">
                    <table class="tabla">
                        <thead>
                            <tr>
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
                            ${generarFilas(data.facturasFamiliares, 'familiar')}
                        </tbody>
                    </table>
                </div>
            `;
        }
        abrirModal("detalle-factura", "overlay-factura", contenidoHTML);
    })
    .catch(err => {
        console.error(err);
        alert('Error al cargar las facturas pagas');
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
