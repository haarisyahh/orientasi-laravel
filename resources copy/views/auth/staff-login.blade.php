<!doctype html>
<html lang="ms">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log Masuk - Program Orientasi Hospital Baling</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', Arial, sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gray-100 p-3 flex items-center justify-center">
    <div class="fixed inset-0 bg-cover bg-center -z-20" style="background-image:url('{{ asset('assets/background.jpg') }}')"></div>
    <div class="fixed inset-0 bg-black/40 -z-10"></div>

    <div class="w-full max-w-md rounded-2xl bg-white/95 shadow-2xl p-5">
        <div class="text-center mb-4">
            <div class="mx-auto mb-2 h-16 w-16 rounded-full bg-white shadow flex items-center justify-center">
                <img src="{{ asset('assets/logo1.png') }}" alt="logo" class="h-14 w-14 rounded-full object-contain" onerror="this.style.display='none'">
            </div>
            <h1 class="text-xl font-bold">LOG MASUK STAFF BARU</h1>
            <p class="text-xs text-gray-500">Program Orientasi Hospital Baling</p>
        </div>

        @if ($errors->any())
            <div class="mb-3 rounded border border-red-500 bg-red-100 px-3 py-2 text-xs font-semibold text-red-900">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.staff.submit') }}" class="space-y-3">
            @csrf
            <div>
                <label class="mb-1 block text-xs font-semibold text-gray-700" for="no_ic">ID Pengguna / No. IC</label>
                <input class="w-full rounded border border-gray-300 px-3 py-2 text-sm" type="text" id="no_ic" name="no_ic" value="{{ old('no_ic') }}" required autofocus>
            </div>

            <div>
                <label class="mb-1 block text-xs font-semibold text-gray-700" for="password">Kata Laluan</label>
                <input class="w-full rounded border border-gray-300 px-3 py-2 text-sm" type="password" id="password" name="password" required>
            </div>

            <div class="rounded bg-blue-100 px-3 py-2 text-xs text-blue-900 border-l-4 border-blue-500">
                <strong>Nota:</strong> Kata laluan standard bagi semua pengguna adalah <strong>User123</strong>. Sila pastikan No. IC dimasukkan tanpa tanda (-).
            </div>

            <button type="submit" class="w-full rounded bg-emerald-600 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Log Masuk</button>
        </form>

        <p class="mt-3 text-center text-xs text-gray-600">
            Belum mendaftar? Sila klik <a href="{{ route('register.staff') }}" class="font-semibold text-blue-600 hover:underline">Daftar</a> untuk daftar pengguna baru.
        </p>
    </div>
</body>
</html>
