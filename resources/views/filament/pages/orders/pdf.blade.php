<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Orden #{{ $order->id }}</title>
</head>

<body>

    <header>
        <img src="{{ public_path('images/excavador.png') }}" alt="Logo">
        <div class="empresa">Club central entrerriano</div>
    </header>

    <footer>
        Página <span class="page"></span> - Generado el {{ \Carbon\Carbon::now()->format('d/m/Y') }}
    </footer>

    <main>
        <div class="titulo">Orden N°{{ $order->id }}</div>

        <div class="info-section">
            <table>
                <tr><th>Proveedor:</th><td>{{ $order->supplier->nombre ?? $order->supplier_id }}</td></tr>
                <tr><th>Fecha:</th><td>{{ $order->created_at->format('d/m/Y') }}</td></tr>
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
                            <td>${{ number_format($detail->precio_unitario, 2) }}</td>
                            <td>${{ number_format($subtotal, 2) }}</td>
                        </tr>
                        @php $totalCalculado += $subtotal; @endphp
                    @endforeach
                    <tr>
                        <td colspan="4" style="text-align: right; font-weight: bold;">Total</td>
                        <td>${{ number_format($order->total ?? $totalCalculado, 2) }}</td>
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
        top: -110px;
        left: 0;
        right: 0;
        text-align: center;
        padding: 30px 0 15px;
        border-bottom: 4px solid #f80c08d2;
        border-top: 4px solid #f80c08d2;
        background-color: #000;
        color: #f80c08d2;
    }

    header img {
        width: 100px;
        margin-bottom: 10px;
    }

    .empresa {
        font-size: 24px;
        font-weight: bold;
        color: white;
    }

    .dominio {
        font-size: 14px;
        color: white;
    }

    footer {
        position: fixed;
        bottom: -50px;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 11px;
        color: #555;
        border-top: 1px solid #ccc;
        padding-top: 5px;
    }

    footer .page::after {
        content: counter(page);
    }

    .titulo {
        text-align: center;
        font-size: 20px;
        margin: 60px 0 25px;
        text-transform: uppercase;
        color: #000;
        font-weight: bold;
    }

    .info-section {
        margin-bottom: 25px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
    }

    table th,
    table td {
        border: 1px solid #ddd;
        padding: 8px;
        vertical-align: top;
    }

    table th {
        background-color: #7a7871;
        color: #000;
        font-weight: bold;
    }

    h3 {
        margin-bottom: 5px;
        color: #000;
    }
</style>

</html>
