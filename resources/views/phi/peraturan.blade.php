@extends('layouts.app')

@section('content')

<h4 class="mb-4">Rekap Peraturan Perusahaan</h4>

<div class="card card-modern p-4 shadow-sm">
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Nama Perusahaan</th>
                <th>Nomor Peraturan</th>
                <th>Masa Berlaku</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>PT Makmur Sentosa</td>
                <td>PP-2026-01</td>
                <td>2026 - 2028</td>
            </tr>
        </tbody>
    </table>
</div>

@endsection