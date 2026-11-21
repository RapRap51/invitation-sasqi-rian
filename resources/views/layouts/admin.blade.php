<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'Admin - Sasqia & Rian')
    </title>

    <link
        rel="stylesheet"
        href="{{ asset('css/wedding.css') }}"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;500;600&display=swap"
        rel="stylesheet"
    >
</head>

<body class="admin-page">

    <header class="admin-header">

        <div class="admin-header-inner">

            <a
                href="{{ route('admin.dashboard') }}"
                class="admin-brand"
            >
                <div class="admin-logo">
                    S&R
                </div>

                <div>
                    <h1>Sasqia & Rian</h1>

                    <p>
                        Panel Panitia
                    </p>
                </div>
            </a>

            <nav class="admin-nav">

                <a
                    href="{{ route('admin.dashboard') }}"
                    class="admin-nav-link"
                >
                    Dashboard
                </a>

                <a
                    href="{{ route('admin.guests.index') }}"
                    class="admin-nav-link"
                >
                    Tamu
                </a>

                <a
                    href="{{ route('admin.checkin.scanner') }}"
                    class="admin-nav-link"
                >
                    Scanner
                </a>

                <a
                    href="{{ route('admin.checkin.manual') }}"
                    class="admin-nav-link"
                >
                    Manual
                </a>

            </nav>

            <form
                method="POST"
                action="{{ route('logout') }}"
            >
                @csrf

                <button
                    type="submit"
                    class="admin-logout"
                >
                    Logout
                </button>
            </form>

        </div>

    </header>


    <main class="admin-container">

        @if (session('success'))

            <div class="admin-alert admin-alert-success">
                ✓ {{ session('success') }}
            </div>

        @endif


        @if (session('error'))

            <div class="admin-alert admin-alert-error">
                {{ session('error') }}
            </div>

        @endif


        @if ($errors->any())

            <div class="admin-alert admin-alert-error">

                @foreach ($errors->all() as $error)

                    <div>
                        {{ $error }}
                    </div>

                @endforeach

            </div>

        @endif


        @yield('content')

    </main>


    {{-- Script khusus halaman --}}
    @stack('scripts')

</body>

</html>