@extends('layouts.app')

@section('content')
    <!-- Content Row -->
    @if (session()->has('success'))
        <div class="alert alert-success col-lg-8" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('gagal'))
        <div class="alert alert-danger col-lg-8" role="alert">
            {{ session('gagal') }}
        </div>
    @endif



    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('fnb.laporan') }}">
                <div class="row">
                    <div class="col-md-2">
                        <label for="period">Periode</label>
                        <select name="period" id="period" class="form-control">
                            <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Hari Ini</option>
                            <option value="this_month" {{ $period == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                            <option value="this_year" {{ $period == 'this_year' ? 'selected' : '' }}>Tahun Ini</option>
                            <option value="specific_month" {{ $period == 'specific_month' ? 'selected' : '' }}>Pilih Bulan</option>
                            <option value="specific_year" {{ $period == 'specific_year' ? 'selected' : '' }}>Pilih Tahun</option>
                            <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Custom Range</option>
                        </select>
                    </div>
                    
                    <!-- Month Selector -->
                    <div class="col-md-2" id="month-group" style="display: {{ $period == 'specific_month' ? 'block' : 'none' }}">
                        <label for="month">Bulan</label>
                        <select name="month" id="month" class="form-control">
                            @php
                                $months = [
                                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                ];
                                $selectedMonth = request('month', date('n'));
                            @endphp
                            @foreach($months as $num => $name)
                                <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Year Selector for Month -->
                    <div class="col-md-2" id="month-year-group" style="display: {{ $period == 'specific_month' ? 'block' : 'none' }}">
                        <label for="month_year">Tahun</label>
                        <select name="month_year" id="month_year" class="form-control">
                            @php
                                $currentYear = date('Y');
                                $selectedMonthYear = request('month_year', $currentYear);
                            @endphp
                            @for($y = $currentYear; $y >= $currentYear - 5; $y--)
                                <option value="{{ $y }}" {{ $selectedMonthYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    
                    <!-- Year Selector -->
                    <div class="col-md-2" id="year-group" style="display: {{ $period == 'specific_year' ? 'block' : 'none' }}">
                        <label for="year">Tahun</label>
                        <select name="year" id="year" class="form-control">
                            @php
                                $selectedYear = request('year', $currentYear);
                            @endphp
                            @for($y = $currentYear; $y >= $currentYear - 5; $y--)
                                <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    
                    <!-- Custom Date Range -->
                    <div class="col-md-2" id="start-date-group" style="display: {{ $period == 'custom' ? 'block' : 'none' }}">
                        <label for="start_date">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-2" id="end-date-group" style="display: {{ $period == 'custom' ? 'block' : 'none' }}">
                        <label for="end_date">Tanggal Akhir</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate }}">
                    </div>
                    
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary form-control">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        @php
            $totalPenjualan = $salesData->sum('total_penjualan');
            $totalModal = $salesData->sum('total_modal');
            $totalLabaRugi = $salesData->sum('laba_rugi');
            $marginPercentage = $totalPenjualan > 0 ? ($totalLabaRugi / $totalPenjualan) * 100 : 0;
        @endphp
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Penjualan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Modal</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalModal, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-{{ $totalLabaRugi >= 0 ? 'success' : 'danger' }} shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-{{ $totalLabaRugi >= 0 ? 'success' : 'danger' }} text-uppercase mb-1">
                                Laba/Rugi Bersih</div>
                            <div class="h5 mb-0 font-weight-bold text-{{ $totalLabaRugi >= 0 ? 'success' : 'danger' }}">
                                Rp {{ number_format($totalLabaRugi, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-{{ $totalLabaRugi >= 0 ? 'chart-line' : 'chart-line-down' }} fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Margin Keuntungan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($marginPercentage, 2) }}%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Detail Penjualan & Laba/Rugi per Produk</h6>
            <div>
                <a href="{{ route('fnb.laporan.excel', request()->all()) }}" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
                <a href="{{ route('fnb.laporan.pdf', request()->all()) }}" class="btn btn-danger btn-sm">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
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
                        @forelse($salesData as $index => $item)
                            @php
                                $margin = $item->total_penjualan > 0 ? ($item->laba_rugi / $item->total_penjualan) * 100 : 0;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->nama }}</td>
                                <td class="text-center">{{ $item->jumlah_terjual }}</td>
                                <td class="text-right">Rp {{ number_format($item->total_modal, 0, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($item->total_penjualan, 0, ',', '.') }}</td>
                                <td class="text-right font-weight-bold {{ $item->laba_rugi >= 0 ? 'text-success' : 'text-danger' }}">
                                    Rp {{ number_format($item->laba_rugi, 0, ',', '.') }}
                                </td>
                                <td class="text-right {{ $margin >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($margin, 2) }}%
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data penjualan pada periode ini</td>
                            </tr>
                        @endforelse
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
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Penjualan Per Hari</h6>
                </div>
                <div class="card-body">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Distribusi Penjualan Produk</h6>
                </div>
                <div class="card-body">
                    <canvas id="productChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    
@endsection

@section('scripts')
<script>
    // Period toggle
    $('#period').change(function() {
        const value = $(this).val();
        
        // Hide all optional fields first
        $('#start-date-group, #end-date-group, #month-group, #month-year-group, #year-group').hide();
        
        // Show relevant fields based on selection
        if (value === 'custom') {
            $('#start-date-group, #end-date-group').show();
        } else if (value === 'specific_month') {
            $('#month-group, #month-year-group').show();
        } else if (value === 'specific_year') {
            $('#year-group').show();
        }
    });

    // Charts
    var salesData = @json($chartData['sales_over_time']);
    var productData = @json($chartData['product_distribution']);
    var profitData = @json($chartData['profit_loss']);

    // Sales over time (bar)
    var ctxSales = document.getElementById('salesChart').getContext('2d');
    new Chart(ctxSales, {
        type: 'bar',
        data: {
            labels: Object.keys(salesData),
            datasets: [{
                label: 'Jumlah Terjual',
                data: Object.values(salesData),
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Product distribution (pie)
    var ctxProduct = document.getElementById('productChart').getContext('2d');
    new Chart(ctxProduct, {
        type: 'pie',
        data: {
            labels: Object.keys(productData),
            datasets: [{
                data: Object.values(productData),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 205, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(153, 102, 255, 0.5)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 205, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        }
    });

    // Profit/Loss (bar)
    var ctxProfit = document.getElementById('profitChart').getContext('2d');
    new Chart(ctxProfit, {
        type: 'bar',
        data: {
            labels: Object.keys(profitData),
            datasets: [{
                label: 'Laba/Rugi',
                data: Object.values(profitData),
                backgroundColor: function(context) {
                    var value = context.parsed.y;
                    return value >= 0 ? 'rgba(75, 192, 192, 0.5)' : 'rgba(255, 99, 132, 0.5)';
                },
                borderColor: function(context) {
                    var value = context.parsed.y;
                    return value >= 0 ? 'rgba(75, 192, 192, 1)' : 'rgba(255, 99, 132, 1)';
                },
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
