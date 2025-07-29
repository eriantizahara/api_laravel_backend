@extends('layouts.dashboard') {{-- Gunakan hanya satu layout utama --}}

@section('page-heading')
    <h2 class="text-3xl font-bold flex items-center gap-2">
        Data Wahana
    </h2>
@endsection

@section('content')
    <div class="col-md-12 mb-4">
        {{-- Tombol untuk menambah data wahana --}}
        <a href="{{ route('wahanas.create') }}" class="btn btn-sm btn-primary">
            <i class="fa fa-plus-circle"></i> Tambah Data
        </a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center">ID</th>
                <th class="text-center">Kode Wahana</th>
                <th class="text-center">Nama Wahana</th>
                <th class="text-center">Deskripsi</th>
                <th class="text-center">Harga Tiket</th>
                <th class="text-center">Foto</th>
                <th class="text-center">Aksi</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($wahanas as $wahana)
                <tr>
                    <td class="text-center">{{ $wahana->id }}</td>
                    <td class="text-center">{{ $wahana->kode_wahana }}</td>
                    <td>{{ $wahana->nama_wahana }}</td>
                    <td>{{ $wahana->deskripsi }}</td>
                    <td class="text-center">{{ $wahana->harga_tiket }}</td>
                    <td>
                        @if ($wahana->foto)
                            <img src="{{ asset('storage/' . $wahana->foto) }}" alt="Foto" width="100">
                        @else
                            <span class="text-muted">Tidak ada foto</span>
                        @endif
                    <td>
                        <a class="btn btn-warning btn-sm" href="{{ route('wahanas.edit', $wahana->id) }}"><i
                                class="fa fa-edit"></i>
                        </a>

                        {{-- <form action="{{ route('wahanas.destroy', $wahana->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" onclick="return confirm('Yakin Hapus?')"><i
                                    class="fa fa-trash"></i>
                            </button>
                        </form> --}}

                        {{-- Form untuk hapus data wahana --}}
                        <form action="{{ route('wahanas.destroy', $wahana->id) }}" method="POST"
                            class="form-hapus d-inline">

                            {{-- Token CSRF untuk keamanan form --}}
                            @csrf

                            {{-- Method spoofing karena HTML hanya mendukung GET dan POST --}}
                            @method('DELETE')

                            {{-- Tombol submit yang akan kita intercept dengan SweetAlert --}}
                            <button type="submit" class="btn btn-danger btn-sm btn-konfirmasi-hapus">
                                <i class="fa fa-trash"></i> {{-- Icon tempat sampah --}}
                            </button>
                        </form>


                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@push('scripts')
    {{-- Load library SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Ambil semua form yang memiliki class 'form-hapus'
            const forms = document.querySelectorAll('.form-hapus');

            // Loop tiap form dan pasang event saat submit
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    // Cegah form langsung dikirim
                    e.preventDefault();

                    // Tampilkan konfirmasi SweetAlert2
                    Swal.fire({
                        title: 'Yakin ingin menghapus data ini?', // Judul popup
                        text: "Data yang sudah dihapus tidak dapat dikembalikan!", // Pesan
                        icon: 'warning', // Ikon peringatan
                        showCancelButton: true, // Tampilkan tombol batal
                        confirmButtonColor: '#d33', // Warna tombol 'Ya'
                        cancelButtonColor: '#3085d6', // Warna tombol 'Batal'
                        confirmButtonText: 'Ya, Hapus!', // Teks tombol konfirmasi
                        cancelButtonText: 'Batal' // Teks tombol batal
                    }).then((result) => {
                        // Jika pengguna klik tombol konfirmasi
                        if (result.isConfirmed) {
                            form.submit(); // Submit form secara manual
                        }
                    });
                });
            });
        });
    </script>

    {{-- Notifikasi sukses jika ada session flash --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif
@endpush
