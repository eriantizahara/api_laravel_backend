@extends('layouts.dashboard') {{-- Menggunakan layout utama dashboard --}}

@section('page-heading')
    {{-- Judul halaman edit --}}
    <h2 class="text-3xl font-bold mb-4">Edit Data Customer</h2>
@endsection

@section('content')
    {{-- Bungkus form dalam card --}}
    <div class="card shadow-sm p-4">
        {{-- Form untuk update data customer --}}
        <form action="{{ route('customers.update', $customer->id) }}" method="POST">
            @csrf {{-- Token keamanan --}}
            @method('PUT') {{-- Gunakan method PUT untuk update data --}}

            {{-- Input: Nama Customer --}}
            <div class="mb-3">
                <label for="namacustomer" class="form-label">Nama Customer</label>
                <input type="text" name="namacustomer" id="namacustomer"
                       value="{{ old('namacustomer', $customer->namacustomer) }}"
                       class="form-control @error('namacustomer') is-invalid @enderror" required>
                @error('namacustomer')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input: Username --}}
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username"
                       value="{{ old('username', $customer->username) }}"
                       class="form-control @error('username') is-invalid @enderror" required>
                @error('username')
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

            {{-- Input: Email --}}
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email"
                       value="{{ old('email', $customer->email) }}"
                       class="form-control @error('email') is-invalid @enderror" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input: No HP --}}
            <div class="mb-3">
                <label for="nohp" class="form-label">No HP</label>
                <input type="text" name="nohp" id="nohp"
                       value="{{ old('nohp', $customer->nohp) }}"
                       class="form-control @error('nohp') is-invalid @enderror">
                @error('nohp')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input: Alamat --}}
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea name="alamat" id="alamat" rows="3"
                          class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $customer->alamat) }}</textarea>
                @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tombol Aksi --}}
            <div class="text-end">
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
