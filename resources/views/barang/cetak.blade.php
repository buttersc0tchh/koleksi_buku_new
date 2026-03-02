<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tag Harga</title>
    <style>
        @page {
            margin: 13mm 5mm 13mm 5mm;
            size: A4 portrait;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; width: 200mm; }

        .label-row {
            display: block;
            width: 200mm;
            height: 33.5mm;
            overflow: hidden;
        }

        .label-cell {
            display: inline-block;
            vertical-align: top;
            width: 38mm;
            height: 33.5mm;
            margin-right: 2mm;
            padding: 3mm;
            overflow: hidden;
        }

        .label-cell:last-child { margin-right: 0; }

        .label-content { text-align: center; padding-top: 3mm; }

        .label-id {
            font-size: 6pt;
            color: #888;
            display: block;
            margin-bottom: 2mm;
        }

        .label-nama {
            font-size: 8.5pt;
            font-weight: bold;
            color: #000;
            display: block;
            line-height: 1.3;
            margin-bottom: 2mm;
        }

        .label-harga {
            font-size: 12pt;
            font-weight: bold;
            color: #c00000;
            display: block;
            margin-bottom: 1mm;
        }

        .label-satuan {
            font-size: 7pt;
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

@for ($i = 0; $i < $totalSlot; $i++)

    @if ($i % $kolom === 0)
        <div class="label-row">
    @endif

    @if ($i < $startPos || $barangIdx >= $totalBarang)
        <div class="label-cell"></div>
    @else
        @php $b = $barangs[$barangIdx]; @endphp
        <div class="label-cell">
            <div class="label-content">
                <span class="label-id">{{ $b->id_barang }}</span>
                <span class="label-nama">{{ $b->nama_barang }}</span>
                <span class="label-harga">Rp {{ number_format($b->harga, 0, ',', '.') }}</span>
                <span class="label-satuan">/ {{ $b->satuan }}</span>
            </div>
        </div>
        @php $barangIdx++; @endphp
    @endif

    @if ($i % $kolom === $kolom - 1 || $i === $totalSlot - 1)
        </div>
    @endif

@endfor

</body>
</html>