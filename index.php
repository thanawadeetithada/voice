<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VOICESRI - Loading...</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap');

    body {
        font-family: 'Sarabun', sans-serif;
    }

    img {
        width: 30%
    }
    </style>
</head>

<body class="bg-emerald-50/30 flex items-center justify-center min-h-screen">

    <div class="text-center animate-pulse">
        <img src="img/logo.png" alt="Logo" class="object-cover mx-auto mb-6">
        <h1 class="text-3xl font-bold text-emerald-800 tracking-tight">VOICE<span class="text-emerald-600">SRI</span>
        </h1>
    </div>

    <script>
    // หน่วงเวลา 2 วินาที (2000 ms) แล้วไปที่หน้า landing.php
    setTimeout(function() {
        window.location.href = 'home.php';
    }, 500);
    </script>
</body>

</html>