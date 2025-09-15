const select = document.getElementById('institucionSocial');
select.addEventListener('change', function () {

    const institucion = this.value;

    fetch("/parametros/buscar", {
        method: "POST",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({ institucion:institucion  })
    })
    .then((res) => res.json())
    .then((data) => {
        console.log(data)
        document.getElementById("facturasSocial").disabled = false;
        const valor = data.parametros.umbral_facturas_cuotas_impagas;
        const texto = (valor === null) ? 'Desactivado' : valor;

        document.getElementById('facturasSocial').innerHTML = 
            `<option value="${valor === null ? 'null' : valor}" disabled selected hidden>${texto}</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="null">Desactivado</option>`;

    })
})


const selectActividad = document.getElementById('institucionActividad');
selectActividad.addEventListener('change', function () {

    const institucion = this.value;

    fetch("/parametros/buscar", {
        method: "POST",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({ institucion:institucion  })
    })
    .then((res) => res.json())
    .then((data) => {
        console.log(data)
        document.getElementById("facturasActividad").disabled = false;
        const valor = data.parametros.umbral_facturas_subactividades_impagas;
        const texto = (valor === null) ? 'Desactivado' : valor;

        document.getElementById('facturasActividad').innerHTML = 
            `<option value="${valor === null ? 'null' : valor}" disabled selected hidden>${texto}</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="null">Desactivado</option>`;

            })
})



window.parametroCuota = function () {

    const institucion = document.getElementById('institucionSocial').value
    const parametro = document.getElementById('facturasSocial').value
    console.log('institucion ' + institucion)
    console.log('parametro ' + parametro)

    if(institucion == false){
        Swal.fire({
            imageWidth: 100,
            imageHeight: 100,
            imageUrl: "/images/alertas/advertencia.png",
            text: `Debe seleccionar una institucion primero`,
            showCancelButton: false,
            confirmButtonText: "ACEPTAR",
            confirmButtonColor: "#e74938",
            cancelButtonColor: "#ffd087",
        })

        return
    }


    Swal.fire({
        imageWidth: 100,
        imageHeight: 100,
        imageUrl: "/images/alertas/advertencia.png",
        text: `¿Desea cambiar el parametro de la cuota social?`,
        showCancelButton: true,
        cancelButtonText: "CANCELAR",
        confirmButtonText: "CONFIRMAR",
        confirmButtonColor: "#e74938",
        cancelButtonColor: "#ffd087",
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("/parametro/cuota/cambio", {
                method: "POST",
                credentials: "same-origin",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({
                    parametro: parametro,
                    institucion: institucion
                })
            })
            .then((res) => res.json())
            .then((data) => {
                if(data.mensaje == true){
                    Swal.fire({
                        text: 'Se ha cambiado exitosamente el parametro de la cuota social.',
                        showConfirmButton: true,
                        confirmButtonText: 'Entendido',
                        backdrop: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        imageWidth: 100,
                        imageHeight: 100,
                        imageUrl: "/images/alertas/check.png"
                    });

                    return
                } else {
                    Swal.fire({
                        text: 'Error.',
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
            })
        }
    })
}


window.parametroSubActividad = function () {

    const institucion = document.getElementById('institucionActividad').value
    const parametro = document.getElementById('facturasActividad').value
    console.log('institucion ' + institucion)
    console.log('parametro ' + parametro)


    if(institucion == false){
        Swal.fire({
            imageWidth: 100,
            imageHeight: 100,
            imageUrl: "/images/alertas/advertencia.png",
            text: `Debe seleccionar una institucion primero`,
            showCancelButton: false,
            confirmButtonText: "ACEPTAR",
            confirmButtonColor: "#e74938",
            cancelButtonColor: "#ffd087",
        })

        return
    }

    


    Swal.fire({
        imageWidth: 100,
        imageHeight: 100,
        imageUrl: "/images/alertas/advertencia.png",
        text: `¿Desea cambiar el parametro de la cuota sub-actividad?`,
        showCancelButton: true,
        cancelButtonText: "CANCELAR",
        confirmButtonText: "CONFIRMAR",
        confirmButtonColor: "#e74938",
        cancelButtonColor: "#ffd087",
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("/parametro/actividad/cambio", {
                method: "POST",
                credentials: "same-origin",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({
                    parametro: parametro,
                    institucion: institucion
                })
            })
            .then((res) => res.json())
            .then((data) => {
                if(data.mensaje == true){
                    Swal.fire({
                        text: 'Se ha cambiado exitosamente el parametro de la cuota sub-actividad.',
                        showConfirmButton: true,
                        confirmButtonText: 'Entendido',
                        backdrop: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        imageWidth: 100,
                        imageHeight: 100,
                        imageUrl: "/images/alertas/check.png"
                    });

                    return
                } else {
                    Swal.fire({
                        text: 'Error.',
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
            })
        }

    })


}