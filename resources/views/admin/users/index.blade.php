@extends('layouts.admin')

@section('title', 'Kelola Petugas - Sasqia & Rian')

@section('content')

<div class="admin-page-header">

    <div>
        <h2>Kelola Akun</h2>

        <p>
            Kelola akun admin dan petugas penerima tamu.
        </p>
    </div>

    <a
        href="{{ route('admin.users.create') }}"
        class="admin-btn"
    >
        ＋ Tambah Akun
    </a>

</div>


<div class="admin-card">

    <div class="admin-table-wrapper">

        <table class="admin-table">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($users as $user)

                    <tr>

                        <td>
                            {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                        </td>

                        <td>
                            <strong>
                                {{ $user->name }}
                            </strong>
                        </td>

                        <td>
                            {{ $user->email }}
                        </td>

                        <td>

                            @if ($user->role === 'admin')

                                <span class="admin-badge confirmed">
                                    Admin
                                </span>

                            @else

                                <span class="admin-badge pending">
                                    Petugas
                                </span>

                            @endif

                        </td>

                        <td>

                            <div class="admin-table-actions">

                                <a
                                    href="{{ route('admin.users.edit', $user) }}"
                                    class="admin-action"
                                >
                                    Edit
                                </a>

                                @if ($user->id !== auth()->id())

                                    <form
                                        method="POST"
                                        action="{{ route('admin.users.destroy', $user) }}"
                                        onsubmit="return confirm('Hapus akun ini?')"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="admin-action admin-action-danger"
                                        >
                                            Hapus
                                        </button>

                                    </form>

                                @endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="admin-empty"
                        >
                            Belum ada akun.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="admin-pagination">
        {{ $users->links() }}
    </div>

</div>

@endsection