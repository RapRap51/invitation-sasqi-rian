@extends('layouts.admin')

@section('title', 'Tambah Akun - Sasqia & Rian')

@section('content')

<div class="admin-page-header">

    <div>
        <h2>Tambah Akun</h2>

        <p>
            Buat akun admin atau petugas penerima tamu.
        </p>
    </div>

</div>


<div class="admin-card admin-form-card">

    <form
        method="POST"
        action="{{ route('admin.users.store') }}"
    >

        @csrf

        <div class="admin-form-group">

            <label class="admin-label">
                Nama
            </label>

            <input
                type="text"
                name="name"
                class="admin-input"
                value="{{ old('name') }}"
                placeholder="Nama petugas"
                required
            >

        </div>


        <div class="admin-form-group">

            <label class="admin-label">
                Email
            </label>

            <input
                type="email"
                name="email"
                class="admin-input"
                value="{{ old('email') }}"
                placeholder="email@contoh.com"
                required
            >

        </div>


        <div class="admin-form-group">

            <label class="admin-label">
                Password
            </label>

            <input
                type="password"
                name="password"
                class="admin-input"
                required
            >

            <small class="admin-help">
                Minimal 8 karakter.
            </small>

        </div>


        <div class="admin-form-group">

            <label class="admin-label">
                Role
            </label>

            <select
                name="role"
                class="admin-select"
                required
            >

                <option value="petugas">
                    Petugas
                </option>

                <option value="admin">
                    Admin
                </option>

            </select>

        </div>


        <div class="admin-form-actions">

            <button
                type="submit"
                class="admin-btn"
            >
                Simpan Akun
            </button>

            <a
                href="{{ route('admin.users.index') }}"
                class="admin-btn admin-btn-secondary"
            >
                Batal
            </a>

        </div>

    </form>

</div>

@endsection