@extends('layouts.admin')

@section('title', 'Dashboard - Sasqia & Rian')

@section('content')

<div class="admin-page-header">

    <div>
        <h2>Dashboard</h2>

        <p>
            Selamat datang,
            <strong>{{ auth()->user()->name }}</strong>
        </p>
    </div>

</div>


<div class="admin-stat-grid">

    <div class="admin-stat-card">
        <div class="admin-stat-icon">👥</div>

        <div>
            <div class="admin-stat-label">
                Total Undangan
            </div>

            <div class="admin-stat-number">
                {{ $totalGuests }}
            </div>
        </div>
    </div>


    <div class="admin-stat-card">
        <div class="admin-stat-icon">🧑‍🤝‍🧑</div>

        <div>
            <div class="admin-stat-label">
                Total Orang
            </div>

            <div class="admin-stat-number">
                {{ $totalPeople }}
            </div>
        </div>
    </div>


    <div class="admin-stat-card admin-stat-success">
        <div class="admin-stat-icon">✓</div>

        <div>
            <div class="admin-stat-label">
                RSVP Hadir
            </div>

            <div class="admin-stat-number">
                {{ $confirmed }}
            </div>
        </div>
    </div>


    <div class="admin-stat-card admin-stat-arrived">
        <div class="admin-stat-icon">🎉</div>

        <div>
            <div class="admin-stat-label">
                Check-in
            </div>

            <div class="admin-stat-number">
                {{ $arrived }}
            </div>
        </div>
    </div>


    <div class="admin-stat-card admin-stat-arrived">
        <div class="admin-stat-icon">❤️</div>

        <div>
            <div class="admin-stat-label">
                Orang Sudah Hadir
            </div>

            <div class="admin-stat-number">
                {{ $arrivedPeople }}
            </div>
        </div>
    </div>


    <div class="admin-stat-card admin-stat-warning">
        <div class="admin-stat-icon">⏳</div>

        <div>
            <div class="admin-stat-label">
                Orang Belum Hadir
            </div>

            <div class="admin-stat-number">
                {{ $totalPeople - $arrivedPeople }}
            </div>
        </div>
    </div>

</div>


<h2 class="admin-section-title">
    Menu Panitia
</h2>


<div class="admin-menu-grid">

    {{-- KELOLA AKUN: HANYA ADMIN --}}
    @if (auth()->user()->role === 'admin')

        <a
            href="{{ route('admin.users.index') }}"
            class="admin-menu-card admin-menu-purple"
        >

            <div class="admin-menu-icon">
                👤
            </div>

            <div>

                <h3>
                    Kelola Akun
                </h3>

                <p>
                    Tambah dan kelola akun admin
                    serta petugas.
                </p>

            </div>

        </a>

    @endif


    {{-- SCANNER --}}
    <a
        href="{{ route('admin.checkin.scanner') }}"
        class="admin-menu-card admin-menu-green"
    >

        <div class="admin-menu-icon">
            📷
        </div>

        <div>

            <h3>
                Scanner QR
            </h3>

            <p>
                Scan QR Code tamu untuk check-in.
            </p>

        </div>

    </a>


    {{-- CHECK-IN MANUAL --}}
    <a
        href="{{ route('admin.checkin.manual') }}"
        class="admin-menu-card admin-menu-brown"
    >

        <div class="admin-menu-icon">
            ✍️
        </div>

        <div>

            <h3>
                Check-in Manual
            </h3>

            <p>
                Cari nama atau nomor HP tamu.
            </p>

        </div>

    </a>


    {{-- DAFTAR TAMU: HANYA ADMIN --}}
    @if (auth()->user()->role === 'admin')

        <a
            href="{{ route('admin.guests.index') }}"
            class="admin-menu-card admin-menu-gold"
        >

            <div class="admin-menu-icon">
                👥
            </div>

            <div>

                <h3>
                    Daftar Tamu
                </h3>

                <p>
                    Kelola data undangan.
                </p>

            </div>

        </a>


        {{-- IMPORT EXCEL: HANYA ADMIN --}}
        <a
            href="{{ route('admin.guests.import.form') }}"
            class="admin-menu-card admin-menu-blue"
        >

            <div class="admin-menu-icon">
                📊
            </div>

            <div>

                <h3>
                    Import Excel
                </h3>

                <p>
                    Tambahkan data melalui Excel.
                </p>

            </div>

        </a>

    @endif

</div>

@endsection