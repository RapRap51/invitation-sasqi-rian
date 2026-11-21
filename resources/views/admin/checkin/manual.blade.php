<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Check-in Manual - Sasqia & Rian</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f5f5f5;
            font-family: Arial, sans-serif;
            color: #333;
        }

        .header {
            background: #b08a5b;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .container {
            max-width: 700px;
            margin: auto;
            padding: 20px;
        }

        .search-card,
        .guest-card {
            background: white;
            border-radius: 18px;
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
        }

        .search-form {
            display: flex;
            gap: 10px;
        }

        .search-form input {
            flex: 1;
            padding: 14px 16px;
            border: 1px solid #ddd;
            border-radius: 12px;
            font-size: 15px;
        }

        .search-form button {
            border: none;
            background: #b08a5b;
            color: white;
            padding: 0 20px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: bold;
        }

        .guest-card h2 {
            margin: 0 0 12px;
        }

        .guest-info {
            margin: 7px 0;
            color: #666;
        }

        .status {
            margin-top: 15px;
            padding: 12px;
            border-radius: 10px;
            text-align: center;
            font-weight: bold;
        }

        .status.pending {
            background: #fff3cd;
            color: #856404;
        }

        .status.arrived {
            background: #d1e7dd;
            color: #0f5132;
        }

        .btn-checkin {
            width: 100%;
            margin-top: 15px;
            padding: 15px;
            border: none;
            border-radius: 12px;
            background: #198754;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-back {
            display: block;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
            color: #b08a5b;
        }

        .empty {
            text-align: center;
            color: #888;
            padding: 20px;
        }

        .success {
            background: #d1e7dd;
            color: #0f5132;
            padding: 14px;
            border-radius: 12px;
            margin-bottom: 16px;
        }

        @media (max-width: 600px) {
            .search-form {
                flex-direction: column;
            }

            .search-form button {
                padding: 14px;
            }
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Check-in Manual</h1>
        <div>Sasqia & Rian</div>
    </div>

    <div class="container">

        @if (session('success'))
            <div class="success">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div style="
                background:#f8d7da;
                color:#842029;
                padding:14px;
                border-radius:12px;
                margin-bottom:16px;
            ">
                {{ session('error') }}
            </div>
        @endif


        <div class="search-card">

            <h2>Cari Tamu</h2>

            <form
                method="GET"
                action="{{ route('admin.checkin.manual.search') }}"
                class="search-form"
            >

                <input
                    type="text"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Cari nama atau nomor HP..."
                    required
                    autofocus
                >

                <button type="submit">
                    Cari
                </button>

            </form>

        </div>


        @isset($guests)

            @forelse ($guests as $guest)

                <div class="guest-card">

                    <h2>
                        {{ $guest->name }}
                    </h2>

                    <div class="guest-info">
                        No HP:
                        <strong>
                            {{ $guest->phone ?: '-' }}
                        </strong>
                    </div>

                    <div class="guest-info">
                        Jumlah Undangan:
                        <strong>
                            {{ $guest->invitation_count }}
                            orang
                        </strong>
                    </div>

                    <div class="guest-info">
                        Kode:
                        <strong>
                            {{ $guest->unique_code }}
                        </strong>
                    </div>


                    @if ($guest->checkin_status === 'arrived')

                        <div class="status arrived">
                            ✓ SUDAH CHECK-IN
                        </div>

                    @else

                        <div class="status pending">
                            BELUM CHECK-IN
                        </div>

                        <form
                            method="POST"
                            action="{{ route('admin.checkin.confirm', $guest) }}"
                            onsubmit="return confirm(
                                'Konfirmasi check-in {{ $guest->name }}?'
                            )"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn-checkin"
                            >
                                ✓ CHECK-IN MANUAL
                            </button>

                        </form>

                    @endif

                </div>

            @empty

                <div class="guest-card empty">
                    Tidak ditemukan tamu dengan
                    pencarian:

                    <strong>
                        {{ $search }}
                    </strong>
                </div>

            @endforelse

        @endisset


        <a
            href="{{ route('admin.dashboard') }}"
            class="btn-back"
        >
            ← Kembali ke Dashboard
        </a>

    </div>

</body>

</html>