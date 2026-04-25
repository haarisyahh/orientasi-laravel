<!doctype html>
<html lang="ms">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Staff Baru - Program Orientasi Hospital Baling</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', Arial, sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gray-100 p-3 flex items-center justify-center">
    <div class="fixed inset-0 bg-cover bg-center -z-20" style="background-image:url('{{ asset('assets/background.jpg') }}')"></div>
    <div class="fixed inset-0 bg-black/40 -z-10"></div>

    <div class="w-full max-w-2xl rounded-xl bg-white/95 shadow-2xl p-5">
        <div class="text-center mb-4">
            <div class="mx-auto mb-2 h-12 w-12 rounded-full bg-white shadow flex items-center justify-center">
                <img src="{{ asset('assets/logo1.png') }}" alt="logo" class="h-11 w-11 rounded-full object-contain" onerror="this.style.display='none'">
            </div>
            <h1 class="text-lg font-bold">PENDAFTARAN STAFF BARU</h1>
            <p class="text-xs text-gray-500">Program Orientasi Hospital Baling</p>
        </div>

        @if ($errors->any())
            <div class="mb-3 rounded border border-red-500 bg-red-100 px-3 py-2 text-xs font-semibold text-red-900">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('success'))
            <div class="mb-3 rounded border border-emerald-500 bg-emerald-100 px-3 py-2 text-xs font-semibold text-emerald-900">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.staff.submit') }}" class="space-y-2">
            @csrf
            <div>
                <label class="mb-1 block text-xs font-semibold text-gray-700" for="nama_penuh">Nama Penuh (Seperti dalam IC)</label>
                <input class="w-full rounded border border-gray-300 px-3 py-2 text-sm" type="text" id="nama_penuh" name="nama_penuh" value="{{ old('nama_penuh') }}" required>
            </div>

            <div>
                <label class="mb-1 block text-xs font-semibold text-gray-700" for="no_ic">ID Pengguna (ID Pengguna adalah No. KP Baru)</label>
                <input class="w-full rounded border border-gray-300 px-3 py-2 text-sm" type="text" id="no_ic" name="no_ic" value="{{ old('no_ic') }}" required>
            </div>

            <div>
                <label class="mb-1 block text-xs font-semibold text-gray-700" for="unit_jabatan">Unit/Jabatan</label>
                <select class="w-full rounded border border-gray-300 px-3 py-2 text-sm" id="unit_jabatan" name="unit_jabatan" required>
                    <option value="">-- Pilih Unit/Jabatan --</option>
                    @foreach($unitOptions as $unit)
                        <option value="{{ $unit }}" @selected(old('unit_jabatan') === $unit)>{{ $unit }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1 block text-xs font-semibold text-gray-700" for="jawatan">Jawatan</label>
                <input class="w-full rounded border border-gray-300 px-3 py-2 text-sm" type="text" id="jawatan" name="jawatan" value="{{ old('jawatan') }}" required>
            </div>

            <div>
                <label class="mb-1 block text-xs font-semibold text-gray-700" for="email">Alamat Emel Rasmi/Peribadi</label>
                <input class="w-full rounded border border-gray-300 px-3 py-2 text-sm" type="email" id="email" name="email" value="{{ old('email') }}" required>
            </div>

            <button type="submit" class="mt-2 w-full rounded bg-emerald-600 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Daftar</button>
        </form>

        <p class="mt-3 text-center text-xs text-gray-600">
            Sudah mendaftar? Sila klik <a href="{{ route('login.staff') }}" class="font-semibold text-emerald-600 hover:underline">Log Masuk</a> untuk log masuk.
        </p>
    </div>
</body>
</html>
