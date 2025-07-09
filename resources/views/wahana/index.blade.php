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
                        <a class="btn btn-warning" href="{{ route('wahanas.edit', $wahana->id) }}"><i
                                class="fa fa-edit"></i>
                        </a>

                        <form action="{{ route('wahanas.destroy', $wahana->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" onclick="return confirm('Yakin Hapus?')"><i
                                    class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
