window.onload = function() {
    eliminarRegistroCero();
};

document.getElementById('filtroInstitucion').addEventListener('input', function() {
    const filtro = this.value.toLowerCase();
    const filas = document.querySelectorAll('#tablaCuerpo tr');

    filas.forEach(fila => {
        const nombre = fila.cells[0].textContent.toLowerCase();
        const fecha = fila.cells[1].textContent.trim().toLowerCase();


        if (nombre.includes(filtro) || fecha.includes(filtro)) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
});


document.getElementById("cerrar-alerta").addEventListener('click', function () {

    const movimientosDiv = document.querySelector(".movimientos");
    const overlay = document.getElementById("overlay");

    movimientosDiv.classList.remove("mostrar");
    movimientosDiv.classList.add("hidden");
    overlay.classList.remove("mostrar");
    overlay.classList.add("hidden");
});

window.movimientos = function (record){
    const movimientosDiv = document.querySelector(".movimientos");
    const overlay = document.getElementById("overlay");
    const recordId = record;

    fetch("/cajadiaria/registros", {
        method: "POST",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({
            record: recordId,
        }),
    })
        .then((res) => res.json())
        .then((data) => {
            console.log(data)
            if (data.mensaje == true) {

                if(data.records.cash_records_details.length != 0){
                    const movimientos = data.records.cash_records_details.slice().reverse().map(
                    (m) => `
                        <tr>
                            <td class="nombre">${m.responsable?.nombre ?? ''} ${m.responsable?.apellido ?? ''}</td>
                            <td class="email">${m.descripcion}</td>
                            <td>${m.tipo === 'salida' ? '-$' + (m.total ?? '') : '$'+(m.total ?? '')}</td>
                            <td class="email">${m.tipo}</td>
                            <td class="btn" style="text-align:center;">
                                <button onclick="eliminarMovimiento(${m.id})">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>`
                    ).join("");

                    document.getElementById("contenedor-informacion").innerHTML = `
                    <h2 class="titulo-informacion" style="margin-left: 1rem; margin-bottom: 1rem;">Registro de movimientos de la institucion ${data.records.institution.nombre} en la fecha ${data.records.fecha}</h2>
                    <div class="informacion">
                        <div>
                            <div class="alto-inf"></div>
                            <table class="tabla table-auto w-full">
                                <thead>
                                    <tr>
                                        <th>Responsable</th>
                                        <th>Descripcion</th>
                                        <th>Total</th>
                                        <th>tipo de movimiento</th>
                                        <th>Accion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${movimientos}
                                </tbody>
                            </table>
                            <div class="bajo-inf"></div>
                        </div>
                    </div>`;
                }else{
                    document.getElementById("contenedor-informacion").innerHTML = `<div class="mensaje" style="text-align:center; margin: 1.5rem" >Este registro no tiene movimientos.</div>`
                }

                
            }
             // Mostrar modal y overlay juntos
            movimientosDiv.classList.remove("hidden");
            movimientosDiv.classList.add("mostrar");
            overlay.classList.remove("hidden");
            overlay.classList.add("mostrar");
        });
}


window.eliminarRegistro = function (id){

    const recordId = id;

    fetch("/cajadiaria/eliminar/registro", {
        method: "DELETE",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({
            record: recordId,
        }),
    })
        .then((res) => res.json())
        .then((data) => {
            console.log(data)
            Swal.fire({
                imageWidth: 100,
                imageHeight: 100,
                imageUrl: "/images/alertas/advertencia.png",
                title: '¿Está seguro que desea eliminar este registro?',
                text: `Atencion: esta accion no tiene vuelta atras, se eliminara el registro junto con sus movimientos`,
                cancelButtonText: "CANCELAR",
                confirmButtonText: "CONFIRMAR",
                showCancelButton: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        text: 'Registro eliminado correctamente',
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
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                }
            })
        })

}

window.eliminarMovimiento = function(id){

    const movimiento = id; 


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
                imageWidth: 100,
                imageHeight: 100,
                imageUrl: "/images/alertas/advertencia.png",
                title: '¿Está seguro que desea eliminar este movimiento?',
                text: `Atencion: esta accion no tiene vuelta atras`,
                cancelButtonText: "CANCELAR",
                confirmButtonText: "CONFIRMAR",
                showCancelButton: true,
            }).then((result) => {
                if (result.isConfirmed) {
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
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                }
            })

        })
}


window.eliminarRegistroCero = function (){
    fetch("/cajadiaria/eliminar/registro/vacio", {
        method: "DELETE",
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
            if(data.mensaje == true){
                location.reload();
            }
        })
}