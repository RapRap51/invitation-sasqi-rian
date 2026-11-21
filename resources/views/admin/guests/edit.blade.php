<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Tamu</title>
</head>

<body>

    <h1>Edit Tamu</h1>

    @if ($errors->any())
        <div style="color: red;">

            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach

        </div>
    @endif

    <form
        method="POST"
        action="{{ route('admin.guests.update', $guest) }}"
    >

        @csrf

        @method('PUT')

        <div>
            <label>Nama</label>

            <br>

            <input
                type="text"
                name="name"
                value="{{ old('name', $guest->name) }}"
                required
            >
        </div>

        <br>

        <div>
            <label>No HP</label>

            <br>

            <input
                type="text"
                name="phone"
                value="{{ old('phone', $guest->phone) }}"
            >
        </div>

        <br>

        <div>
            <label>Jumlah Undangan</label>

            <br>

            <input
                type="number"
                name="invitation_count"
                min="1"
                max="20"
                value="{{ old('invitation_count', $guest->invitation_count) }}"
                required
            >
        </div>

        <br>

        <button type="submit">
            Simpan Perubahan
        </button>

        <a href="{{ route('admin.guests.index') }}">
            Batal
        </a>

    </form>

</body>

</html>