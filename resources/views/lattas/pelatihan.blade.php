@extends('layouts.app')

@section('content')

<h3 class="mb-4">Bidang Lattas - Rekap Pelatihan</h3>

<div class="card shadow p-3">
    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <th>No</th>
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
                <td>{{ $training->lpk->nama_lpk ?? '-' }}</td>
                <td>{{ $training->program_pelatihan }}</td>
                <td>{{ $training->jumlah_peserta }} Orang</td>
                <td>{{ $training->jumlah_paket }} Paket</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Belum ada data Rekap Pelatihan</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot class="table-group-divider fw-bold bg-light">
            <tr>
                <td colspan="3" class="text-end">JUMLAH</td>
                <td>{{ $trainings->sum('jumlah_peserta') }} Orang</td>
                <td>{{ $trainings->sum('jumlah_paket') }} Paket</td>
            </tr>
        </tfoot>
    </table>
</div>

@endsection