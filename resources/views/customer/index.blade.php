@extends('layouts.dashboard') {{-- Gunakan hanya satu layout utama --}}

@section('page-heading')
    <h2 class="text-3xl font-bold flex items-center gap-2">
        Data Customer
    </h2>
@endsection

@section('content')
    <div class="col-md-12 mb-4">
        {{-- Tombol untuk menambah data customer --}}
        <a href="{{ route('customers.create') }}" class="btn btn-sm btn-primary">
            <i class="fa fa-plus-circle"></i> Tambah Data
        </a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center">ID</th>
                <th class="text-center">Nama Customer</th>
                <th class="text-center">Username</th>
                <th class="text-center">Email</th>
                <th class="text-center">No HP</th>
                <th class="text-center">Alamat</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
                <tr>
                    <td class="text-center">{{ $customer->id }}</td>
                    <td>{{ $customer->namacustomer }}</td>
                    <td class="text-center">{{ $customer->username }}</td>
                    <td class="text-center">{{ $customer->email }}</td>
                    <td class="text-center">{{ $customer->nohp ?? '-' }}</td>
                    <td>{{ $customer->alamat ?? '-' }}</td>
                    <td class="text-center">
                        <a class="btn btn-warning btn-sm" href="{{ route('customers.edit', $customer->id) }}">
                            <i class="fa fa-edit"></i>
                        </a>

                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data customer ini?')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
