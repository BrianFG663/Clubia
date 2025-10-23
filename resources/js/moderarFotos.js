window.socios = function(){
    document.getElementById('contenedor').innerHTML = ``;
    fetch("/traer/socios/fotos", {
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


            if(data.socios.length !== 0){
                console.log(data.socios);
                const contenedor = document.getElementById("contenedor");

                data.socios.forEach((socio) => {
                    socio.media.forEach((media) => {
                        const nombreSinExtension = media.file_name.substring(0, media.file_name.lastIndexOf('.'));
                        const url = `/storage/${media.id}/conversions/${nombreSinExtension}-profile.jpg`;

                        const html = `
                            <div class="carnet">
                                <div class="contenedor-imagen">
                                    <img class="imagen-carnet" src="${url}" alt="Foto de ${socio.nombre} ${socio.apellido}">
                                </div>
                                <div class="contenedor-inf">
                                    <div class="titulo-inf">Socio: ${socio.nombre} ${socio.apellido}</div>
                                    <div class="numero-inf">Nº socio: #${socio.id}</div>
                                    <div class="botones-inf">
                                        <div class="titulo-botones">¿Foto de perfil valida?</div>
                                        <div class="botones"><button class="boton-check" title="Aceptar foto" onclick="aceptarFoto(${socio.id})"><i class="fas fa-check text-green-600"></i></button> <button class="boton-equis" title="Rechazar foto" onclick="eliminarFoto(${socio.id})"><i class="fas fa-times text-red-600"></i></button></div>
                                    </div>
                                </div>
                            </div>
                        `;
                        contenedor.insertAdjacentHTML("beforeend", html);
                    });
                });
            }else{
                const div = document.createElement("div");
                div.classList.add("carrito-vacio");

                const img = document.createElement("img");
                img.src = "/images/html/caja-vacia.png";
                img.classList.add("img-vacio");

                const span = document.createElement("span");
                span.textContent = 'Estas al dia con la validacion de fotos.';

                div.appendChild(img);
                div.appendChild(span);

                document.getElementById('contenedor').appendChild(div)
            }
            
        })
}

window.onload = function () {
    socios();
};

function mostrarMensaje(tipo) {
    const mensaje = document.getElementById('mensaje');
    const icono = document.getElementById('icono-mensaje');
    const texto = document.getElementById('texto-mensaje');

    if (tipo === 'aprobado') {
        icono.src = '/images/alertas/check.png';
        texto.textContent = 'Imagen aprobada y registrada correctamente.';
    } else if (tipo === 'rechazado') {
        icono.src = '/images/alertas/equis.png';
        texto.textContent = 'Imagen eliminada, el socio será notificado.';
    }

    mensaje.classList.add('visible');

    setTimeout(() => {
        mensaje.classList.remove('visible');
    }, 3000);
}


window.aceptarFoto = function(idSocio){

    const id = idSocio;
    console.log(id);

    mostrarMensaje('aprobado');

    fetch("/aceptar/foto/socio", {
        method: "POST",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({ id: id }),
    })
        .then((res) => res.json())
        .then((data) => {

            console.log(data.mensaje);

            if(data.mensaje == true){
                socios();
            }
        })
}


window.eliminarFoto = function(idSocio){

    const id = idSocio;
    mostrarMensaje('rechazado');

    console.log(id);

    fetch("/eliminar/foto/socio", {
        method: "delete",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({ id: id }),
    })
        .then((res) => res.json())
        .then((data) => {

            console.log(data.mensaje);

            if(data.mensaje == true){
                socios();
            }
        })
}