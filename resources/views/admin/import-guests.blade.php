@extends('layouts.admin')

@section('title', 'Import Tamu - Sasqia & Rian')

@section('content')

<div class="admin-page-header">

    <div>
        <h2>Import Daftar Tamu</h2>

        <p>
            Tambahkan tamu melalui file Excel.
        </p>
    </div>

</div>


<div class="admin-form-card admin-card">

    <form
        action="{{ route('admin.guests.import') }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf

        <div class="admin-form-group">

            <label class="admin-label">
                File Excel
            </label>

            <input
                type="file"
                name="file"
                class="admin-input"
                accept=".xlsx,.xls,.csv"
                required
            >

            <small class="admin-help">
                Format: .xlsx, .xls, atau .csv
            </small>

        </div>


        <div class="admin-form-actions">

            <button
                type="submit"
                class="admin-btn"
            >
                Import Excel
            </button>

            <a
                href="{{ route('admin.guests.template') }}"
                class="admin-btn admin-btn-secondary"
            >
                Download Template
            </a>

        </div>

    </form>

</div>

@endsection