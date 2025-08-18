const select = document.getElementById('actividad');
select.addEventListener('change', function () {
    const actividad = this.value;

    document.getElementById('facturas').innerHTML =
    `<div class="facturas-pagas" id="facturas-pagas"></div>
    <div class="facturas-no-pagas" id="facturas-no-pagas"></div>`

    document.getElementById('facturas').style.padding = '0%';
    document.getElementById('facturas').style.flexDirection = 'row';


    const facturasPagas = document.getElementById('facturas-pagas')
    const facturasNoPagas = document.getElementById('facturas-no-pagas')
    facturasNoPagas.innerHTML = '';
    facturasPagas.innerHTML = '';
    


    const mensajeVacio = document.createElement("div");
    mensajeVacio.classList.add("carrito-vacio");
    const imagen = document.createElement("img");
    imagen.src = "/images/html/caja-vacia.png";
    imagen.classList.add("img-vacio");

    const texto = document.createElement("span");
    texto.textContent = "No se ha selccionado proveedor/socio/ventas";

    mensajeVacio.appendChild(imagen);
    mensajeVacio.appendChild(texto);
    facturasPagas.appendChild(mensajeVacio);
    facturasNoPagas.appendChild(mensajeVacio.cloneNode(true));


    if (actividad == 1) {
        document.getElementById('select-js').innerHTML =
        `<div class="div-input-button">
        <label for="dni">Ingrese numero de documento</label>
            <input type="number" id="dni" class="dni">
            <button class="btn-dni" id="btn-dni">Buscar</button>
        </div>`

        document.getElementById("btn-dni").addEventListener("click", function() {
            const dni = document.getElementById("dni").value;
            console.log(dni)

            if (dni == '') {
                Swal.fire({
                text: 'Por favor ingrese un numero de documento.',
                showConfirmButton: true,
                confirmButtonText: 'Entendido',
                backdrop: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                imageWidth: 100,
                imageHeight: 100,
                imageUrl: "/images/alertas/advertencia.png"
                });

                return
            }

            fetch("/notacredito/socio/facturas", {
                method: "POST",
                credentials: "same-origin",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                },
                body: JSON.stringify({ dni: dni })
            })
                .then((res) => res.json())
                .then((data) => {

                    if (data.socio == false) {
                        Swal.fire({
                        text: `No hay un socio registrado con ese dni.`,
                        imageUrl: "/images/alertas/advertencia.png",
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#3085d6',
                        backdrop: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        imageWidth: 100,
                        imageHeight: 100,
                        });
                    }
                    
                    if (data.mensaje == true) {
                        console.log(data)
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

                    const generarFilas = (facturas) => {
                        return facturas.map(p => 
                            `<tr data-id="${p.id}">
                                <td>${p.partner.nombre} ${p.partner.apellido}</td>
                                <td>${p.fecha_factura}</td>
                                <td>${p.monto_total}</td>
                                <td>
                                    <button class="btn-borrar" title="Eliminar factura" onclick="realizarNotaDebito(${p.id})" style="background:none; border:none; cursor:pointer;">
                                        <i class="fa-regular fa-credit-card"></i>
                                    </button>
                                </td>
                            </tr>`).join('');
                        };

                    const facturasPagas = data.facturas.filter(p => p.estado_pago == 1);
                    const facturasImpagas = data.facturas.filter(p => p.estado_pago == 0);

                    const contPagas = document.getElementById('facturas-pagas');
                    const contImpagas = document.getElementById('facturas-no-pagas');

                    // Facturas pagas
                    if (facturasPagas.length > 0) {
                        contPagas.innerHTML = 
                        `<h2 class="titulo-facturas-pagas">Facturas pagas</h2>
                        <table border="1" cellspacing="0" cellpadding="5">
                            <thead>
                                <tr>
                                    <th>Socio</th>
                                    <th>Fecha de factura</th>
                                    <th>Monto total</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                                <tbody>${generarFilas(facturasPagas)}</tbody>
                        </table>`;
                        } else {
                            contPagas.innerHTML = "";
                            contPagas.appendChild(crearMensajeVacio("Este proveedor no tiene facturas pagas"));
                        }

                        // Facturas impagas
                        if (facturasImpagas.length > 0) {
                            contImpagas.innerHTML = 
                            `<h2 class="titulo-facturas-inpagas">Facturas a pagar</h2>
                            <table border="1" cellspacing="0" cellpadding="5">
                                <thead>
                                    <tr>
                                        <th>Socio</th>
                                        <th>Fecha de factura</th>
                                        <th>Monto total</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>${generarFilas(facturasImpagas)}</tbody>
                            </table>`;
                        } else {
                            contImpagas.innerHTML = "";
                            contImpagas.appendChild(crearMensajeVacio("Este proveedor no tiene facturas impagas"));
                        }
                    }

                    if (data.mensaje == false) {
                        Swal.fire({
                            title: 'Atencion',
                            text: 'Este socio no tiene facturas registradas',
                            icon: 'info',
                            confirmButtonText: 'Entendido',
                            confirmButtonColor: '#3085d6',
                            backdrop: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            imageWidth: 100,
                            imageHeight: 100,
                        });
                    }
                    
                }) //fin de fetch
            });
        }

    if (actividad == 2) {

        fetch("/notacredito/buscar/proveedores", {
            method: "POST",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            }
        })
        .then((res) => res.json())
        .then((data) => {
            console.log(data)

            document.getElementById('select-js').innerHTML = `<label for="proveedores">Seleccione proveedor</label><select id="proveedores"><option value="${false}" disabled selected hidden>--Lista de proveedores--</option></select>`
            const proveedores = data.proveedores.map(
                (p) => `<option value="${p.id}">${p.nombre}</option>`
            ).join("");

            document.getElementById('proveedores').innerHTML += proveedores


            const selectProveedores = document.getElementById('proveedores');
            selectProveedores.addEventListener('change', function () {
            const proveedor = this.value;

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

                        const generarFilas = (facturas) => {
                            return facturas.map(p => 
                                `<tr data-id="${p.id}">
                                    <td>${p.order?.supplier?.nombre ?? ''}</td>
                                    <td>${p.fecha_factura}</td>
                                    <td>${p.monto_total}</td>
                                    <td>
                                        <button class="btn-borrar" title="Eliminar factura" onclick="realizarNotaDebito(${p.id})" style="background:none; border:none; cursor:pointer;">
                                            <i class="fa-regular fa-credit-card"></i>
                                        </button>
                                    </td>
                                </tr>`).join('');
                            };

                        const facturasPagas = data.facturas.filter(p => p.estado_pago == 1);
                        const facturasImpagas = data.facturas.filter(p => p.estado_pago == 0);

                        const contPagas = document.getElementById('facturas-pagas');
                        const contImpagas = document.getElementById('facturas-no-pagas');

                            // Facturas pagas
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
                                    ${generarFilas(facturasPagas)}
                                </tbody>
                            </table>`;
                        } else {
                            contPagas.innerHTML = "";
                            contPagas.appendChild(crearMensajeVacio("Este proveedor no tiene facturas pagas"));
                        }

                            // Facturas impagas
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
                                <tbody>${generarFilas(facturasImpagas)}</tbody>
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
                    }
                })

            })

        })
    }


    if(actividad == 3){
        fetch("/notacredito/ventas/facturas", {
            method: "POST",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            }
        })
        .then((res) => res.json())
        .then((data) => {
            if (data.mensaje == true) {
                console.log(data)
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

                const generarFilas = (facturas) => {
                    return facturas.map(p => `
                    <tr data-id="${p.id}">
                        <td>${p.sale.user.nombre} ${p.sale.user.apellido}</td>
                        <td>${p.fecha_factura}</td>
                        <td>${p.monto_total}</td>
                        <td>${p.sale && p.sale.sale_details ? p.sale.sale_details.length : 0}</td>
                        <td>
                            <button class="btn-borrar" title="Eliminar factura" onclick="realizarNotaDebito(${p.id})" style="background:none; border:none; cursor:pointer;">
                                <i class="fa-regular fa-credit-card"></i>
                            </button>
                        </td>
                    </tr>`).join('');
                };

                const facturasPagas = data.facturas.filter(p => p.estado_pago == 1);
                const contPagas = document.getElementById('facturas');

                if (facturasPagas.length > 0) {
                    contPagas.innerHTML = '<h2 class="titulo-facturas-inpagas">Facturas de las ventas realizadas</h2>';
                    contPagas.innerHTML += 
                    `<table border="1" cellspacing="0" cellpadding="5" class="tabla-ventas">
                        <thead>
                            <tr>
                                <th>Encargado de la venta</th>
                                <th>Fecha de la venta</th>
                                <th>Monto total</th>
                                <th>Cantidad de productos</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>${generarFilas(facturasPagas)}</tbody>
                    </table>`;

                    contPagas.style.flexDirection = 'column';
                    contPagas.style.padding = '2%';

                } else {
                    contPagas.innerHTML = "";
                    contPagas.appendChild(crearMensajeVacio("Este proveedor no tiene facturas pagas"));
                    }
            }
        })
    }
});

window.realizarNotaDebito = function(id){
    const idFactura = id
    console.log(idFactura)

    fetch("/notacredito/elimininar/facturas", {
        method: "DELETE",
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
        if(data.mensaje == true){
            fetch("/notacredito/ventas/facturas", {
            method: "POST",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            }
            })
            .then((res) => res.json())
            .then((data) => {
                if (data.mensaje == true) {
                    console.log(data)
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

                    const generarFilas = (facturas) => {
                        return facturas.map(p => `
                        <tr data-id="${p.id}">
                            <td>${p.sale.user.nombre} ${p.sale.user.apellido}</td>
                            <td>${p.fecha_factura}</td>
                            <td>${p.monto_total}</td>
                            <td>${p.sale && p.sale.sale_details ? p.sale.sale_details.length : 0}</td>
                            <td>
                                <button class="btn-borrar" title="Eliminar factura" onclick="realizarNotaDebito(${p.id})" style="background:none; border:none; cursor:pointer;">
                                    <i class="fa-regular fa-credit-card"></i>
                                </button>
                            </td>
                        </tr>`).join('');
                    };

                    const facturasPagas = data.facturas.filter(p => p.estado_pago == 1);
                    const contPagas = document.getElementById('facturas');

                    if (facturasPagas.length > 0) {
                        contPagas.innerHTML = 
                        `<table border="1" cellspacing="0" cellpadding="5" class="tabla-ventas">
                            <thead>
                                <tr>
                                    <th>Encargado de la venta</th>
                                    <th>Fecha de la venta</th>
                                    <th>Monto total</th>
                                    <th>Cantidad de productos</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>${generarFilas(facturasPagas)}</tbody>
                        </table>`;

                        contPagas.style.padding = '2%';

                    } else {
                        contPagas.innerHTML = "";
                        contPagas.appendChild(crearMensajeVacio("Este proveedor no tiene facturas pagas"));
                    }
                }
            })
        }
    })
}