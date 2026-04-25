<!doctype html>
<html lang="ms">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <style>body{font-family:'Poppins',sans-serif}</style>
</head>
<body class="min-h-screen bg-gray-100">
  <div class="bg-blue-900 text-white px-5 py-4 flex items-center justify-between gap-3 flex-wrap">
    <div>
      <h1 class="text-xl font-bold">Dashboard Admin</h1>
      <p class="text-xs opacity-90">Selamat datang, {{ $adminName }}</p>
    </div>
    <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="rounded bg-red-600 px-3 py-2 text-xs font-semibold hover:bg-red-700">Log Keluar</button></form>
  </div>

  <div class="max-w-6xl mx-auto p-4 space-y-4">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
      <div class="rounded bg-white-50 p-4 shadow text-center"><div class="text-xs text-gray-500">Staff Baru</div><div class="text-2xl font-bold text-blue-600">{{ $staffCount }}</div></div>
      <div class="rounded bg-white-50 p-4 shadow text-center"><div class="text-xs text-gray-500">Penyelia</div><div class="text-2xl font-bold text-amber-600">{{ $penyeliaCount }}</div></div>
      <div class="rounded bg-white-50 p-4 shadow text-center"><div class="text-xs text-gray-500">Selesai</div><div class="text-2xl font-bold text-indigo-600">{{ $completedUsersCount }}</div></div>
    </div>

    <div class="rounded bg-white p-4 shadow">
      <h2 class="font-semibold mb-3">Fungsi Admin</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        @php
          $linkColors = [
            'Staff Progress' => 'border-blue-200 bg-blue-50 text-blue-800 hover:bg-blue-100',
            'Reset Unit User' => 'border-amber-200 bg-amber-50 text-amber-800 hover:bg-amber-100',
            'Pengurusan Staff' => 'border-emerald-200 bg-emerald-50 text-emerald-800 hover:bg-emerald-100',
            'Pengurusan Penyelia' => 'border-purple-200 bg-purple-50 text-purple-800 hover:bg-purple-100',
            'Profil Admin' => 'border-indigo-200 bg-indigo-50 text-indigo-800 hover:bg-indigo-100',
            'Tandakan Unit Selesai - untuk testing sahaja' => 'border-green-200 bg-green-50 text-green-800 hover:bg-green-100',
            'Kemaskini Slaid / Kandungan Unit' => 'border-rose-200 bg-rose-50 text-rose-800 hover:bg-rose-100',
          ];
        @endphp
        @foreach($links as $label => $url)
          <a href="{{ $url }}" target="_blank" class="rounded border px-4 py-3 text-sm font-semibold transition {{ $linkColors[$label] ?? 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50' }}">{{ $label }}</a>
        @endforeach
      </div>
      <p class="mt-3 text-xs text-gray-500">Pautan akan buka tab baru untuk memudahkan semakan rentas modul.</p>
    </div>
  </div>
</body>
</html>
