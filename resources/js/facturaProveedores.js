const select = document.getElementById('proveedor');
select.addEventListener('change', function () {
    const proveedor = this.value;
    console.log(proveedor)
    facturasProveedor(proveedor);
})

function facturasProveedor(proveedorid) {
    const proveedor = proveedorid

    fetch("/notacredito/proveedor/facturas", {
        method: "POST",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({ proveedor: proveedor }),
    })
    .then((res) => res.json())
    .then((data) => {
        console.log(data)

        if (data.mensaje == true) {
            const crearMensajeVacio = (texto) => {
                const div = document.createElement("div");
                div.classList.add("carrito-vacio");

                const img = document.createElement("img");
                img.src = "/images/html/caja-vacia.png";
                img.classList.add("img-vacio");

                const span = document.createElement("span");
                span.textContent = texto;

                div.appendChild(img);
                div.appendChild(span);

                return div;
            };

            const generarFilasinpagas = (facturas) => {
                return facturas.map(p =>
                    `<tr data-id="${p.id}">
                        <td>${p.order?.supplier?.nombre ?? ''}</td>
                        <td>${p.fecha_factura}</td>
                        <td>${p.monto_total}</td>
                        <td>
                            <button class="btn-borrar" title="Pagar factura" onclick="pagarFactura(${p.id},'${proveedor}')" style="background:none; border:none; cursor:pointer;">
                                <i class="fa-solid fa-wallet"></i>
                            </button>
                        </td>
                    </tr>`).join('');
            };

            const generarFilaspagas = (facturas) => {
                return facturas.map(p =>
                    `<tr data-id="${p.id}">
                        <td>${p.order?.supplier?.nombre ?? ''}</td>
                        <td>${p.fecha_factura}</td>
                        <td>${p.monto_total}</td>
                        <td>
                            <button class="btn-borrar" title="Generar PDF" onclick="generarPdf(${p.id})" style="background:none; border:none; cursor:pointer;">
                                <i class="fa-solid fa-file-pdf"></i>
                            </button>
                        </td>
                    </tr>`).join('');
            };

            const facturasPagas = data.facturas.filter(p => p.estado_pago == 1);
            const facturasImpagas = data.facturas.filter(p => p.estado_pago == 0);

            const contPagas = document.getElementById('facturas-pagas');
            const contImpagas = document.getElementById('facturas-no-pagas');

            if (facturasPagas.length > 0) {
                contPagas.innerHTML =
                    `<h2 class="titulo-facturas-pagas">Facturas pagas</h2>
                        <table border="1" cellspacing="0" cellpadding="5">
                            <thead>
                                <tr>
                                    <th>Proveedor</th>
                                    <th>Fecha de factura</th>
                                    <th>Monto total</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${generarFilaspagas(facturasPagas)}
                            </tbody>
                        </table>`;
            } else {
                contPagas.innerHTML = "";
                contPagas.appendChild(crearMensajeVacio("Este proveedor no tiene facturas pagas"));
            }

            if (facturasImpagas.length > 0) {
                contImpagas.innerHTML =
                    `<h2 class="titulo-facturas-inpagas">Facturas a pagar</h2>
                        <table border="1" cellspacing="0" cellpadding="5">
                            <thead>
                                <tr>
                                  <th>Proveedor</th>
                                    <th>Fecha de factura</th>
                                    <th>Monto total</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>${generarFilasinpagas(facturasImpagas)}</tbody>
                        </table>`;
            } else {
                contImpagas.innerHTML = "";
                contImpagas.appendChild(crearMensajeVacio("Este proveedor no tiene facturas impagas"));
            }
        }

        if (data.mensaje == false) {
            Swal.fire({
                title: 'Atencion',
                text: 'Este proveedor no tiene facturas registradas',
                icon: 'info',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#3085d6',
                backdrop: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                imageWidth: 100,
                imageHeight: 100,
            });
            document.getElementById('facturas-pagas').innerHTML = '';
            document.getElementById('facturas-no-pagas').innerHTML = '';
        }
    })
}


window.pagarFactura = function (id,proveedorId) {
    const idFactura = id;
    const proveedor = proveedorId;

    console.log(proveedorId)

    Swal.fire({
        imageWidth: 100,
        imageHeight: 100,
        imageUrl: "/images/alertas/advertencia.png",
        text: `¿Desea pagar la factura seleccionada?`,
        showCancelButton: true,
        cancelButtonText: "CANCELAR",
        confirmButtonText: "CONFIRMAR",
        confirmButtonColor: "#e74938",
        cancelButtonColor: "#ffd087",
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("/facturas/pagar", {
                method: "POST",
                credentials: "same-origin",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                },
                body: JSON.stringify({ id: idFactura }),
            })
            .then((res) => res.json())
            .then((data) => {
                Swal.fire({
                    imageWidth: 100,
                    imageHeight: 100,
                    imageUrl: "/images/alertas/advertencia.png",
                    text: `¿Desea descargar la factura?`,
                    showCancelButton: true,
                    cancelButtonText: "SALIR",
                    confirmButtonText: "DESCARGAR",
                    confirmButtonColor: "#e74938",
                    cancelButtonColor: "#ffd087",
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Factura marcada con exito',
                            text: 'se marco como paga la factura seleccionada',
                            imageUrl: "/images/alertas/check.png",
                            confirmButtonText: 'Entendido',
                            confirmButtonColor: '#3085d6',
                            backdrop: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            imageWidth: 100,
                            imageHeight: 100,
                        });
                        facturasProveedor(proveedor)
                        window.location.href = `/factura/${idFactura}/pdf`;
                    }else{
                        facturasProveedor(proveedor)
                        Swal.fire({
                            title: 'Factura marcada con exito',
                            text: 'se marco como paga la factura seleccionada',
                            imageUrl: "/images/alertas/check.png",
                            confirmButtonText: 'Entendido',
                            confirmButtonColor: '#3085d6',
                            backdrop: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            imageWidth: 100,
                            imageHeight: 100,
                        });
                    }
                })
            })
        }
    })
}


window.generarPdf = function(id){

    const idFactura = id;
    window.location.href = `/factura/${idFactura}/pdf`;
}

