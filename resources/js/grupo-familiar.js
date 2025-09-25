window.buscarGrupoFamiliar = function () {
    const valor = document.getElementById('filtroNombre').value.trim();

    if (valor === '') {
        window.location.reload();
        return;
    }

    fetch('/grupo-familiar/buscar', {
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
        const cuerpoTabla = document.getElementById('tablaCuerpo');
        
        if (data.mensaje === false) {
            cuerpoTabla.innerHTML = `
                <tr>
                    <td colspan="6" style="text-align:center;">${data.message ?? 'No se encontraron familiares.'}</td>
                </tr>`;
            return;
        }

        if (!data.jefes || !data.jefes.length) {
            cuerpoTabla.innerHTML = `
                <tr>
                    <td colspan="6" style="text-align:center;">No se encontraron familiares.</td>
                </tr>`;
            return;
        }

        const filasHTML = data.jefes.map(jefe => `
            <tr>
                <td class="nombre">${jefe.nombre} ${jefe.apellido}</td>
                <td>${jefe.dni ?? '-'}</td>
                <td class="email">${jefe.email ?? '-'}</td>
                <td>${jefe.telefono ?? '-'}</td>
                <td class="btn">
                    <button onclick="detalleFamilia(${jefe.id})">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </td>
                <td class="btn">
                    <button onclick="agregarIntegrante(${jefe.id})">
                        <i class="fa-solid fa-user-plus"></i>
                    </button>
                </td>
            </tr>
        `).join('');

        cuerpoTabla.innerHTML = filasHTML;
    })
    .catch(error => {
        console.error("Error al buscar grupo familiar:", error);
    });
};


function calcularEdad(fechaNacimiento) {
    const hoy = new Date();
    const nacimiento = new Date(fechaNacimiento);
    let edad = hoy.getFullYear() - nacimiento.getFullYear();
    const mes = hoy.getMonth() - nacimiento.getMonth();

    if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
        edad--;
    }

    return edad;
}

window.detalleFamilia = function (id) {
    const familiaresDiv = document.querySelector(".familiares");
    const overlay = document.getElementById("overlay");
    const jefeId = id;

    fetch("/detalles-familiares", {
        method: "POST",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({ id: jefeId }),
    })
        .then((res) => res.json())
        .then((data) => {
            if (data.mensaje == true) {
                const familiares = data.familia
                    .map(
                        (f) => `
                <tr>
                    <td class="nombre">${f.nombre} ${f.apellido}</td>
                    <td>${f.dni}</td>
                    <td class="email">${f.email}</td>
                    <td>${f.telefono}</td>
                    <td class="email">${f.direccion}</td>
                    <td>${calcularEdad(f.fecha_nacimiento)}</td>
                    <td class="btn" style="text-align:center;"><button onclick="eliminarIntegrante(${f.id
                            })"><i class="fas fa-trash"></i></button></td>
                </tr>`
                    )
                    .join("");

                document.getElementById("contenedor-informacion").innerHTML = `
                    <h2 class="titulo-informacion" style="margin-left: 1rem; margin-bottom: 1rem;">Detalles miembros familia ${data.jefe.apellido}</h2>
                    <div class="informacion">
                        <div>
                            <div class="alto-inf"></div>
                            <table class="tabla table-auto w-full">
                                <thead>
                                    <tr>
                                        <th>Nombre completo</th>
                                        <th>DNI</th>
                                        <th>E-mail</th>
                                        <th>Telefono</th>
                                        <th>Direccion</th>
                                        <th>Edad
                                        </th>
                                        <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${familiares}
                                </tbody>
                            </table>
                            <div class="bajo-inf"></div>
                        </div>
                    </div>`;
            } else {
                document.getElementById(
                    "contenedor-informacion"
                ).innerHTML = `<div class="mensaje" style="text-align:center; margin: 1.5rem" >No hay familiares inscriptos en este grupo.</div>`;
            }
             // Mostrar modal y overlay juntos
            familiaresDiv.classList.remove("hidden");
            familiaresDiv.classList.add("mostrar");
            overlay.classList.remove("hidden");
            overlay.classList.add("mostrar");
        });

       
};

