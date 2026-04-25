<!doctype html>
<html lang="ms">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kemaskini Slaid Unit</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <style>
    body{font-family:'Poppins',sans-serif}
    .admin-reminder {
      animation: reminderPulse 2.2s ease-in-out infinite;
    }
    @keyframes reminderPulse {
      0%, 100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.15); transform: translateY(0); }
      50% { box-shadow: 0 0 0 8px rgba(245, 158, 11, 0.08); transform: translateY(-1px); }
    }
    @media (prefers-reduced-motion: reduce) {
      .admin-reminder { animation: none; }
    }
  </style>
</head>
<body class="min-h-screen bg-gray-100">
  <div class="bg-blue-900 text-white px-5 py-4 flex items-center justify-between gap-3 flex-wrap">
    <div>
      <h1 class="text-xl font-bold">Kemaskini Slaid / Kandungan Unit</h1>
      <p class="text-xs opacity-90">Admin: {{ $adminName }}</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="rounded bg-white/20 px-3 py-2 text-xs font-semibold hover:bg-white/30">Kembali Dashboard</a>
  </div>

  <div class="max-w-6xl mx-auto p-4 space-y-4">
    @if(session('status'))
      <div class="rounded border border-green-200 bg-green-50 p-3 text-sm text-green-800">{{ session('status') }}</div>
    @endif

    @if($errors->any())
      <div class="rounded border border-red-200 bg-red-50 p-3 text-sm text-red-800">
        <ul class="list-disc pl-5 space-y-1">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="admin-reminder rounded border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900 leading-relaxed">
      <p><strong>⚠️ Peringatan Admin:</strong></p>
      <p>
        Untuk bahagian Kemaskini Slaid, jangan gantikan slaid yang mengandungi pautan laman web (link).
        Ini kerana slaid yang dimuat naik adalah dalam format PNG (imej statik), jadi pautan tidak boleh diklik
        dan pengguna tidak dapat membuka laman tersebut. Jika kandungan memerlukan link aktif, sila kekalkan
        versi asal atau rujuk pembangun sistem.
      </p>
    </div>

    <div class="rounded bg-white p-4 shadow space-y-3">
      <h2 class="font-semibold">Pilih Unit</h2>
      <form method="GET" action="{{ route('admin.slide-content') }}" class="flex flex-col sm:flex-row gap-3 sm:items-end">
        <div class="flex-1">
          <label class="block text-xs font-semibold text-gray-600 mb-1">Unit / Jabatan</label>
          <select name="unit_slug" class="w-full rounded border border-gray-300 px-3 py-2 text-sm" onchange="this.form.submit()">
            @foreach($units as $slug => $unit)
              <option value="{{ $slug }}" @selected($selectedUnitSlug === $slug)>{{ $unit['label'] }}</option>
            @endforeach
          </select>
        </div>
        <button class="rounded bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Papar</button>
      </form>
      <p class="text-xs text-gray-500">Folder aktif: <strong>{{ $selectedUnit['folder'] }}</strong></p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
      <div class="rounded bg-white p-4 shadow space-y-3">
        <h2 class="font-semibold">Tambah / Ganti Slaid</h2>
        <form method="POST" action="{{ route('admin.slide-content.upload') }}" enctype="multipart/form-data" class="space-y-3">
          @csrf
          <input type="hidden" name="unit_slug" value="{{ $selectedUnitSlug }}">

          <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Fail Slaid (Format PNG dan JPG sahaja, max 10MB)</label>
            <input type="file" name="slide_file" accept=".png,.jpg" required class="w-full rounded border border-gray-300 px-3 py-2 text-sm bg-white">
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Nombor Slaid (untuk tujuan gantian slaid sahaja)</label>
            <input type="number" name="slide_number" min="1" class="w-full rounded border border-gray-300 px-3 py-2 text-sm" placeholder="Kosongkan untuk tambah di hujung">
            
          </div>

          <button class="rounded bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Simpan Slaid</button>
        </form>
      </div>

      <div class="rounded bg-white p-4 shadow space-y-3">
        <h2 class="font-semibold">Senarai Slaid ({{ count($slides) }})</h2>
        <p class="text-xs text-gray-500">Padam slaid akan auto susun semula nombor supaya kekal berturutan.</p>
        <div class="max-h-[440px] overflow-auto border rounded">
          <table class="min-w-full text-sm">
            <thead class="bg-gray-50 sticky top-0">
              <tr>
                <th class="px-3 py-2 text-left">No</th>
                <th class="px-3 py-2 text-left">Fail</th>
                <th class="px-3 py-2 text-left">Tindakan</th>
              </tr>
            </thead>
            <tbody>
              @forelse($slides as $slide)
                <tr class="border-t">
                  <td class="px-3 py-2 font-semibold">{{ $slide['number'] }}</td>
                  <td class="px-3 py-2">
                    <div class="font-medium text-xs text-gray-700">{{ $slide['filename'] }}</div>
                    <a href="{{ $slide['url'] }}" target="_blank" class="text-xs text-blue-600 hover:underline">Preview</a>
                  </td>
                  <td class="px-3 py-2">
                    <form method="POST" action="{{ route('admin.slide-content.delete') }}" onsubmit="return confirm('Padam slaid #{{ $slide['number'] }}?');">
                      @csrf
                      <input type="hidden" name="unit_slug" value="{{ $selectedUnitSlug }}">
                      <input type="hidden" name="slide_number" value="{{ $slide['number'] }}">
                      <button class="rounded bg-red-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-700">Padam</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="px-3 py-4 text-center text-sm text-gray-500">Belum ada slaid dalam unit ini.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
