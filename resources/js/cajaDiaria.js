
document.getElementById('institucion').addEventListener('change', function () {
    const institucion = this.value;
    cajaDiaria(false,institucion)
})

window.agregarMovimiento = function (){

    const descripcion = document.getElementById('descripcion').value
    const rawValue = document.getElementById('total').value;
    const total = rawValue.replace(/\D/g, '');

    const tipo= document.getElementById('tipo').value

    console.log(total);

    if (tipo == false) {
        Swal.fire({
            text: `Por favor, seleccione un tipo de movimiento`,
            backdrop: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            imageWidth: 100,
            imageHeight: 100,
            timer: 2000,
            showConfirmButton: false,
            imageUrl: "/images/alertas/advertencia.png"
        });

        return
    }

    if(total == '' || descripcion == ''){
        Swal.fire({
            text: `Por favor, complete el formulario`,
            backdrop: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            imageWidth: 100,
            imageHeight: 100,
            timer: 2000,
            showConfirmButton: false,
            imageUrl: "/images/alertas/advertencia.png"
        });

        return
    }

    console.log(tipo)
    console.log(total)



    fetch("/cajadiaria/registrar", {
        method: "POST",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({
            descripcion: descripcion,
            total: total,
            institucion: institucion.value,
            tipo:tipo
        }),
    })
        .then((res) => res.json())
        .then((data) => {

            if(data.mensaje == true){
                Swal.fire({
                    imageWidth: 100,
                    imageHeight: 100,
                    imageUrl: "/images/alertas/advertencia.png",
                    title: 'Atención',
                    text: `¿Está seguro de registrar una ${tipo} a la caja diaria?`,
                    cancelButtonText: "CANCELAR",
                    confirmButtonText: "CONFIRMAR",
                    showCancelButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            text: `Se registro la ${tipo} correcatamente`,
                            backdrop: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            imageWidth: 100,
                            imageHeight: 100,
                            timer: 2000,
                            showConfirmButton: false,
                            imageUrl: "/images/alertas/check.png"
                        });

                        cajaDiaria(false,institucion.value)
                        document.getElementById('agregar-movimiento').classList.remove("mostrar");
                        document.getElementById('agregar-movimiento').classList.add("hidden");
                        document.getElementById('overlay').classList.remove("mostrar");
                        document.getElementById('overlay').classList.add("hidden");
                    }
                })
            }
            
        })
}

