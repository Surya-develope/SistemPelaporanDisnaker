@extends('layouts.app')

@section('content')
<div class="row min-vh-100 flex-center m-0">

    <div class="col-8 col-md-4 col-sm-6 text-center">
        <h4 class="mb-4 text-center">Import Data Rekap LPK</h4>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                Ada kesalahan pada file Excel Anda. Pastikan format kolom sesuai dengan template.
            </div>
        @endif

        <div class="card p-4 shadow-sm text-start mb-4">
            <h6 class="fw-bold mb-3">1. Upload Master LPK (Lembaga Aktif/Non-Aktif)</h6>
            <form action="{{ route('lattas.import.master') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-muted small d-block">
                        Format kolom (Baris 1): <code>nama_lpks</code>, <code>nama_pimpinan</code>, <code>tahun_berdiri</code>, <code>alamat</code>
                    </label>
                    <a href="{{ asset('storage/template_master_lpk.xlsx') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fa fa-download me-1"></i> Download Template LPK</a>
                    <input type="file" name="file" class="form-control" required accept=".xls,.xlsx,.csv">
                </div>
                <button class="btn btn-primary w-100" type="submit">Import LPK Master</button>
            </form>
        </div>

        <div class="card p-4 shadow-sm text-start">
            <h6 class="fw-bold mb-3">2. Upload Rekap Pelatihan LPK</h6>
            <form action="{{ route('lattas.import.training') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-muted small d-block">
                        Format kolom (Baris 1): <code>nama_lpklembaga</code>, <code>program_pelatihan_porglat</code>, <code>jumlah_peserta</code>, <code>jumlah_paket</code>
                    </label>
                    <a href="{{ asset('storage/template_training_lpk.xlsx') }}" class="btn btn-sm btn-outline-success mb-3"><i class="fa fa-download me-1"></i> Download Template Pelatihan</a>
                    <input type="file" name="file" class="form-control" required accept=".xls,.xlsx,.csv">
                </div>
                <button class="btn btn-success w-100" type="submit">Import Pelatihan LPK</button>
            </form>
        </div>

        <div class="mt-4">
            <a href="{{ url('/') }}" class="text-decoration-none">← Kembali ke Dashboard</a>
        </div>
    </div>
</div>
@endsection
