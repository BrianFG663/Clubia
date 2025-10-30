document.getElementById('actividad').addEventListener('change', function () {
    let actividad = document.getElementById('actividad').value;
    console.log(actividad);

    fetch("/inscripcion/subactividad", {
        method: "POST",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({ actividad: actividad }),
    })
        .then((res) => res.json())
        .then((data) => {
            if (data.mensaje == true) { 
                const select = document.getElementById('sub-actividad');
                select.innerHTML = '';

                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = '--Seleccione una sub-actividad--';
                defaultOption.disabled = true;
                defaultOption.selected = true;
                select.appendChild(defaultOption);

                data.subActividades.forEach(sub => {
                    const option = document.createElement('option');
                    option.value = sub.id;
                    option.textContent = sub.nombre;
                    select.appendChild(option);
                });
            }

            if (data.mensaje == false) { 
                const select = document.getElementById('sub-actividad');
                // Limpiar opciones actuales
                select.innerHTML = '';

                // Opción por defecto
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = '--Esta actividad no tiene sub-actividades--';
                defaultOption.disabled = true;
                defaultOption.selected = true;
                select.appendChild(defaultOption);
            }
        })

});



window.inscribirSocio = function () {
    const actividad = document.getElementById('actividad').value;
    const subActividad = document.getElementById('sub-actividad').value;
    const dni = document.getElementById('dni').value;

    if (actividad == false || subActividad == false || dni == '') {
        Swal.fire({
            text: "Por favor completar el formulario",
            showConfirmButton: false,
            timer: 2000,
            imageWidth: 100,
            imageHeight: 100,
            imageUrl: "/images/alertas/advertencia.png",
        });
    }

    console.log(actividad, subActividad, dni)


    fetch("/inscripcion/validar", {
        method: "POST",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({
            subActividad: subActividad,
            dni: dni
        }),
    })
        .then((res) => res.json())
        .then((data) => {
            if (data.mensaje == true && actividad != false && subActividad != false) {
                console.log(data)
                Swal.fire({
                    imageWidth: 100,
                    imageHeight: 100,
                    imageUrl: "/images/html/inscribir-usuario.png",
                    text: `¿Desea inscribir al socio ${data.integrante.nombre} ${data.integrante.apellido} a la sub actividad "${data.subActividad.nombre}"?`,
                    showCancelButton: true,
                    cancelButtonText: "CANCELAR",
                    confirmButtonText: "CONFIRMAR",
                    confirmButtonColor: "#e74938",
                    cancelButtonColor: "#ffd087",
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            text: "Socio inscripto correctamente",
                            showConfirmButton: false,
                            timer: 2000,
                            imageWidth: 100,
                            imageHeight: 100,
                            imageUrl: "/images/alertas/check.png",
                        });
                        setTimeout(() => {
                            document.getElementById('formulario').submit();
                        }, 2100);
                    }
                })
            }

            if (data.mensaje == false && actividad != false && subActividad != false && dni != '') {
                console.log(data)
                Swal.fire({
                    text: "No hay un socio registrado con ese dni",
                    showConfirmButton: false,
                    timer: 2000,
                    imageWidth: 100,
                    imageHeight: 100,
                    imageUrl: "/images/alertas/advertencia.png",
                });
            }
            
        })
}
