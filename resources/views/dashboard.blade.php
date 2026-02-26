@extends('layouts.app')

@section('content')

<h4 class="mb-4">Dashboard Statistik</h4>

<div class="row g-4 mb-4">

    <div class="col-md-4">
        <div class="card card-modern shadow-sm p-4">
            <h6 class="text-muted">Total Tenaga Kerja</h6>
            <h3>1,250</h3>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-modern shadow-sm p-4">
            <h6 class="text-muted">Total PKWT</h6>
            <h3>320</h3>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-modern shadow-sm p-4">
            <h6 class="text-muted">Total Pelatihan</h6>
            <h3>45</h3>
        </div>
    </div>

</div>

<div class="card card-modern shadow-sm p-4">
    <h6 class="mb-3">Statistik Bulanan</h6>
    <canvas id="myChart" height="100"></canvas>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('myChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            datasets: [
                {
                    label: 'Tenaga Kerja',
                    data: [120, 150, 180, 200, 170, 220],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59,130,246,0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'PKWT',
                    data: [40, 60, 75, 90, 70, 100],
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34,197,94,0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });
});
</script>

@endsection