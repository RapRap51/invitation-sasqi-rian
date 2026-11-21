@extends('layouts.admin')

@section('title', 'Daftar Tamu - Sasqia & Rian')

@section('content')

<div class="admin-page-header">

    <div>
        <h2>Daftar Tamu</h2>

        <p>
            Kelola seluruh undangan Sasqia & Rian.
        </p>
    </div>

    <div class="admin-page-actions">

        <a
            href="{{ route('admin.guests.import.form') }}"
            class="admin-btn"
        >
            ＋ Import Excel
        </a>

        <a
            href="{{ route('admin.guests.template') }}"
            class="admin-btn admin-btn-secondary"
        >
            Template
        </a>

        <a
            href="{{ route('admin.guests.export') }}"
            class="admin-btn admin-btn-secondary"
        >
            Export Excel
        </a>

    </div>

</div>


<div class="admin-card">

    <div class="admin-table-wrapper">

        <table class="admin-table">

            <thead>

                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>No HP</th>
                    <th>Jumlah</th>
                    <th>Kode</th>
                    <th>RSVP</th>
                    <th>Check-in</th>
                    <th>Aksi</th>
                </tr>

            </thead>

            <tbody>

                @forelse ($guests as $guest)

                    <tr>

                        <td>
                            {{ $loop->iteration + ($guests->currentPage() - 1) * $guests->perPage() }}
                        </td>

                        <td>
                            <strong>
                                {{ $guest->name }}
                            </strong>
                        </td>

                        <td>
                            {{ $guest->phone ?? '-' }}
                        </td>

                        <td>
                            {{ $guest->invitation_count }}
                        </td>

                        <td>
                            <span class="admin-code">
                                {{ str_replace('RAFIINA-', '', $guest->unique_code) }}
                            </span>
                        </td>

                        <td>
                            <span
                                class="admin-badge
                                {{ $guest->rsvp_status }}"
                            >
                                {{ $guest->rsvp_status }}
                            </span>
                        </td>

                        <td>

                            @if ($guest->checkin_status === 'arrived')

                                <span class="admin-badge arrived">
                                    Hadir
                                </span>

                            @else

                                <span class="admin-badge pending">
                                    Belum
                                </span>

                            @endif

                        </td>

                        <td>

                            <div class="admin-table-actions">

                                <a
                                    href="{{ route('admin.guests.qr', $guest) }}"
                                    class="admin-action"
                                >
                                    QR
                                </a>

                                <a
                                    href="{{ route('admin.guests.edit', $guest) }}"
                                    class="admin-action"
                                >
                                    Edit
                                </a>

                                <form
                                    action="{{ route('admin.guests.destroy', $guest) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus tamu ini?')"
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

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="8"
                            class="admin-empty"
                        >
                            Belum ada data tamu.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    <div class="admin-pagination">
        {{ $guests->links() }}
    </div>

</div>

@endsection