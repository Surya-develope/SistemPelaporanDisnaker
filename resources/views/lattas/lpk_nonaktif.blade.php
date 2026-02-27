@extends('layouts.app')

@section('content')
<h4 class="mb-4">Rekap LPK Non Aktif</h4>

<div class="card card-modern shadow-sm p-4">
    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Nama LPK</th>
                <th>Nama Pimpinan</th>
                <th>Tahun Berdiri</th>
                <th>Alamat</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lpks as $lpk)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $lpk->nama_lpk }}</td>
                <td>{{ $lpk->nama_pimpinan }}</td>
                <td>{{ $lpk->tahun_berdiri }}</td>
                <td>{{ $lpk->alamat }}</td>
                <td><span class="badge bg-danger">Non Aktif</span></td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Belum ada data LPK Non Aktif</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection