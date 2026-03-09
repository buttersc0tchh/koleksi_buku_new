<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tag Harga</title>
    <style>
        @page {
            margin: 22mm 4.7mm 5mm 4.7mm;
            size: A4 portrait;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; }

        table.label-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        td.label-cell {
            width: 20%;
            height: 30mm;
            vertical-align: middle;
            text-align: center;
        }

        .label-id {
            font-size: 5pt;
            color: #888;
            display: block;
            margin-bottom: 0.3mm;
        }

        .label-nama {
            font-size: 7pt;
            font-weight: bold;
            color: #000;
            display: block;
            line-height: 1.2;
            margin-bottom: 0.5mm;
        }

        .label-harga {
            font-size: 9.5pt;
            font-weight: bold;
            color: #c00000;
            display: block;
            margin-bottom: 0.3mm;
        }

        .label-satuan {
            font-size: 6pt;
            color: #555;
            display: block;
        }
    </style>
</head>
<body>

@php
    $totalSlot   = 40;
    $kolom       = 5;
    $barangIdx   = 0;
    $totalBarang = count($barangs);
@endphp

<table class="label-table">
    @for ($i = 0; $i < $totalSlot; $i++)

        @if ($i % $kolom === 0)
            <tr>
        @endif

        @if ($i < $startPos || $barangIdx >= $totalBarang)
            <td class="label-cell"></td>
        @else
            @php $b = $barangs[$barangIdx]; @endphp
            <td class="label-cell">
                <span class="label-id">{{ $b->id_barang }}</span>
                <span class="label-nama">{{ $b->nama_barang }}</span>
                <span class="label-harga">Rp {{ number_format($b->harga, 0, ',', '.') }}</span>
                <span class="label-satuan">/ {{ $b->satuan }}</span>
            </td>
            @php $barangIdx++; @endphp
        @endif

        @if ($i % $kolom === $kolom - 1 || $i === $totalSlot - 1)
            </tr>
        @endif

    @endfor
</table>

</body>
</html>