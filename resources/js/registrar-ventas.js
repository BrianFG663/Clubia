const input = document.getElementById("buscar");
const resultados = document.getElementById("resultados");
const carrito = document.getElementById("carrito");
const enviarBtn = document.getElementById("enviar-carrito");

var contador = 0;
let timeout = null;
let productosCarrito = [];
var total = 0;

input.addEventListener("input", function () {
    clearTimeout(timeout);

    timeout = setTimeout(() => {
        const query = this.value.trim();

        if (query.length >= 2) {
            fetch(`/buscar-productos?query=${query}`)
                .then((response) => response.json())
                .then((data) => {
                    resultados.innerHTML = "";

                    data.slice(0, 3).forEach((producto) => {
                        const contenedor = document.createElement("div");
                        contenedor.className =
                            "articulo";

                        const info = document.createElement("div");

                        const titulo = document.createElement("h3");
                        titulo.className =
                            "titulo-articulo";
                        titulo.textContent = producto.nombre;

                        const descripcion = document.createElement("p");
                        descripcion.className =
                            "descripcion-articulo";
                        descripcion.textContent =
                            producto.descripcion ?? "Sin descripcion";

                        const precio = document.createElement("span");
                        precio.className =
                            "precio-articulo";
                        precio.textContent =
                            producto.precio !== undefined
                                ? `$${parseFloat(producto.precio)}`
                                : "Precio no disponible";

                        info.appendChild(titulo);
                        info.appendChild(descripcion);

                        contenedor.appendChild(info);
                        contenedor.appendChild(precio);

                        contenedor.dataset.id = producto.id;
                        contenedor.style.cursor = "pointer";
                        contenedor.addEventListener("click", () =>
                            agregarAlCarrito(producto)
                        );

                        resultados.appendChild(contenedor);
                    });
                });
        } else {
            resultados.innerHTML = "";
        }
    }, 300);
});

// Función para agregar un producto al carrito
function agregarAlCarrito(producto) {
    const encontrado = productosCarrito.find((p) => p.id === producto.id);
    if (encontrado) {
        encontrado.cantidad++;
    } else {
        productosCarrito.push({
            id: producto.id,
            nombre: producto.nombre,
            cantidad: 1,
            precio: producto.precio,
            descripcion: producto.descripcion,
        });
    }

    console.log("Productos en carrito después de agregar:", productosCarrito); // Mostrar array actualizado
    renderizarCarrito();
    resultados.innerHTML = "";
    input.value = "";
}

// Funciones para sumar, restar y eliminar productos del carrito

function sumarCantidad(id) {
    const producto = productosCarrito.find((p) => p.id === id);
    if (producto) {
        producto.cantidad++;
        console.log("Productos en carrito después de sumar:", productosCarrito); // Mostrar array actualizado
        renderizarCarrito();
    }
}

function restarCantidad(id) {
    const producto = productosCarrito.find((p) => p.id === id);
    if (producto) {
        if (producto.cantidad > 1) {
            producto.cantidad--;
        } else {
            productosCarrito = productosCarrito.filter((p) => p.id !== id);
        }
        console.log(
            "Productos en carrito después de restar o eliminar:",
            productosCarrito
        ); // Mostrar array actualizado
        renderizarCarrito();
    }
}

function eliminarProducto(id) {
    productosCarrito = productosCarrito.filter((p) => p.id !== id);
    console.log("Productos en carrito después de eliminar:", productosCarrito); // Mostrar array actualizado
    renderizarCarrito();
}

//

// Función para renderizar el carrito de compras

