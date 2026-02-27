@extends('layouts.app')

@section('content')
<div class="row min-vh-100 flex-center m-0">

    <div class="col-8 col-md-5 col-sm-8 text-center">
        <h4 class="mb-4 text-center">Import Data Rekap PENTA</h4>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                Ada kesalahan pada file Excel Anda. Pastikan format kolom sesuai dengan ketentuan.
            </div>
        @endif

        <div class="card p-4 shadow-sm text-start mb-4">
            <h6 class="fw-bold mb-3">1. Upload Data Lowongan Kerja</h6>
            <form action="{{ route('penta.import.lowongan') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-muted small d-block">
                        Format kolom (Baris 1): <code>judul_lowongan</code>, <code>perusahaan</code>, <code>kuota</code>, <code>status_lowongan</code> (dan kolom relevan lainnya)
                    </label>
                    <a href="{{ asset('storage/template_lowongan_penta.xlsx') }}" class="btn btn-sm btn-outline-info mb-3"><i class="fa fa-download me-1"></i> Download Template Lowongan</a>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label">Bulan Laporan</label>
                            <select name="bulan" class="form-select" required>
                                @for($i=1; $i<=12; $i++)
                                    <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Tahun Laporan</label>
                            <input type="number" name="tahun" class="form-control" value="{{ date('Y') }}" required min="2000" max="2100">
                        </div>
                    </div>

                    <input type="file" name="file" class="form-control" required accept=".xls,.xlsx,.csv">
                </div>
                <button class="btn btn-primary w-100" type="submit">Import Lowongan</button>
            </form>
        </div>

        <div class="card p-4 shadow-sm text-start mb-4">
            <h6 class="fw-bold mb-3">2. Upload Data Pencari Kerja Aktif</h6>
            <form action="{{ route('penta.import.pencari') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-muted small d-block">
                        Format kolom (Baris 1): <code>nik</code>, <code>nama</code>, <code>jenis_kelamin</code>, <code>pendidikan_terakhir</code>, <code>status_verifikasi</code> (dan lain-lain)
                    </label>
                    <a href="{{ asset('storage/template_pencari_penta.xlsx') }}" class="btn btn-sm btn-outline-success mb-3"><i class="fa fa-download me-1"></i> Download Template Pencari Kerja</a>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label">Bulan Laporan</label>
                            <select name="bulan" class="form-select" required>
                                @for($i=1; $i<=12; $i++)
                                    <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Tahun Laporan</label>
                            <input type="number" name="tahun" class="form-control" value="{{ date('Y') }}" required min="2000" max="2100">
                        </div>
                    </div>

                    <input type="file" name="file" class="form-control" required accept=".xls,.xlsx,.csv">
                </div>
                <button class="btn btn-primary w-100" type="submit">Import Pencari Kerja</button>
            </form>
        </div>

        <div class="card p-4 shadow-sm text-start">
            <h6 class="fw-bold mb-3">3. Upload Data Penempatan (Pelamar Diterima)</h6>
            <form action="{{ route('penta.import.penempatan') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-muted small d-block">
                        Format kolom (Baris 1): <code>nama</code>, <code>nama_perusahaan</code>, <code>judul_lowongan</code>, <code>tanggal_diterima</code> (dan lain-lain)
                    </label>
                    <a href="{{ asset('storage/template_penempatan_penta.xlsx') }}" class="btn btn-sm btn-outline-primary mb-3"><i class="fa fa-download me-1"></i> Download Template Penempatan</a>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label">Bulan Laporan</label>
                            <select name="bulan" class="form-select" required>
                                @for($i=1; $i<=12; $i++)
                                    <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Tahun Laporan</label>
                            <input type="number" name="tahun" class="form-control" value="{{ date('Y') }}" required min="2000" max="2100">
                        </div>
                    </div>

                    <input type="file" name="file" class="form-control" required accept=".xls,.xlsx,.csv">
                </div>
                <button class="btn btn-primary w-100" type="submit">Import Penempatan</button>
            </form>
        </div>

        <div class="mt-4">
            <a href="{{ url('/') }}" class="text-decoration-none">← Kembali ke Dashboard</a>
        </div>
    </div>
</div>
@endsection
