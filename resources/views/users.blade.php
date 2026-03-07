@extends('layouts.app')

@section('title', 'Kelola Pengguna | SIP DISNAKER')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
    <div>
        <h3 class="fw-bold mb-1" style="color:#1e293b; letter-spacing:-0.5px;">Kelola Akun Pengguna</h3>
        <p class="text-muted mb-0" style="font-size:14px;">Kontrol hak akses dan manajemen akun staf.</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahUserModal" style="border-radius:10px; font-weight:600;">
        <i class="fa fa-user-plus me-2"></i>Tambah Akun
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:10px;">
        <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:10px;">
        <i class="fa fa-triangle-exclamation me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-sm alert-danger fade show" style="border-radius:10px;">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li><i class="fa fa-triangle-exclamation me-1"></i>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card card-modern border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 custom-table">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="20%">Nama Lengkap</th>
                        <th width="15%">Username</th>
                        <th width="20%">Email</th>
                        <th width="15%">Role</th>
                        <th width="15%">Dibuat Pada</th>
                        <th width="10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $u)
                        <tr>
                            <td class="text-center text-muted">{{ $index + 1 }}</td>
                            <td class="fw-semibold" style="color:#334155">{{ $u->name }}</td>
                            <td><span class="badge bg-light text-dark border"><i class="fa fa-at me-1 text-muted"></i>{{ $u->username }}</span></td>
                            <td class="text-muted">{{ $u->email }}</td>
                            <td>
                                @if($u->role === 'admin')
                                    <span class="badge bg-danger">Administrator</span>
                                @elseif($u->role === 'pejabat')
                                    <span class="badge bg-warning text-dark">Kadis / Pejabat</span>
                                @else
                                    <span class="badge bg-primary">Petugas {{ strtoupper($u->role) }}</span>
                                @endif
                            </td>
                            <td class="text-muted" style="font-size:12.5px;">{{ $u->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-light border-0 text-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $u->id }}" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </button>
                                
                                @if($u->id !== auth()->id())
                                <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border-0 text-danger shadow-sm" title="Hapus">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>



                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fa fa-users fs-3 mb-2 opacity-50"></i><br>
                                Belum ada data pengguna.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah User -->
<div class="modal fade" id="tambahUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow" style="border-radius:14px">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Akun Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" style="font-size:13px;font-weight:600">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" required placeholder="Contoh: Budi Santoso">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:13px;font-weight:600">Username</label>
                        <input type="text" name="username" class="form-control" required placeholder="Contoh: budi123">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:13px;font-weight:600">Email</label>
                        <input type="email" name="email" class="form-control" required placeholder="Contoh: budi@disnaker.go.id">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:13px;font-weight:600">Hak Akses (Role)</label>
                        <select name="role" class="form-select" required>
                            <option value="">Pilih Hak Akses...</option>
                            <option value="penta">Petugas PENTA</option>
                            <option value="phi">Petugas PHI</option>
                            <option value="lattas">Petugas LATTAS</option>
                            <option value="pejabat">Kadis / Pimpinan (Read-Only)</option>
                            <option value="admin">Administrator (Akses Penuh)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:13px;font-weight:600">Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="Masukkan password">
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Buat Akun</button>
                </div>
            </form>
        </div>
    </div>
</div><!-- Area Modals Edit -->
@foreach($users as $u)
<div class="modal fade" id="editUserModal{{ $u->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow" style="border-radius:14px">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Edit Akun <span class="text-primary">{{ $u->name }}</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('users.update', $u->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" style="font-size:13px;font-weight:600">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ $u->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:13px;font-weight:600">Username</label>
                        <input type="text" name="username" class="form-control" value="{{ $u->username }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:13px;font-weight:600">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $u->email }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:13px;font-weight:600">Hak Akses (Role)</label>
                        <select name="role" class="form-select" required>
                            <option value="admin" {{ $u->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                            <option value="pejabat" {{ $u->role == 'pejabat' ? 'selected' : '' }}>Kadis / Pimpinan</option>
                            <option value="penta" {{ $u->role == 'penta' ? 'selected' : '' }}>Petugas PENTA</option>
                            <option value="phi" {{ $u->role == 'phi' ? 'selected' : '' }}>Petugas PHI</option>
                            <option value="lattas" {{ $u->role == 'lattas' ? 'selected' : '' }}>Petugas LATTAS</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:13px;font-weight:600">Password Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                        <small class="text-muted d-block mt-1">Minimal 3 karakter.</small>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<style>
    .custom-table th {
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #64748b;
        font-weight: 700;
        padding: 16px;
        border-bottom: 2px solid #e2e8f0;
    }
    .custom-table td {
        padding: 16px;
        vertical-align: middle;
        font-size: 13.5px;
    }
</style>
@endsection
