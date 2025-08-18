// Año actual
const anoActual = new Date().getFullYear();

// Mes actual (formato 2 dígitos)
const mesActual = String(new Date().getMonth() + 1).padStart(2, '0');

// Función para llenar un select con opciones y seleccionar el valor actual
function llenarSelectConJson(selectId, jsonUrl, valorActual) {
  const select = document.getElementById(selectId);
  fetch(jsonUrl)
    .then(response => response.json())
    .then(items => {
      items.forEach(item => {
        const option = document.createElement('option');
        option.value = item.value;
        option.textContent = item.label;
        if (option.value === String(valorActual)) {
          option.selected = true;
        }
        select.appendChild(option);
      });
    });
}

// Llenar selects para años
llenarSelectConJson('ano', '/json/anos.json', anoActual);
llenarSelectConJson('ano-individual', '/json/anos.json', anoActual);

// Llenar selects para meses
llenarSelectConJson('mes', '/json/meses.json', mesActual);
llenarSelectConJson('mes-individual', '/json/meses.json', mesActual);


document.getElementById('facturarBtn').addEventListener('click', async () => {
  const institutionId = document.getElementById('institutionSelect').value;
  const nombreInstituto = document.getElementById('institutionSelect').options[document.getElementById('institutionSelect').selectedIndex].text;
  const mes = document.getElementById('mes').value;
  const ano = document.getElementById('ano').value;
  const textoMes = document.getElementById('mes').options[document.getElementById('mes').selectedIndex].text;
  const fechaFactura = mes + "-" + ano;

  if (institutionId == false) {
    Swal.fire({
      text: 'Por favor seleccione una institucion.',
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

  Swal.fire({
    imageWidth: 100,
    imageHeight: 100,
    imageUrl: "/images/alertas/advertencia.png",
    title: 'Atención',
    text: `¿Está seguro de iniciar la facturación de ${textoMes} de ${ano} para todos los socios de ${nombreInstituto}?`,
    cancelButtonText: "CANCELAR",
    confirmButtonText: "CONFIRMAR",
    showCancelButton: true,
  }).then((result) => {
    if (result.isConfirmed) {
      fetch("/facturar/socio", {
        method: "POST",
        credentials: "same-origin",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content"),
        },
        body: JSON.stringify({
          institution_id: institutionId,
          fecha_factura: fechaFactura
        })
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.mensaje == true) {

            Swal.fire({
              title: 'Proceso de facturación iniciado',
              text: 'Las facturas están siendo generadas y pronto estarán disponibles para todos los socios.',
              showConfirmButton: true,
              confirmButtonText: 'Entendido',
              backdrop: false,
              allowOutsideClick: false,
              allowEscapeKey: false,
              imageWidth: 100,
              imageHeight: 100,
              imageUrl: "/images/alertas/check.png"
            });

          } else {
            Swal.fire({
              title: 'No existen facturas pendientes',
              text: `Todas las facturas correspondientes a ${textoMes} de ${ano} para los socios de ${nombreInstituto} ya han sido generadas.`,
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
    }
  })
});


window.buscarSocio = function () {
  let dni = document.getElementById('dni').value
  const overlay = document.getElementById('overlay')

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

  fetch("/facturar/buscarSocio", {
    method: "POST",
    credentials: "same-origin",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),
    },
    body: JSON.stringify({
      dni: dni
    }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.mensaje == true) {
        document.getElementById('titulo-individual').innerHTML =
          `<h2>Socio a facturar: ${data.integrante.nombre} ${data.integrante.apellido}</h2>
        <button id="cerrar-alerta" onclick="cerrarAlerta()"><i class="fa-solid fa-xmark"></i></button>
        <input type="hidden" value="${data.integrante.id}" id="socio_id">`

        console.log(data)


        if (data.integrante.sub_activities.length === 0) {
          const mensajeVacio = document.createElement("div");
          mensajeVacio.classList.add("carrito-vacio");

          const imagen = document.createElement("img");
          imagen.src = "/images/html/caja-vacia.png";
          imagen.classList.add("img-vacio");

          const texto = document.createElement("span");
          texto.textContent = "Este socio no esta inscripto a sub actividades";

          mensajeVacio.appendChild(imagen);
          mensajeVacio.appendChild(texto);
          document.getElementById('div-checkbox-sub-actividad').appendChild(mensajeVacio);
        } else {
          const subActividades = data.integrante.sub_activities
            .map(
              (s) => `<label class="label-checkbox"><input type="checkbox" value="${s.id}"> ${s.nombre}</label>`
            ).join("");

          document.getElementById('div-checkbox-sub-actividad').innerHTML = subActividades
        }

        const tipoSocio = data.integrante.member_types
          .map(
            (mt) => `<label class="label-checkbox"><input type="checkbox" value="${mt.id}"> ${mt.nombre}</label>`
          ).join("");

        document.getElementById('div-checkbox-tipo-socio').innerHTML = tipoSocio


        if (data.familiares.length === 0 && !data.integrante.responsable) {
          document.getElementById('body').innerHTML = `<tr><td style="text-align: center">Este socio no esta en ningun grupo familiar</td></tr>`
        } else {
          const familiares = data.familiares
            .map(
              (f) => `<tr><td>${f.nombre} ${f.apellido}</td></tr>`
            ).join("");

          document.getElementById('body').innerHTML = `<tr><td>${data.integrante.responsable.nombre} ${data.integrante.responsable.apellido}</td></tr>`
          document.getElementById('body').innerHTML += familiares
        }

        overlay.style.display = "flex";

      } else {
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
    })
}

window.cerrarAlerta = function () {
  const overlay = document.getElementById("overlay");
  overlay.style.display = "none";
};



window.enviarSeleccion = function () {
  // Checkbox sub-actividades
  const subActividadCheckboxes = document.querySelectorAll('#chechboxs-sub-actividad input[type="checkbox"]:checked');
  const subActividadesSeleccionadas = Array.from(subActividadCheckboxes).map(cb => cb.value);

  // Checkbox tipos de socio
  const socioCheckboxes = document.querySelectorAll('#chechboxs-socio input[type="checkbox"]:checked');
  const sociosSeleccionados = Array.from(socioCheckboxes).map(cb => cb.value);

  const socio = document.getElementById('socio_id').value;
  const mes = document.getElementById('mes-individual').value;
  const ano = document.getElementById('ano-individual').value;
  const textoMes = document.getElementById('mes-individual').options[document.getElementById('mes-individual').selectedIndex].text;
  const fechaFactura = mes + "-" + ano;

  fetch("/facturar/socio/individual", {
    method: "POST",
    credentials: "same-origin",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),
    },
    body: JSON.stringify({
      subActividades: subActividadesSeleccionadas,
      tiposDeSocio: sociosSeleccionados,
      fecha: fechaFactura,
      socio: socio
    }),
  })
    .then((res) => res.json())
    .then((data) => {

      console.log(data)

      let subActividades = data.facturas_existentes.sub_actividades
      let tiposSocio = data.facturas_existentes.tipos_socio;

      if (data.mensaje == true && subActividades.length === 0 && tiposSocio.length === 0) {
        Swal.fire({
          text: 'Se han generado correctamente todas las facturas correspondientes a este socio.',
          imageUrl: "/images/alertas/check.png",
          confirmButtonText: 'Entendido',
          confirmButtonColor: '#3085d6',
          backdrop: false,
          allowOutsideClick: false,
          allowEscapeKey: false,
          imageWidth: 100,
          imageHeight: 100,
        });

        return
      }

      if (data.mensaje == true && (subActividades.length != 0 || tiposSocio.length != 0)) {

        const combinados = [
          ...subActividades.filter(item => item.trim() !== ''),
          ...tiposSocio.filter(item => item.trim() !== '')
        ];

        const nombresNoFacturados = combinados.length > 0
          ? combinados.join(', ')
          : 'Ninguna factura evitada';



        Swal.fire({
          title: 'Facturación completada',
          text: `Se han generado correctamente las facturas correspondientes a este socio. Sin embargo, algunas facturas fueron omitidas debido a que ya existían: ${nombresNoFacturados}.`,
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

      if (data.mensaje == false) {
        Swal.fire({
          title: 'Facturación no realizada',
          text: 'Todas las facturas correspondientes a la fecha seleccionada ya se encuentran registradas, por lo que no se generaron duplicados.',
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


}