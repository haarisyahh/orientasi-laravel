<!doctype html>
<html lang="ms">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Staff Progress</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
  <div class="max-w-6xl mx-auto p-4">
    <div class="mb-4 flex items-center justify-between gap-2 flex-wrap">
      <h1 class="text-xl font-bold">Monitor Progress Staff</h1>
      <a href="{{ route('admin.dashboard') }}" class="rounded bg-slate-700 px-3 py-2 text-xs font-semibold text-white">Kembali Dashboard</a>
    </div>

    <form method="GET" class="mb-4 flex gap-2">
      <input name="search" value="{{ $search }}" class="w-full max-w-md rounded border px-3 py-2 text-sm" placeholder="Cari nama/no ic/unit">
      <button class="rounded bg-blue-600 px-3 py-2 text-sm font-semibold text-white">Cari</button>
    </form>

    <div class="rounded bg-white shadow overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-3 py-2 text-left">Nama</th>
            <th class="px-3 py-2 text-left">No. IC</th>
            <th class="px-3 py-2 text-left">Unit/Jabatan</th>
            <th class="px-3 py-2 text-left">Progress</th>
            <th class="px-3 py-2 text-left">Tarikh/Masa Selesai</th>
            <th class="px-3 py-2 text-left">Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rows as $row)
            @php
              $count = (int)$row->completion_count;
              $status = $count === 0 ? 'BELUM MULA' : ($count >= $totalUnits ? 'SELESAI' : 'SEDANG BERJALAN');
              $completionDate = $row->max_completion_date ? \Carbon\Carbon::parse($row->max_completion_date)->format('d/m/Y H:i') : '-';
            @endphp
            <tr class="border-t">
              <td class="px-3 py-2">{{ $row->nama_penuh }}</td>
              <td class="px-3 py-2">{{ $row->no_ic }}</td>
              <td class="px-3 py-2">{{ $row->unit_jabatan }}</td>
              <td class="px-3 py-2 font-semibold">{{ $count }}/{{ $totalUnits }}</td>
              <td class="px-3 py-2 text-sm">{{ $completionDate }}</td>
              <td class="px-3 py-2">
                <span class="rounded px-2 py-1 text-xs font-semibold {{ $status === 'SELESAI' ? 'bg-emerald-100 text-emerald-700' : ($status === 'BELUM MULA' ? 'bg-indigo-100 text-indigo-700' : 'bg-amber-100 text-amber-700') }}">{{ $status }}</span>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="px-3 py-4 text-center text-gray-500">Tiada data staff.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
