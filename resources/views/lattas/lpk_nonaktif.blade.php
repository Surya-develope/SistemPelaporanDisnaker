@extends('layouts.app')

@section('content')
<h4 class="mb-4">Rekap LPK Non Aktif</h4>

<div class="card card-modern shadow-sm p-4">
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Nama LPK</th>
                <th>Alamat</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>LPK Harapan Bangsa</td>
                <td>Pekanbaru</td>
                <td><span class="badge bg-danger">Non Aktif</span></td>
            </tr>
        </tbody>
    </table>
</div>
@endsection