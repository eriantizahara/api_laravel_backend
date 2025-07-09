@extends('layouts.dashboard') {{-- Gunakan hanya satu layout utama --}}

@section('page-heading')
    {{-- Judul halaman --}}
    <h2 class="text-3xl font-bold mb-4">Entri Data Customer</h2>
@endsection

@section('content')
    {{-- Kartu pembungkus form --}}
    <div class="card shadow-sm p-4">
        {{-- Form untuk mengirim data customer --}}
        <form action="{{ route('customers.store') }}" method="POST">
            @csrf {{-- Token keamanan untuk mencegah CSRF --}}

            {{-- Input: Nama Customer --}}
            <div class="mb-3">
                <label for="namacustomer" class="form-label">Nama Customer</label>
                <input type="text" name="namacustomer" id="namacustomer"
                       value="{{ old('namacustomer') }}"
                       class="form-control @error('namacustomer') is-invalid @enderror" required>
                @error('namacustomer')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input: Username --}}
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username"
                       value="{{ old('username') }}"
                       class="form-control @error('username') is-invalid @enderror" required>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input: Password --}}
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
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
                       value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input: No HP --}}
            <div class="mb-3">
                <label for="nohp" class="form-label">No HP</label>
                <input type="text" name="nohp" id="nohp"
                       value="{{ old('nohp') }}"
                       class="form-control @error('nohp') is-invalid @enderror">
                @error('nohp')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input: Alamat --}}
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea name="alamat" id="alamat" rows="3"
                          class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tombol Simpan dan Kembali --}}
            <div class="text-end">
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
