@extends('layouts.dashboard') {{-- Menggunakan layout utama dashboard --}}

@section('page-heading')
    {{-- Judul halaman edit --}}
    <h2 class="text-3xl font-bold mb-4">Edit Data User</h2>
@endsection

@section('content')
    {{-- Bungkus form dalam card --}}
    <div class="card shadow-sm p-4">
        {{-- Form untuk update data user --}}
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf {{-- Token keamanan --}}
            @method('PUT') {{-- Gunakan method PUT untuk update data --}}

            {{-- Input: Kode User --}}
            <div class="mb-3">
                <label for="kodeuser" class="form-label">Kode User</label>
                <input type="text" name="kodeuser" id="kodeuser"
                       value="{{ old('kodeuser', $user->kodeuser) }}"
                       class="form-control @error('kodeuser') is-invalid @enderror" required>
                @error('kodeuser')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input: Nama --}}
            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" name="name" id="name"
                       value="{{ old('name', $user->name) }}"
                       class="form-control @error('name') is-invalid @enderror" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input: Email --}}
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email"
                       value="{{ old('email', $user->email) }}"
                       class="form-control @error('email') is-invalid @enderror" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input: Password --}}
            <div class="mb-3">
                <label for="password" class="form-label">Password (Kosongkan jika tidak diubah)</label>
                <input type="password" name="password" id="password"
                       class="form-control @error('password') is-invalid @enderror">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tombol Aksi --}}
            <div class="text-end">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
