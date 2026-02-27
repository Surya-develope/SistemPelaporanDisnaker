@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="m-0">Bidang Lattas - Rekap Pelatihan</h3>
</div>

<div class="card shadow p-3 mb-4">
    <form method="GET" action="{{ url('/lattas/pelatihan') }}" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Tampilkan Bulan:</label>
            <select name="bulan" class="form-select">
                <option value="">Semua Bulan</option>
                @for($i=1; $i<=12; $i++)
                    <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Tahun:</label>
            <input type="number" name="tahun" class="form-control" value="{{ request('tahun') }}" placeholder="Contoh: 2026">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100"><i class="fa fa-filter me-1"></i> Filter</button>
        </div>
        @if(request('bulan') || request('tahun'))
        <div class="col-md-2">
            <a href="{{ url('/lattas/pelatihan') }}" class="btn btn-outline-secondary w-100">Reset</a>
        </div>
        @endif
    </form>
</div>

<div class="card shadow p-3">
    <table class="table table-bordered table-striped text-center align-middle">
        <thead class="table-light">
            <tr>
                <th width="5%">No</th>
                <th width="10%">Bulan</th>
                <th width="10%">Tahun</th>
                <th>Nama LPK/Lembaga</th>
                <th>Program Pelatihan (PORGLAT)</th>
                <th>Jumlah Peserta</th>
                <th>Jumlah Paket</th>
            </tr>
        </thead>
        <tbody>
            @forelse($trainings as $training)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $training->bulan ? date('F', mktime(0, 0, 0, $training->bulan, 1)) : '-' }}</td>
                <td>{{ $training->tahun ?? '-' }}</td>
                <td class="text-start">{{ $training->lpk->nama_lpk ?? '-' }}</td>
                <td class="text-start">{{ $training->program_pelatihan }}</td>
                <td>{{ $training->jumlah_peserta }} Orang</td>
                <td>{{ $training->jumlah_paket }} Paket</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted py-4">Belum ada data Rekap Pelatihan untuk periode tersebut.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot class="table-group-divider fw-bold bg-light">
            <tr>
                <td colspan="5" class="text-end">JUMLAH TOTAL</td>
                <td>{{ $trainings->sum('jumlah_peserta') }} Orang</td>
                <td>{{ $trainings->sum('jumlah_paket') }} Paket</td>
            </tr>
        </tfoot>
    </table>
</div>

@endsection