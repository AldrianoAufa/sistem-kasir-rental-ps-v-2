<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan FnB</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-success { color: green; }
        .text-danger { color: red; }
        .font-weight-bold { font-weight: bold; }
        h3 { margin-bottom: 5px; }
        .summary { margin: 20px 0; }
        .summary p { margin: 5px 0; }
    </style>
</head>
<body>
    <h1>Laporan Penjualan FnB</h1>
    <p>Periode: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</p>
    
    @php
        $totalPenjualan = $salesData->sum('total_penjualan');
        $totalModal = $salesData->sum('total_modal');
        $totalLabaRugi = $salesData->sum('laba_rugi');
        $marginPercentage = $totalPenjualan > 0 ? ($totalLabaRugi / $totalPenjualan) * 100 : 0;
    @endphp
    
    <div class="summary">
        <h3>Ringkasan</h3>
        <p><strong>Total Penjualan:</strong> Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
        <p><strong>Total Modal:</strong> Rp {{ number_format($totalModal, 0, ',', '.') }}</p>
        <p><strong>Laba/Rugi Bersih:</strong> <span class="{{ $totalLabaRugi >= 0 ? 'text-success' : 'text-danger' }}">Rp {{ number_format($totalLabaRugi, 0, ',', '.') }}</span></p>
        <p><strong>Margin Keuntungan:</strong> {{ number_format($marginPercentage, 2) }}%</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th class="text-center">Jumlah Terjual</th>
                <th class="text-right">Total Modal</th>
                <th class="text-right">Total Penjualan</th>
                <th class="text-right">Laba/Rugi</th>
                <th class="text-right">Margin (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($salesData as $index => $data)
                @php
                    $margin = $data->total_penjualan > 0 ? ($data->laba_rugi / $data->total_penjualan) * 100 : 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $data->nama }}</td>
                    <td class="text-center">{{ $data->jumlah_terjual }}</td>
                    <td class="text-right">Rp {{ number_format($data->total_modal, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($data->total_penjualan, 0, ',', '.') }}</td>
                    <td class="text-right {{ $data->laba_rugi >= 0 ? 'text-success' : 'text-danger' }} font-weight-bold">
                        Rp {{ number_format($data->laba_rugi, 0, ',', '.') }}
                    </td>
                    <td class="text-right {{ $margin >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ number_format($margin, 2) }}%
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="font-weight-bold">
                <td colspan="2" class="text-right">TOTAL:</td>
                <td class="text-center">{{ $salesData->sum('jumlah_terjual') }}</td>
                <td class="text-right">Rp {{ number_format($totalModal, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</td>
                <td class="text-right {{ $totalLabaRugi >= 0 ? 'text-success' : 'text-danger' }}">
                    Rp {{ number_format($totalLabaRugi, 0, ',', '.') }}
                </td>
                <td class="text-right {{ $marginPercentage >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ number_format($marginPercentage, 2) }}%
                </td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
