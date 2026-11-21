@extends('layouts.admin')

@section('title', 'QR Undangan - Sasqia & Rian')

@section('content')

@php
    $shortCode = str_replace(
        'RAFIINA-',
        '',
        $guest->unique_code
    );

    $invitationUrl = route(
        'invitation.show',
        ['code' => $shortCode]
    );
@endphp


<div class="admin-page-header">

    <div>

        <h2>QR Code Undangan</h2>

        <p>
            QR Code khusus untuk
            <strong>{{ $guest->name }}</strong>.
        </p>

    </div>

</div>


<div class="admin-qr-page">


    {{-- DATA TAMU --}}

    <div class="admin-card admin-qr-guest-card">

        <div class="admin-guest-avatar admin-qr-avatar">
            {{ mb_strtoupper(mb_substr($guest->name, 0, 1)) }}
        </div>

        <h3>
            {{ $guest->name }}
        </h3>

        <p class="admin-qr-subtitle">
            {{ $guest->invitation_count }} orang
        </p>

        <div class="admin-qr-code-label">
            Kode Undangan
        </div>

        <div class="admin-code">
            {{ $shortCode }}
        </div>

    </div>


    {{-- QR --}}

    <div class="admin-card admin-qr-card">

        <div class="admin-qr-title">
            QR Check-in
        </div>

        <p class="admin-qr-description">
            Tunjukkan QR Code ini kepada panitia
            saat hari H untuk melakukan check-in.
        </p>


        <div class="admin-qr-image">

            {!! QrCode::size(300)->generate($invitationUrl) !!}

        </div>


        <div class="admin-qr-url">

            <span>
                Link Undangan
            </span>

            <div class="admin-copy-link">

                <input
                    type="text"
                    id="invitationUrl"
                    value="{{ $invitationUrl }}"
                    readonly
                >

                <button
                    type="button"
                    id="copyInvitationLink"
                    class="admin-copy-btn"
                >
                    📋 Salin
                </button>

            </div>

            <small
                id="copyStatus"
                class="admin-copy-status"
            ></small>

        </div>


        <div class="admin-qr-actions">

            <button
                type="button"
                class="admin-btn"
                onclick="window.print()"
            >
                🖨 Print QR
            </button>

            <a
                href="{{ route('admin.guests.qr.download', $guest) }}"
                class="admin-btn admin-btn-secondary"
            >
                ↓ Download QR
            </a>

            <a
                href="{{ route('admin.guests.index') }}"
                class="admin-btn admin-btn-secondary"
            >
                ← Kembali
            </a>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>
document
    .getElementById('copyInvitationLink')
    .addEventListener('click', async function () {

        const input =
            document.getElementById('invitationUrl');

        const button =
            document.getElementById('copyInvitationLink');

        const status =
            document.getElementById('copyStatus');

        try {

            await navigator.clipboard.writeText(
                input.value
            );

            button.innerHTML = '✓ Tersalin';

            status.textContent =
                'Link berhasil disalin ke clipboard.';

            setTimeout(() => {

                button.innerHTML = '📋 Salin';

                status.textContent = '';

            }, 2000);

        } catch (error) {

            input.select();

            document.execCommand('copy');

            button.innerHTML = '✓ Tersalin';

            status.textContent =
                'Link berhasil disalin.';

            setTimeout(() => {

                button.innerHTML = '📋 Salin';

                status.textContent = '';

            }, 2000);
        }

    });
</script>

@endpush