@extends('layouts.dashboard') {{-- Gunakan hanya satu layout utama --}}

@section('page-heading')
    <h2 class="text-3xl font-bold flex items-center gap-2">
        Data User
    </h2>
@endsection

@section('content')
    <div class="col-md-12 mb-4">
        {{-- Tombol untuk menambah data user --}}
        <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">
            <i class="fa fa-plus-circle"></i> Tambah Data
        </a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center">ID</th>
                <th class="text-center">Nama</th>
                <th class="text-center">Email</th>
                <th class="text-center">No HP</th>
                <th class="text-center">Alamat</th>
                <th class="text-center">Status</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td class="text-center">{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td class="text-center">{{ $user->email }}</td>
                    <td class="text-center">{{ $user->nohp ?? '-' }}</td>
                    <td>{{ $user->alamat ?? '-' }}</td>
                    <td class="text-center">
                        <span class="badge bg-{{ $user->status == 'admin' ? 'success' : 'secondary' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>
                    <td class="text-center">
                        <a class="btn btn-warning btn-sm" href="{{ route('users.edit', $user->id) }}">
                            <i class="fa fa-edit"></i>
                        </a>

                        {{-- Form untuk hapus data wahana --}}
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="form-hapus d-inline">

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
