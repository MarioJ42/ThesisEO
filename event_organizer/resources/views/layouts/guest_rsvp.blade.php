<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fenix EO - Digital Guestbook</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        .font-serif { font-family: 'Playfair Display', serif; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="text-gray-900 antialiased">
    <main class="w-full min-h-screen">
        @yield('content')
    </main>
</body>
</html>
