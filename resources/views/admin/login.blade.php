<!doctype html>
<html lang="ms">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Log Masuk Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <style>body{font-family:'Poppins',sans-serif}</style>
</head>
<body class="min-h-screen bg-gray-100 p-4 flex items-center justify-center">
  <div class="fixed inset-0 bg-cover bg-center -z-20" style="background-image:url('{{ asset('assets/background.jpg') }}')"></div>
  <div class="fixed inset-0 bg-black/40 -z-10"></div>
  <div class="w-full max-w-md rounded-2xl bg-white/95 p-5 shadow-2xl">
    <h1 class="text-xl font-bold text-center">LOG MASUK ADMIN</h1>
    <p class="text-xs text-gray-500 text-center mb-4">Program Orientasi Hospital Baling</p>
    @if($errors->any())<div class="mb-3 rounded border border-red-500 bg-red-100 px-3 py-2 text-xs font-semibold text-red-900">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('login.admin.submit') }}" class="space-y-3">@csrf
      <div><label class="text-xs font-semibold">ID Pengguna / No. IC</label><input name="no_ic" value="{{ old('no_ic') }}" class="w-full rounded border px-3 py-2 text-sm" required></div>
      <div><label class="text-xs font-semibold">Kata Laluan</label><input type="password" name="password" class="w-full rounded border px-3 py-2 text-sm" required></div>
      <button class="w-full rounded bg-emerald-600 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Log Masuk Admin</button>
    </form>
    <p class="mt-3 text-center text-xs text-gray-600"><a class="font-semibold text-blue-600" href="{{ route('home') }}">Kembali ke utama</a></p>
  </div>
</body>
</html>
