@extends('layouts.app')

@section('content')

<style>
    .import-page { max-width: 780px; margin: 0 auto; padding: 0 1rem 2rem; }

    .import-page-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: #0d1b35;
        margin-bottom: 0.25rem;
    }
    .import-page-subtitle { font-size: 0.85rem; color: #64748b; margin-bottom: 1.75rem; }

    /* ── Import Card ── */
    .import-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 10px rgba(0,0,0,.07);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 1.5rem;
        transition: box-shadow .22s ease;
    }
    .import-card:hover { box-shadow: 0 6px 22px rgba(0,0,0,.11); }

    .import-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 1rem 1.4rem;
        background: linear-gradient(135deg, #0d1b35 0%, #1e3a8a 100%);
        color: #fff;
    }
    .import-card-header .icon-wrap {
        width: 38px; height: 38px;
        background: rgba(255,255,255,.13);
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .import-card-header h6 {
        margin: 0;
        font-size: .92rem;
        font-weight: 600;
        letter-spacing: .01em;
    }
    .import-card-header small {
        font-size: .75rem;
        opacity: .75;
        display: block;
        margin-top: 1px;
    }

    .import-card-body { padding: 1.4rem; }

    /* ── Format hint ── */
    .format-hint {
        background: #f0f6ff;
        border-left: 3px solid #2563eb;
        border-radius: 6px;
        padding: .6rem .9rem;
        font-size: .78rem;
        color: #374151;
        margin-bottom: 1rem;
    }
    .format-hint code {
        background: #dbeafe;
        color: #1d4ed8;
        border-radius: 4px;
        padding: 1px 5px;
        font-size: .76rem;
    }

    /* ── Form controls ── */
    .form-label { font-size: .82rem; font-weight: 600; color: #374151; margin-bottom: .35rem; }
    .form-control, .form-select {
        border-radius: 8px;
        border: 1.5px solid #e2e8f0;
        font-size: .875rem;
        padding: .45rem .75rem;
        transition: border-color .18s, box-shadow .18s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37,99,235,.12);
        outline: none;
    }
    input[type="file"].form-control { padding: .38rem .75rem; }

    /* ── Buttons ── */
    .btn-download {
        display: inline-flex; align-items: center; gap: 6px;
        padding: .42rem 1rem;
        border-radius: 8px;
        border: 1.5px solid #2563eb;
        color: #2563eb;
        background: transparent;
        font-size: .8rem;
        font-weight: 600;
        text-decoration: none;
        transition: all .18s;
        margin-bottom: 1rem;
    }
    .btn-download:hover { background: #2563eb; color: #fff; }

    .btn-import {
        display: flex; align-items: center; justify-content: center; gap: 8px;
        width: 100%;
        padding: .6rem 1rem;
        border-radius: 9px;
        border: none;
        background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
        color: #fff;
        font-size: .88rem;
        font-weight: 600;
        cursor: pointer;
        transition: opacity .18s, transform .14s;
        box-shadow: 0 3px 10px rgba(37,99,235,.28);
        margin-top: 1.1rem;
    }
    .btn-import:hover { opacity: .9; transform: translateY(-1px); }
    .btn-import:active { transform: translateY(0); opacity: 1; }

    /* ── Alerts ── */
    .alert-modern {
        display: flex; align-items: flex-start; gap: 10px;
        border-radius: 10px;
        padding: .85rem 1.1rem;
        font-size: .85rem;
        margin-bottom: 1.25rem;
        border: none;
    }
    .alert-modern.success { background: #f0fdf4; color: #166534; border-left: 4px solid #22c55e; }
    .alert-modern.danger  { background: #fff5f5; color: #991b1b; border-left: 4px solid #ef4444; }
    .alert-modern .alert-icon { font-size: 1rem; margin-top: 1px; flex-shrink: 0; }
</style>

<div class="import-page">

    <p class="import-page-title"><i class="fa-solid fa-file-import me-2 text-primary"></i>Import Data Rekap PENTA</p>
    <p class="import-page-subtitle">Upload file Excel (.xlsx/.xls/.csv) untuk memperbarui data Bidang Penempatan Tenaga Kerja.</p>

    @if(session('success'))
        <div class="alert-modern success">
            <i class="fa-solid fa-circle-check alert-icon"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="alert-modern danger">
            <i class="fa-solid fa-triangle-exclamation alert-icon"></i>
            <span>Ada kesalahan pada file Excel Anda. Pastikan format kolom sesuai dengan template yang tersedia.</span>
        </div>
    @endif

    {{-- ── CARD 1: Lowongan Kerja ── --}}
    <div class="import-card">
        <div class="import-card-header">
            <div class="icon-wrap"><i class="fa-solid fa-briefcase"></i></div>
            <div>
                <h6>Import Data Lowongan Kerja</h6>
                <small>Data lowongan yang tersedia dari perusahaan/instansi</small>
            </div>
        </div>
        <div class="import-card-body">
            <div class="format-hint">
                <i class="fa-solid fa-circle-info me-1"></i>
                Format kolom (Baris 1): <code>judul_lowongan</code>, <code>perusahaan</code>, <code>kuota</code>, <code>status_lowongan</code>, <code>bulan</code>, <code>tahun</code>
            </div>
            <a href="{{ asset('storage/template_lowongan_penta.xlsx') }}" class="btn-download">
                <i class="fa-solid fa-download"></i> Download Template Lowongan
            </a>
            <form action="{{ route('penta.import.lowongan') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <label class="form-label">File Excel</label>
                <input type="file" name="file" class="form-control" required accept=".xls,.xlsx,.csv">
                <button class="btn-import" type="submit">
                    <i class="fa-solid fa-upload"></i> Import Lowongan Kerja
                </button>
            </form>
        </div>
    </div>

    {{-- ── CARD 2: Pencari Kerja ── --}}
    <div class="import-card">
        <div class="import-card-header">
            <div class="icon-wrap"><i class="fa-solid fa-user-check"></i></div>
            <div>
                <h6>Import Data Pencari Kerja Aktif</h6>
                <small>Data pencari kerja yang terdaftar di Disnaker</small>
            </div>
        </div>
        <div class="import-card-body">
            <div class="format-hint">
                <i class="fa-solid fa-circle-info me-1"></i>
                Format kolom (Baris 1): <code>nik</code>, <code>nama</code>, <code>jenis_kelamin</code>, <code>pendidikan_terakhir</code>, <code>status_verifikasi</code>, <code>bulan</code>, <code>tahun</code>
            </div>
            <a href="{{ asset('storage/template_pencari_penta.xlsx') }}" class="btn-download">
                <i class="fa-solid fa-download"></i> Download Template Pencari Kerja
            </a>
            <form action="{{ route('penta.import.pencari') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <label class="form-label">File Excel</label>
                <input type="file" name="file" class="form-control" required accept=".xls,.xlsx,.csv">
                <button class="btn-import" type="submit">
                    <i class="fa-solid fa-upload"></i> Import Pencari Kerja
                </button>
            </form>
        </div>
    </div>

    {{-- ── CARD 3: Penempatan ── --}}
    <div class="import-card">
        <div class="import-card-header">
            <div class="icon-wrap"><i class="fa-solid fa-chart-pie"></i></div>
            <div>
                <h6>Import Data Penempatan</h6>
                <small>Data pelamar yang berhasil diterima bekerja</small>
            </div>
        </div>
        <div class="import-card-body">
            <div class="format-hint">
                <i class="fa-solid fa-circle-info me-1"></i>
                Format kolom (Baris 1): <code>nama</code>, <code>nama_perusahaan</code>, <code>judul_lowongan</code>, <code>tanggal_diterima</code>, <code>bulan</code>, <code>tahun</code>
            </div>
            <a href="{{ asset('storage/template_penempatan_penta.xlsx') }}" class="btn-download">
                <i class="fa-solid fa-download"></i> Download Template Penempatan
            </a>
            <form action="{{ route('penta.import.penempatan') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <label class="form-label">File Excel</label>
                <input type="file" name="file" class="form-control" required accept=".xls,.xlsx,.csv">
                <button class="btn-import" type="submit">
                    <i class="fa-solid fa-upload"></i> Import Penempatan
                </button>
            </form>
        </div>
    </div>

    <div class="mt-2">
        <a href="{{ url('/') }}" class="text-decoration-none" style="font-size:.85rem; color:#2563eb;">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
    </div>

</div>
@endsection