window.eliminarIntegrante = function (id) {
    const familiarId = id;

    Swal.fire({
        imageWidth: 100,
        imageHeight: 100,
        imageUrl: "/images/alertas/advertencia.png",
        text: "¿Desea eliminar este integrante del grupo familiar?",
        cancelButtonText: "CANCELAR",
        confirmButtonText: "CONFIRMAR",
        confirmButtonColor: "#e74938",
        cancelButtonColor: "#ffd087",
    }).then((result) => {
        if (result.isConfirmed) {
            console.log(id);
            fetch("/detalles-familiares/eliminar-integrante", {
                method: "PATCH",
                credentials: "same-origin",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({ id: familiarId }),
            })
                .then((res) => res.json())
                .then((data) => {
                    if (data.mensaje == true) {
                        Swal.fire({
                            text: "Integrante eliminado del grupo familiar",
                            showConfirmButton: false,
                            timer: 2000,
                            backdrop: false,
                            allowOutsideClick: false,
                            imageWidth: 100,
                            imageHeight: 100,
                            imageUrl: "/images/alertas/check.png",
                        });
                        setTimeout(() => {
                            detalleFamilia(data.responsable);
                        }, 2000);
                    }
                });
        }
    });
};

window.agregarIntegrante = function (responsable_id) {
    Swal.fire({
        text: "Buscar socio para agregar al grupo familiar",
        input: "text",
        inputPlaceholder: "Ingrese numero de documento",
        showCancelButton: true,
        confirmButtonColor: "#ff7c019a",
        confirmButtonText: "Buscar",
        cancelButtonColor: "#ff2b06",
        cancelButtonText: "Cancelar",
        didOpen: () => {
            const input = Swal.getInput();
            input.type = "number";
            input.min = 0;
        },
        inputValidator: (value) => {
            if (!value) {
                return "Debe ingresar un número.";
            }
        },
    }).then((result) => {
        const dni = result.value;
        fetch("/detalles-familiares/buscar-integrante", {
            method: "POST",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({ dni: dni }),
        })
            .then((res) => res.json())
            .then((data) => {
                console.log(data);
                if (data.mensaje == true) {
                    Swal.fire({
                        imageWidth: 100,
                        imageHeight: 100,
                        allowOutsideClick: true,
                        imageUrl: "/images/html/lupa.png",
                        title: "Socio encontrado",
                        text: `¿Desea agregar a ${data.integrante.nombre} ${data.integrante.apellido} al grupo familiar?`,
                        showCancelButton: true,
                        cancelButtonText: "CANCELAR",
                        confirmButtonText: "CONFIRMAR",
                        confirmButtonColor: "#e74938",
                        cancelButtonColor: "#ffd087",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch("/detalles-familiares/agregar-integrante", {
                                method: "PATCH",
                                credentials: "same-origin",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": document
                                        .querySelector('meta[name="csrf-token"]')
                                        .getAttribute("content"),
                                },
                                body: JSON.stringify({
                                    dni: dni,
                                    responsable_id: responsable_id,
                                }),
                            })
                                .then((res) => res.json())
                                .then((data) => {
                                    console.log(data);
                                    if (data.mensaje == true) {
                                        Swal.fire({
                                            text: "Integrante agregado correctamenter al grupo grupo familiar",
                                            showConfirmButton: true,
                                            confirmButtonText: "ACEPTAR",
                                            imageWidth: 100,
                                            imageHeight: 100,
                                            imageUrl: "/images/alertas/check.png",
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                location.reload();
                                            }
                                        })
                                    }
                                });
                        }

                    });
                }
                if (data.mensaje == false && dni != undefined) {
                    console.log(dni)
                    Swal.fire({
                        text: "El documento ingresado no corresponde a un socio",
                        showConfirmButton: true,
                        confirmButtonText: "ACEPTAR",
                        imageWidth: 100,
                        imageHeight: 100,
                        imageUrl: "/images/alertas/advertencia.png",
                    });
                }

            });
    });
};

document.addEventListener("DOMContentLoaded", () => {
    const cerrarBtn = document.getElementById("cerrar-alerta");
    const familiaresDiv = document.querySelector(".familiares");
    const overlay = document.getElementById("overlay");

    if (cerrarBtn && familiaresDiv && overlay) {
        function cerrarModal() {
            familiaresDiv.classList.remove("mostrar");
            familiaresDiv.classList.add("hidden");
            overlay.classList.remove("mostrar");
            overlay.classList.add("hidden");
            document.getElementById("contenedor-informacion").innerHTML = "";
        }

        cerrarBtn.addEventListener("click", cerrarModal);
        overlay.addEventListener("click", cerrarModal);
    }
});

