<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura {{ $factura->numero }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; color: #333; }
        .header { text-align: right; margin-bottom: 30px; }
        .titulo { font-size: 24px; font-weight: bold; color: #4f46e5; }
        .seccion { margin-bottom: 20px; }
        .tabla { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .tabla th { background: #f3f4f6; text-align: left; padding: 10px; border-bottom: 2px solid #e5e7eb; }
        .tabla td { padding: 10px; border-bottom: 1px solid #e5e7eb; }
        .totales { margin-top: 30px; float: right; width: 40%; }
        .fila-total { display: flex; justify-content: space-between; padding: 5px 0; }
        .total-final { font-size: 18px; font-weight: bold; color: #4f46e5; border-top: 2px solid #4f46e5; margin-top: 10px; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="titulo">FACTURA</div>
        <div>Folio: {{ $factura->numero }}</div>
        <div>Fecha: {{ $factura->fecha_emision }}</div>
    </div>

    <div class="seccion">
        <strong>EMISOR:</strong><br>
        {{ auth()->user()->name }}<br>
        {{ auth()->user()->email }}
    </div>

    <div class="seccion">
        <strong>CLIENTE:</strong><br>
        {{ $factura->cliente->nombre }}<br>
        RUT: {{ $factura->cliente->rut }}<br>
        Giro: {{ $factura->cliente->giro }}
    </div>

    <table class="tabla">
        <thead>
            <tr>
                <th>Descripción</th>
                <th>Cant.</th>
                <th>P. Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($factura->detalles as $detalle)
            <tr>
                <td>{{ $detalle->nombre_producto }}</td>
                <td>{{ $detalle->cantidad }}</td>
                <td>${{ number_format($detalle->precio_unitario, 0, ',', '.') }}</td>
                <td>${{ number_format($detalle->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totales">
        <div class="fila-total">Neto: ${{ number_format($factura->subtotal, 0, ',', '.') }}</div>
        <div class="fila-total">IVA (19%): ${{ number_format($factura->iva, 0, ',', '.') }}</div>
        <div class="total-final">TOTAL: ${{ number_format($factura->total, 0, ',', '.') }}</div>
    </div>
</body>
</html>
