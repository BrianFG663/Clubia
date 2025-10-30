window.facturasInpagas = function (id) {

    console.log(id)

    fetch('/socio/facturas/inpagas', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ socio: id })
    })
        .then((res) => res.json())
        .then(data => {

            console.log(data);

            const facturas = document.querySelector(".facturas-mostrar");
            const overlay = document.getElementById("overlay");
            facturas.classList.remove("hidden");
            facturas.classList.add("mostrar");
            overlay.classList.remove("hidden");
            overlay.classList.add("mostrar");

            if (data.mensaje === true && data.jefe === true) {
                const socio = data.socio;

                document.getElementById('contenedor-informacion').innerHTML = `<h1 class="titulo-facturas-inpagas">Facturas pendientes grupo familiar ${data.socio.apellido}</h1>`

                let socioHtml = `
                    <table class="tabla">
                        <thead>
                            <tr>
                                <th class="tabla-td" colspan="3">${socio.nombre} ${socio.apellido}</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${socio.invoices.map(f => `
                                <tr>
                                    <td class="tabla-td">
                                        ${
                                            f.member_type
                                                ? `Cuota social: ${f.member_type.nombre}`
                                                : f.sub_activity
                                                    ? `Actividad: ${f.sub_activity.nombre}`
                                                    : 'Sin tipo asignado'
                                        }
                                    </td>
                                    <td class="tabla-td">Arancel: $${f.monto_total}</td>
                                    <td class="tabla-td">Fecha: ${f.fecha_factura}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;

                socio.family_members?.forEach(miembro => {
                    socioHtml += `
                        <table class="tabla">
                            <thead>
                                <tr>
                                    <th colspan="3" class="tabla-td">${miembro.nombre} ${miembro.apellido}</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${
                                    Array.isArray(miembro.invoices) && miembro.invoices.length > 0
                                        ? miembro.invoices.map(f => `
                                            <tr>
                                                <td class="tabla-td">
                                                    ${
                                                        f.member_type
                                                            ? `Cuota social: ${f.member_type.nombre}`
                                                            : f.sub_activity
                                                                ? `Actividad: ${f.sub_activity.nombre}`
                                                                : 'Sin tipo asignado'
                                                    }
                                                </td>
                                                <td class="tabla-td">Arancel: $${f.monto_total}</td>
                                                <td class="tabla-td">Fecha: ${f.fecha_factura}</td>
                                            </tr>
                                        `).join('')
                                        : `<tr><td class="tabla-td" colspan="3">El socio se encuentra al dia.</td></tr>`
                                }
                            </tbody>
                        </table>
                    `;
                });

                document.getElementById('contenedor-informacion').innerHTML += socioHtml;
            }


            if (data.mensaje === true && data.jefe === false) {
                const socio = data.socio;

                document.getElementById('contenedor-informacion').innerHTML = `<h1 class="titulo-facturas-inpagas">Facturas pendientes de ${data.socio.nombre} ${data.socio.apellido}</h1>`

                let socioHtml = `
                    <table class="tabla">
                        <thead>
                            <tr>
                                <th class="tabla-td" colspan="3">${socio.nombre} ${socio.apellido}</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${socio.invoices.map(f => `
                                <tr>
                                    <td class="tabla-td">
                                        ${
                                            f.member_type
                                                ? `Cuota social: ${f.member_type.nombre}`
                                                : f.sub_activity
                                                    ? `Actividad: ${f.sub_activity.nombre}`
                                                    : 'Sin tipo asignado'
                                        }
                                    </td>
                                    <td class="tabla-td">Arancel: $${f.monto_total}</td>
                                    <td class="tabla-td">Fecha: ${f.fecha_factura}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>`;

                document.getElementById('contenedor-informacion').innerHTML += socioHtml;
            }


            if (data.mensaje === false && data.jefe === false) {
                const socio = data.socio;

                document.getElementById('contenedor-informacion').innerHTML = `<h1 class="titulo-facturas-inpagas">Facturas pendientes de ${data.socio.nombre} ${data.socio.apellido}</h1>`

                let socioHtml = `
                    <table class="tabla">
                        <thead>
                            <tr>
                                <th class="tabla-td" colspan="3">${socio.nombre} ${socio.apellido}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td class="tabla-td" colspan="3">El socio se encuentra al dia</td></tr>
                        </tbody>
                    </table>`;

                document.getElementById('contenedor-informacion').innerHTML += socioHtml;
            }
        })
}


window.facturasPagas = function (id) {

    console.log(id)

    fetch('/socio/facturas/pagas', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ socio: id })
    })
        .then((res) => res.json())
        .then(data => {

            console.log(data);

            const facturas = document.querySelector(".facturas-mostrar");
            const overlay = document.getElementById("overlay");
            facturas.classList.remove("hidden");
            facturas.classList.add("mostrar");
            overlay.classList.remove("hidden");
            overlay.classList.add("mostrar");

            if (data.mensaje === true && data.jefe === true) {
                const socio = data.socio;

                document.getElementById('contenedor-informacion').innerHTML = `<h1 class="titulo-facturas-inpagas">Facturas pagas del grupo familiar ${data.socio.apellido}</h1>`

                let socioHtml = `
                    <table class="tabla">
                        <thead>
                            <tr>
                                <th class="tabla-td" colspan="3">${socio.nombre} ${socio.apellido}</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${socio.invoices.map(f => `
                                <tr>
                                    <td class="tabla-td">
                                        ${
                                            f.member_type
                                                ? `Cuota social: ${f.member_type.nombre}`
                                                : f.sub_activity
                                                    ? `Actividad: ${f.sub_activity.nombre}`
                                                    : 'Sin tipo asignado'
                                        }
                                    </td>
                                    <td class="tabla-td">Arancel: $${f.monto_total}</td>
                                    <td class="tabla-td">Fecha: ${f.fecha_factura}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;

                socio.family_members?.forEach(miembro => {
                    socioHtml += `
                        <table class="tabla">
                            <thead>
                                <tr>
                                    <th colspan="3" class="tabla-td">${miembro.nombre} ${miembro.apellido}</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${
                                    Array.isArray(miembro.invoices) && miembro.invoices.length > 0
                                        ? miembro.invoices.map(f => `
                                            <tr>
                                                <td class="tabla-td">
                                                    ${
                                                        f.member_type
                                                            ? `Cuota social: ${f.member_type.nombre}`
                                                            : f.sub_activity
                                                                ? `Actividad: ${f.sub_activity.nombre}`
                                                                : 'Sin tipo asignado'
                                                    }
                                                </td>
                                                <td class="tabla-td">Arancel: $${f.monto_total}</td>
                                                <td class="tabla-td">Fecha: ${f.fecha_factura}</td>
                                            </tr>
                                        `).join('')
                                        : `<tr><td class="tabla-td" colspan="3">El socio no tiene facturas pagas.</td></tr>`
                                }
                            </tbody>
                        </table>
                    `;
                });

                document.getElementById('contenedor-informacion').innerHTML += socioHtml;
            }


            if (data.mensaje === true && data.jefe === false) {
                const socio = data.socio;

                document.getElementById('contenedor-informacion').innerHTML = `<h1 class="titulo-facturas-inpagas">Facturas pendientes de ${data.socio.nombre} ${data.socio.apellido}</h1>`

                let socioHtml = `
                    <table class="tabla">
                        <thead>
                            <tr>
                                <th class="tabla-td" colspan="3">${socio.nombre} ${socio.apellido}</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${socio.invoices.map(f => `
                                <tr>
                                    <td class="tabla-td">
                                        ${
                                            f.member_type
                                                ? `Cuota social: ${f.member_type.nombre}`
                                                : f.sub_activity
                                                    ? `Actividad: ${f.sub_activity.nombre}`
                                                    : 'Sin tipo asignado'
                                        }
                                    </td>
                                    <td class="tabla-td">Arancel: $${f.monto_total}</td>
                                    <td class="tabla-td">Fecha: ${f.fecha_factura}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>`;

                document.getElementById('contenedor-informacion').innerHTML += socioHtml;
            }

            if (data.mensaje === false && data.jefe === false) {
                const socio = data.socio;

                document.getElementById('contenedor-informacion').innerHTML = `<h1 class="titulo-facturas-inpagas">Facturas pendientes de ${data.socio.nombre} ${data.socio.apellido}</h1>`

                let socioHtml = `
                    <table class="tabla">
                        <thead>
                            <tr>
                                <th class="tabla-td" colspan="3">${socio.nombre} ${socio.apellido}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td class="tabla-td" colspan="3">Aun no se han abonado facturas.</td></tr>
                        </tbody>
                    </table>`;

                document.getElementById('contenedor-informacion').innerHTML += socioHtml;
            }
        })
}


document.addEventListener("DOMContentLoaded", () => {
    const cerrarBtn = document.getElementById("cerrar-alerta");
    const facturas = document.querySelector(".facturas-mostrar");
    const overlay = document.getElementById("overlay");

    if (cerrarBtn && facturas && overlay) {
        function cerrarModal() {
            facturas.classList.remove("mostrar");
            facturas.classList.add("hidden");
            overlay.classList.remove("mostrar");
            overlay.classList.add("hidden");
            document.getElementById("contenedor-informacion").innerHTML = "";
        }

        cerrarBtn.addEventListener("click", cerrarModal);
        overlay.addEventListener("click", cerrarModal);
    }
});


