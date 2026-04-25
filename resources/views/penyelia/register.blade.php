<!doctype html>
<html lang="ms">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pendaftaran Penyelia</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <style>body{font-family:'Poppins',sans-serif}</style>
</head>
<body class="min-h-screen bg-gray-100 p-4 flex items-center justify-center">
  <div class="fixed inset-0 bg-cover bg-center -z-20" style="background-image:url('{{ asset('assets/background.jpg') }}')"></div>
  <div class="fixed inset-0 bg-black/40 -z-10"></div>
  <div class="w-full max-w-2xl rounded-xl bg-white/95 p-5 shadow-2xl">
    <h1 class="text-lg font-bold text-center">PENDAFTARAN PENYELIA UNIT</h1>
    <p class="text-xs text-gray-500 text-center mb-4">Program Orientasi Hospital Baling</p>
    @if($errors->any())<div class="mb-3 rounded border border-red-500 bg-red-100 px-3 py-2 text-xs font-semibold text-red-900">{{ $errors->first() }}</div>@endif
    @if(session('success'))<div class="mb-3 rounded border border-emerald-500 bg-emerald-100 px-3 py-2 text-xs font-semibold text-emerald-900">{{ session('success') }}</div>@endif
    <form method="POST" action="{{ route('register.penyelia.submit') }}" class="space-y-2">@csrf
      <div><label class="text-xs font-semibold">Nama Penuh</label><input name="nama_penuh" value="{{ old('nama_penuh') }}" class="w-full rounded border px-3 py-2 text-sm" required></div>
      <div><label class="text-xs font-semibold">No. IC</label><input name="no_ic" value="{{ old('no_ic') }}" class="w-full rounded border px-3 py-2 text-sm" required></div>
      <div><label class="text-xs font-semibold">Unit/Jabatan</label><select name="unit_jabatan" class="w-full rounded border px-3 py-2 text-sm" required><option value="">-- Pilih Unit/Jabatan --</option>@foreach($unitOptions as $unit)<option value="{{ $unit }}" @selected(old('unit_jabatan')===$unit)>{{ $unit }}</option>@endforeach</select></div>
      <div><label class="text-xs font-semibold">Jawatan</label><input name="jawatan" value="{{ old('jawatan') }}" class="w-full rounded border px-3 py-2 text-sm" required></div>
      <div><label class="text-xs font-semibold">Email</label><input type="email" name="email" value="{{ old('email') }}" class="w-full rounded border px-3 py-2 text-sm" required></div>
      <button class="w-full rounded bg-emerald-600 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Daftar Penyelia</button>
    </form>
    <p class="mt-3 text-center text-xs text-gray-600">Sudah mendaftar? <a class="font-semibold text-blue-600" href="{{ route('login.penyelia') }}">Log Masuk Penyelia</a></p>
  </div>
</body>
</html>
