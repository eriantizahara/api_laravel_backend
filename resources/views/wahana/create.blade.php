@extends('layouts.dashboard') {{-- Gunakan hanya satu layout utama --}}

@section('page-heading')
    {{-- Judul halaman --}}
    <h2 class="text-3xl font-bold mb-4">Entri Data Wahana</h2>
@endsection

@section('content')
    {{-- Kartu pembungkus form --}}
    <div class="card shadow-sm p-4">
        {{-- Form untuk mengirim data wahana --}}
        <form action="{{ route('wahanas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf {{-- Token keamanan untuk mencegah CSRF --}}

            {{-- Input: Kode Wahana --}}
            <div class="mb-3">
                <label for="kode_wahana" class="form-label">Kode Wahana</label>
                <input type="text" name="kode_wahana" id="kode_wahana"
                       value="{{ old('kode_wahana') }}" {{-- Menampilkan input sebelumnya jika validasi gagal --}}
                       class="form-control @error('kode_wahana') is-invalid @enderror" required>
                {{-- Menampilkan pesan error jika validasi gagal --}}
                @error('kode_wahana')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input: Nama Wahana --}}
            <div class="mb-3">
                <label for="nama_wahana" class="form-label">Nama Wahana</label>
                <input type="text" name="nama_wahana" id="nama_wahana"
                       value="{{ old('nama_wahana') }}"
                       class="form-control @error('nama_wahana') is-invalid @enderror" required>
                @error('nama_wahana')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input: Deskripsi --}}
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="3"
                          class="form-control @error('deskripsi') is-invalid @enderror"
                          required>{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input: Harga Tiket --}}
            <div class="mb-3">
                <label for="harga_tiket" class="form-label">Harga Tiket</label>
                <input type="number" name="harga_tiket" id="harga_tiket"
                       value="{{ old('harga_tiket') }}"
                       class="form-control @error('harga_tiket') is-invalid @enderror" required>
                @error('harga_tiket')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input: Foto (Upload) --}}
            <div class="mb-3">
                <label for="foto" class="form-label">Foto</label>
                <input type="file" name="foto" id="foto"
                       class="form-control @error('foto') is-invalid @enderror" accept="image/*" required>
                @error('foto')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tombol Simpan dan Kembali --}}
            <div class="text-end">
                {{-- Tombol kembali ke halaman index --}}
                <a href="{{ route('wahanas.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left me-1"></i> Kembali
                </a>
                {{-- Tombol untuk mengirim form --}}
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
