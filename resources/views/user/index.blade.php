@extends('layouts.main', ['title' => 'User'])

@section('title-content')
    <i class="fas fa-user-tie mr-2"></i> User
@endsection

@section('content')
    @if (session('store') === 'success')
        <x-alert type="success">
            <strong>Berhasil dibuat!</strong> User berhasil dibuat.
        </x-alert>
    @endif

    @if (session('update') === 'success')
        <x-alert type="success">
            <strong>Berhasil diupdate!</strong> User berhasil diupdate.
        </x-alert>
    @endif

    @if (session('destroy') === 'success')
        <x-alert type="success">
            <strong>Berhasil dihapus!</strong> User berhasil dihapus.
        </x-alert>
    @endif

    <div class="card card-orange card-outline">
        <div class="card-header form-inline">
            <a href="{{ route('user.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i> Tambah
            </a>
            <form action="{{ route('user.index') }}" method="get" class="ml-auto">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" value="{{ request()->search }}"
                        placeholder="Nama, Username">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $key => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $key }}</td>
                            <td>{{ $user->nama }}</td>
                            <td>{{ $user->username }}</td>
                            <td class="text-right">
                                <a href="{{ route('user.edit', ['user' => $user->id]) }}"
                                    class="btn btn-xs text-success p-1 mr-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" data-toggle="modal" data-target="#modalDelete"
                                    data-url="{{ route('user.destroy', ['user' => $user->id]) }}"
                                    class="btn btn-xs text-danger p-1 btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Data user tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="card-footer">
                {{ $users->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection

@push('modals')
    <x-modal-delete />
@endpush

@push('scripts')
<script>
    $(function () {
        $('#modalDelete').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var url = button.data('url');
            $(this).find('form#formDelete').attr('action', url);
        });

        $('#yesDelete').on('click', function () {
            $('#formDelete').submit();
        });
    });
</script>
@endpush
