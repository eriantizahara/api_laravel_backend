@extends('layouts.dashboard') {{-- Menggunakan layout utama dashboard --}}

@section('page-heading')
    {{-- Judul halaman edit --}}
    <h2 class="text-3xl font-bold mb-4">Edit Data Wahana</h2>
@endsection

@section('content')
    {{-- Bungkus form dalam card --}}
    <div class="card shadow-sm p-4">
        {{-- Form untuk update data wahana --}}
        <form action="{{ route('wahanas.update', $wahana->id) }}" method="POST" enctype="multipart/form-data">
            @csrf {{-- Token keamanan --}}
            @method('PUT') {{-- Gunakan method PUT untuk update data --}}

            {{-- Input: Kode Wahana --}}
            <div class="mb-3">
                <label for="kode_wahana" class="form-label">Kode Wahana</label>
                <input type="text" name="kode_wahana" id="kode_wahana"
                       value="{{ old('kode_wahana', $wahana->kode_wahana) }}" {{-- Ambil nilai lama atau dari validasi --}}
                       class="form-control @error('kode_wahana') is-invalid @enderror" required>
                @error('kode_wahana') {{-- Tampilkan pesan error jika validasi gagal --}}
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input: Nama Wahana --}}
            <div class="mb-3">
                <label for="nama_wahana" class="form-label">Nama Wahana</label>
                <input type="text" name="nama_wahana" id="nama_wahana"
                       value="{{ old('nama_wahana', $wahana->nama_wahana) }}"
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
                          required>{{ old('deskripsi', $wahana->deskripsi) }}</textarea>
                @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input: Harga Tiket --}}
            <div class="mb-3">
                <label for="harga_tiket" class="form-label">Harga Tiket</label>
                <input type="number" name="harga_tiket" id="harga_tiket"
                       value="{{ old('harga_tiket', $wahana->harga_tiket) }}"
                       class="form-control @error('harga_tiket') is-invalid @enderror" required>
                @error('harga_tiket')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input: Foto Baru --}}
            <div class="mb-3">
                <label for="foto" class="form-label">Foto (Opsional)</label>
                <input type="file" name="foto" id="foto"
                       class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                @error('foto')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                {{-- Tampilkan foto lama jika ada --}}
                @if ($wahana->foto)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $wahana->foto) }}" alt="Foto Wahana" width="120">
                        <p class="text-muted small mb-0">Foto saat ini</p>
                    </div>
                @endif
            </div>

            {{-- Tombol Aksi --}}
            <div class="text-end">
                {{-- Tombol kembali ke halaman index --}}
                <a href="{{ route('wahanas.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left me-1"></i> Kembali
                </a>
                {{-- Tombol simpan perubahan --}}
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
