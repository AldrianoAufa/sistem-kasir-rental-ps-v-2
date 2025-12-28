@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-user-shield"></i> Kelola Akses Halaman untuk Admin
            </h6>
        </div>
        <div class="card-body">
            <p class="text-muted mb-4">
                <i class="fas fa-info-circle"></i> 
                Centang halaman yang boleh diakses oleh Admin (Kasir). 
                Owner selalu memiliki akses ke semua halaman.
            </p>
            
            <form action="{{ route('access-control.bulk-update') }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="60%">Halaman</th>
                                <th width="20%" class="text-center">Akses Admin</th>
                                <th width="20%" class="text-center">Akses Owner</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permissions as $permission)
                            <tr>
                                <td>
                                    <i class="fas fa-file-alt text-primary mr-2"></i>
                                    {{ $permission->page_name }}
                                    <small class="text-muted d-block">{{ $permission->page_key }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" 
                                               class="custom-control-input" 
                                               id="perm_{{ $permission->id }}"
                                               name="permissions[]" 
                                               value="{{ $permission->id }}"
                                               {{ $permission->admin_access ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="perm_{{ $permission->id }}">
                                            {{ $permission->admin_access ? 'Aktif' : 'Mati' }}
                                        </label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-success">
                                        <i class="fas fa-check"></i> Selalu Aktif
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <button type="button" class="btn btn-secondary ml-2" onclick="location.reload()">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info">
                <i class="fas fa-question-circle"></i> Panduan
            </h6>
        </div>
        <div class="card-body">
            <ul class="mb-0">
                <li><strong>Owner (Pemilik)</strong>: Memiliki akses penuh ke semua halaman dan fitur.</li>
                <li><strong>Admin (Kasir)</strong>: Akses terbatas sesuai pengaturan di atas.</li>
                <li>Fitur <strong>Manajemen User</strong>, <strong>Master Data</strong>, dan <strong>Manajemen Stok</strong> hanya dapat diakses oleh Owner.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
