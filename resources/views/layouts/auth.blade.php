<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'Sasqia & Rian')
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

    @yield('content')

</body>

</html>