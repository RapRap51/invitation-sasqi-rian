@extends('layouts.auth')

@section('title', 'Login Panitia - Sasqia & Rian')

@section('content')

<div class="admin-login-page">

    <div class="admin-login-card">

        <div class="admin-login-brand">

            <div class="admin-login-logo">
                S&R
            </div>

            <h1>
                Sasqia & Rian
            </h1>

            <p>
                Panel Panitia
            </p>

        </div>


        @if ($errors->any())

            <div class="admin-alert admin-alert-error">

                @foreach ($errors->all() as $error)

                    <div>
                        {{ $error }}
                    </div>

                @endforeach

            </div>

        @endif


        <form
            method="POST"
            action="{{ route('login.process') }}"
            class="admin-login-form"
        >

            @csrf


            <div class="admin-form-group">

                <label class="admin-label">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    class="admin-input"
                    value="{{ old('email') }}"
                    placeholder="Masukkan email"
                    autocomplete="email"
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
                    placeholder="Masukkan password"
                    autocomplete="current-password"
                    required
                >

            </div>


            <button
                type="submit"
                class="admin-btn admin-login-button"
            >
                Masuk ke Panel
            </button>

        </form>


        <div class="admin-login-footer">
            Sasqia & Rian Wedding
        </div>

    </div>

</div>

@endsection