<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>Jumlah Terjual</th>
            <th>Total Modal</th>
            <th>Total Penjualan</th>
            <th>Laba/Rugi</th>
            <th>Margin (%)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($salesData as $index => $data)
            @php
                $margin = $data->total_penjualan > 0 ? ($data->laba_rugi / $data->total_penjualan) * 100 : 0;
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $data->nama }}</td>
                <td>{{ $data->jumlah_terjual }}</td>
                <td>{{ $data->total_modal }}</td>
                <td>{{ $data->total_penjualan }}</td>
                <td>{{ $data->laba_rugi }}</td>
                <td>{{ number_format($margin, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        @php
            $totalPenjualan = $salesData->sum('total_penjualan');
            $totalModal = $salesData->sum('total_modal');
            $totalLabaRugi = $salesData->sum('laba_rugi');
            $marginPercentage = $totalPenjualan > 0 ? ($totalLabaRugi / $totalPenjualan) * 100 : 0;
        @endphp
        <tr>
            <td colspan="2"><strong>TOTAL</strong></td>
            <td><strong>{{ $salesData->sum('jumlah_terjual') }}</strong></td>
            <td><strong>{{ $totalModal }}</strong></td>
            <td><strong>{{ $totalPenjualan }}</strong></td>
            <td><strong>{{ $totalLabaRugi }}</strong></td>
            <td><strong>{{ number_format($marginPercentage, 2) }}</strong></td>
        </tr>
    </tfoot>
</table>
