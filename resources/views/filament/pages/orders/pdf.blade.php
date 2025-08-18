<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Orden #{{ $order->id }}</title>
</head>

<body>

    <header>
        <img src="{{ public_path('images/logos/logo-cce.png') }}" alt="Logo">
        <div class="empresa">Club central entrerriano</div>
    </header>

    

    <main>
        <div class="titulo">Orden N°{{ $order->id }}</div>

        <div class="info-section">
            <table>
                <tr><th>Proveedor:</th><td>{{ $order->supplier->nombre ?? $order->supplier_id }}</td></tr>
                <tr><th>Fecha:</th><td>{{ $order->created_at->format('d/m/Y') }}</td></tr>
                <tr><th>CUIT:</th><td>{{ $order->supplier->cuit ?? $order->supplier_id }}</td></tr>
                <tr><th>Condicion IVA:</th><td>{{ $order->supplier->condition->nombre }}</td></tr>

            </table>
        </div>

        <div class="info-section">
            <h3>Detalle de productos</h3>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalCalculado = 0; @endphp
                    @foreach ($order->orderDetails as $index => $detail)
                        @php $subtotal = $detail->cantidad * $detail->precio_unitario; @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $detail->nombre_producto }}</td>
                            <td>{{ $detail->cantidad }}</td>
                           <td>${{ number_format($detail->precio_unitario, 2, ',', '.') }}</td>
                            <td>${{ number_format($subtotal, 2, ',', '.') }}</td>
                        </tr>
                        @php $totalCalculado += $subtotal; @endphp
                    @endforeach
                    <tr>
                        <td colspan="4" style="text-align: right; font-weight: bold;">Total</td>
                        <td>${{ number_format($order->total ?? $totalCalculado, 2,',','.' )}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

</body>

<style>
    @page {
        margin: 160px 40px 80px 40px;
    }

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
        color: #000;
    }

    header {
        position: fixed;
        top: -150px;
        left: 0;
        right: 0;
        text-align: center;
        padding: 30px 0 15px;
        border-bottom: 4px solid #f80c08d2;
        background-color: #fffdfde1;
        color: #f80c08d2;

    }

    header img {
        width: 100px;
        margin-bottom: 10px;
    }

  
    .empresa {
        font-size: 24px;
        font-weight: bold;
        color: rgb(17, 17, 17);
     
    }

    .info-section h3 {
    border-bottom: 2px solid #e0e0e0;
    padding-bottom: 5px;
    margin-bottom: 15px;
    
}

table {
    margin-top: 2rem;
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

/* Encabezados de la tabla */
table thead th {
    background-color: #f5f5f5; /
    color: #424242;
    font-weight: bold;
    text-align: left;
    padding: 12px 8px; 
    border: 1px solid #e0e0e0; 
}

/* Celdas de la tabla */
table tbody td {
    padding: 10px 8px;
    border: 1px solid #e0e0e0;
}

/* Filas impares */
table tbody tr:nth-child(odd) {
    background-color: #fafafa;
}

/* Estilo para el total */
.total-row {
    font-weight: bold;
    background-color: #e0e0e0;
    text-align: right !important; 
}

/* MAIN: Contenido principal del documento */
    main {
        margin-top: 80px; 
    }

    .titulo{
        text-align: center;
    }
</style>

</html>