function renderizarCarrito() {
    carrito.innerHTML = "";
    let totalCalculado = 0;

    if (productosCarrito.length === 0) {
        const mensajeVacio = document.createElement("div");
        mensajeVacio.classList.add("carrito-vacio");

        const imagen = document.createElement("img");
        imagen.src = "/images/html/caja-vacia.png";
        imagen.classList.add("img-vacio");

        const texto = document.createElement("span");
        texto.textContent = "Aun no se seleccionaron productos";

        mensajeVacio.appendChild(imagen);
        mensajeVacio.appendChild(texto);
        carrito.appendChild(mensajeVacio);

        // También limpiamos el total
        const totalDiv = document.getElementById("total-carrito");
        totalDiv.innerHTML = "<span>TOTAL: $00.00</span>";
        return;
    }

    productosCarrito.forEach((producto, index) => {
        console.log(producto);
        totalCalculado += producto.precio * producto.cantidad;
        contador++;
        const li = document.createElement("div");
        li.innerHTML = `
            <div class="articulos">
                <h3 class="titulo-articulo">
                    ${producto.nombre}
                </h3>
                <p class="descripcion-articulo">
                    ${producto.descripcion || "Sin descripción"}
                </p>
                <div class="acciones-articulo">
                    <span class="precio-articulo font-bold text-primary-400">
                        $${producto.precio}
                    </span>
                    <div class="botones-articulo flex items-center gap-2">
                        <div class="precio">TOTAL: $${(parseFloat(producto.precio) * producto.cantidad).toFixed(2)}</div>
                        <button class="btn-quitar" data-id="${producto.id}">−</button>
                        <span class="cantidad-articulo">
                            ${producto.cantidad}
                        </span>
                        <button class="btn-agregar" data-id="${producto.id}">+</button>
                        <button class="btn-eliminar" data-id="${producto.id}"><i class="fas fa-trash"></i></button>                   
                    </div>
                </div>
            </div>

        `;
        carrito.appendChild(li);
    });

    // Mostrar el total en pantalla
    const totalDiv = document.getElementById("total-carrito");
    totalDiv.innerHTML = `<span>TOTAL: $${totalCalculado.toFixed(2)}</span>`;

    // Actualizar la variable total
    total = totalCalculado;

    // Eventos de botones
    document.querySelectorAll(".btn-agregar").forEach((button) => {
        button.addEventListener("click", (e) => {
            const id = e.currentTarget.dataset.id;
            sumarCantidad(parseInt(id));
        });
    });

    document.querySelectorAll(".btn-quitar").forEach((button) => {
        button.addEventListener("click", (e) => {
            const id = e.currentTarget.dataset.id;
            restarCantidad(parseInt(id));
        });
    });

    document.querySelectorAll(".btn-eliminar").forEach((button) => {
        button.addEventListener("click", (e) => {
            const id = e.currentTarget.dataset.id;
            Swal.fire({
                imageWidth: 100,
                imageHeight: 100,
                imageUrl: "/images/alertas/advertencia.png",
                title: "¿Quitar este producto de la venta?",
                showCancelButton: true,
                cancelButtonText: "CANCELAR",
                confirmButtonText: "CONFIRMAR",
                confirmButtonColor: "#e74938",
                cancelButtonColor: "#ffd087",
            }).then((result) => {
                if (result.isConfirmed) {
                    eliminarProducto(parseInt(id));
                }
            });
        });
    });

    console.log(
        "Estado final del carrito después de renderizar:",
        productosCarrito
    );
}

// Evento para el botón de enviar carrito

enviarBtn.addEventListener("click", function () {
    if (productosCarrito.length === 0) {
        Swal.fire({
            title: "Carrito de venta vacio",
            text: "¡Agregue productos para continuar!",
            showConfirmButton: true,
            confirmButtonText: "ACEPTAR",
            imageWidth: 100,
            imageHeight: 100,
            imageUrl: "/images/html/carro-vacio.png",
        });
        return;
    }

    Swal.fire({
        imageWidth: 100,
        imageHeight: 100,
        imageUrl: "/images/html/venta.png",
        title: "¿Desea registrar esta venta?",
        showCancelButton: true,
        cancelButtonText: "CANCELAR",
        confirmButtonText: "REGISTRAR",
    }).then((result) => {
        if (result.isConfirmed) {
            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            fetch("/venta-registrada", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({
                    total: total,
                    productos: productosCarrito,
                }),
            })
                .then((response) => {
                    console.log(response);
                    return response.json();
                })
                .then((data) => {
                    Swal.fire({
                        title: "Venta registrada",
                        showConfirmButton: false,
                        timer: 1500,
                        imageWidth: 100,
                        imageHeight: 100,
                        imageUrl: "/images/alertas/check.png",
                    });
                    productosCarrito = [];
                    renderizarCarrito();
                })
                .catch((error) => {
                    console.error("Error registrando la venta:", error);
                    alert("Error al registrar la venta.");
                });
        }
    });
});