window.cajaDiaria = function (fechaFiltro,institucionId) {
    const fecha = fechaFiltro
    const institucion = institucionId

    fetch("/cajadiaria", {
        method: "POST",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({
            institucion: institucion,
            fecha: fecha
        }),
    })
        .then((res) => res.json())
        .then((data) => {
            console.log(data)
            if (data.mensaje == true) {
                // Normalizar records para que siempre sea array
                let records = data.records;
                if (!Array.isArray(records)) {
                    records = [records]; 
                }

                const divVacio = document.getElementById("carrito-vacio");

                if (divVacio) {
                    divVacio.remove(); //lo elimino solo si existe (solo existe la primera vez antes de seleccionar la institucion)
                }

                // agregar contenedores generales
                const contenedor = document.getElementById('contenedor-general');

                if (!document.getElementById("monto") && !document.getElementById("movimientos")) { //creo div de monto y movimientos solo si no estan creados
                    const montoDiv = document.createElement("div");
                    montoDiv.id = "monto";
                    montoDiv.classList.add("monto");

                    const movimientosDiv = document.createElement("div");
                    movimientosDiv.id = "movimientos";
                    movimientosDiv.classList.add("movimientos");

                    contenedor.appendChild(montoDiv);
                    contenedor.appendChild(movimientosDiv);
                }

                document.getElementById('movimientos').innerHTML = 
                    `<h1 class="titulo-movimientos">Movimientos del dia</h1>
                    <div class="tabla" id="tabla"></div>`; 

                // reestructurar el monto (usar el primer registro)
                const total = parseFloat(records[0].total);
                const entero = Math.floor(total).toLocaleString('es-AR');
                const decimales = (total % 1).toFixed(2).slice(2);

                document.getElementById('monto').innerHTML = `
                    <span class="total">
                        ${entero}<sup class="decimales">${decimales}</sup>
                    </span>
                    <button title="Ocultar" onclick='ocultarMonto()'>
                        <i class="fa-regular fa-eye"></i>
                    </button>`;

                const generarFilas = (records) => {
                    const detalles = records.flatMap(record => record.cash_records_details || []);

                    if (detalles.length === 0) {
                        return `<tr><td colspan="5" style="text-align:center;">No se han hecho movimientos el día de hoy</td></tr>`;
                    }

                    return detalles.slice().reverse().map(detail => {
                        const signo = detail.tipo === 'salida' ? '-' : '';
                        return `
                            <tr data-id="${detail.id}">
                                <td>${detail.responsable?.nombre ?? ''} ${detail.responsable?.apellido ?? ''}</td>
                                <td>${detail.descripcion ?? ''}</td>
                                <td>${signo}${new Intl.NumberFormat('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(detail.total)}</td>
                                <td>${detail.tipo}</td>
                                <td>
                                    <button class="btn-borrar" title="Eliminar" onclick="eliminarMovimiento(${detail.id})" style="background:none; border:none; cursor:pointer;">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    }).join('');
                };

                const contTabla = document.getElementById('tabla');

                contTabla.innerHTML = `
                    <table border="1" cellspacing="0" cellpadding="5">
                        <thead>
                            <tr>
                                <th>Responsable</th>
                                <th>Descripción</th>
                                <th>Total</th>
                                <th>Tipo de movimiento</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${generarFilas(records)}
                        </tbody>
                    </table>`;
            }


        })
}

window.eliminarMovimiento = function (id) {

    const movimiento = id;
    console.log(institucion.value)
    console.log(movimiento)


    Swal.fire({
        imageWidth: 100,
        imageHeight: 100,
        imageUrl: "/images/alertas/advertencia.png",
        title: '¿Está seguro que desea eliminar este movimiento?',
        text: `Atención: esta acción no tiene vuelta atrás`,
        cancelButtonText: "CANCELAR",
        confirmButtonText: "CONFIRMAR",
        showCancelButton: true,
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("/cajadiaria/eliminar/movimiento", {
                method: "DELETE",
                credentials: "same-origin",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({
                    movimiento: movimiento
                }),
            })
                .then((res) => res.json())
                .then((data) => {
                    console.log(data)
                    Swal.fire({
                        text: 'Movimiento eliminado correctamente',
                        showConfirmButton: false,
                        confirmButtonText: 'Entendido',
                        backdrop: false,
                        timer: 2000,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        imageWidth: 100,
                        imageHeight: 100,
                        imageUrl: "/images/alertas/check.png"
                    });
                    cajaDiaria(false, institucion.value)
                })
        }
    })


}


window.ocultarMonto = function(){
    document.getElementById('monto').innerHTML = 
    `<span class="total">
        **<sup class="decimales">**</sup>
    </span>
    <button title="Mostrar" onclick='mostrarMonto()'>
        <i class="fa-regular fa-eye-slash"></i>
    </button>`;
}

window.mostrarMonto = function(){

    const fecha = false;
    const institucion = document.getElementById('institucion').value



    fetch("/cajadiaria", {
        method: "POST",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({
            institucion: institucion,
            fecha: fecha
        }),
    })
        .then((res) => res.json())
        .then((data) => {

            let records = data.records;
            if (!Array.isArray(records)) {
                records = [records]; 
            }

            // reestructurar el monto (usar el primer registro)
            const total = parseFloat(records[0].total);
            const entero = Math.floor(total).toLocaleString('es-AR');
            const decimales = (total % 1).toFixed(2).slice(2);

            document.getElementById('monto').innerHTML = 
            `<span class="total">
                ${entero}<sup class="decimales">${decimales}</sup>
            </span>
            <button title="ocultar" onclick='ocultarMonto()'>
                <i class="fa-regular fa-eye"></i>
            </button>`;
        })
}



document.getElementById('btn-registrar').addEventListener("click", function() {
    

    let institucion = document.getElementById('institucion').value

    if(institucion == false){
        
        Swal.fire({
            text: `Por favor, seleccione una institución`,
            backdrop: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            imageWidth: 100,
            imageHeight: 100,
            timer: 2000,
            showConfirmButton: false,
            imageUrl: "/images/alertas/advertencia.png"
        });

        return

    }else{
        document.getElementById('agregar-movimiento').classList.remove("hidden");
        document.getElementById('agregar-movimiento').classList.add("mostrar");
        document.getElementById('overlay').classList.remove("hidden");
        document.getElementById('overlay').classList.add("mostrar");
    }
});

document.getElementById('cerrar-alerta').addEventListener("click", function() {
    document.getElementById('agregar-movimiento').classList.remove("mostrar");
    document.getElementById('agregar-movimiento').classList.add("hidden");
    document.getElementById('overlay').classList.remove("mostrar");
    document.getElementById('overlay').classList.add("hidden");
});