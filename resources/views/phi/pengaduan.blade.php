@extends('layouts.app')

@section('content')

<h4 class="mb-4">Rekap Pengaduan Kasus</h4>

<div class="card card-modern p-4 shadow-sm">
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Nama Pelapor</th>
                <th>Perusahaan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Andi Saputra</td>
                <td>PT Sejahtera</td>
                <td><span class="badge bg-warning">Proses</span></td>
            </tr>
        </tbody>
    </table>
</div>

@endsection