<!doctype html>
<html lang="ms">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Pengurusan Staff</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @media print {
      .no-print { display: none !important; }
      body { background: white !important; }
      .print-container { max-width: 100% !important; padding: 0 !important; }
      .shadow-sm, .shadow-lg, .shadow-xl { box-shadow: none !important; }
      table { font-size: 11px !important; }
      th, td { padding: 4px 6px !important; }
      .progress-bar-wrap { display: none !important; }
    }
  </style>
</head>
<body class="min-h-screen bg-slate-100 text-slate-800">
  @php
    $unitTarget = (int) ($totalUnits ?? 11);
    $totalStaff = $rows->count();
    $selesaiCount = $rows->filter(fn ($r) => (int) $r->completion_count >= $unitTarget)->count();
    $berjalanCount = $rows->filter(fn ($r) => (int) $r->completion_count > 0 && (int) $r->completion_count < $unitTarget)->count();
    $belumCount = $rows->filter(fn ($r) => (int) $r->completion_count === 0)->count();
  @endphp

  <div class="mx-auto max-w-7xl space-y-4 p-4 sm:p-6 print-container">
    <div class="rounded-2xl bg-gradient-to-r from-slate-900 via-blue-900 to-slate-900 p-5 text-white shadow-lg">
      <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
          <h1 class="text-2xl font-bold tracking-tight">Pengurusan Staff</h1>
          <p class="mt-1 text-sm text-slate-200">Pantau progress orientasi dan urus maklumat staff dalam satu halaman.</p>
        </div>
        <div class="no-print flex gap-2 flex-wrap">
          <button onclick="window.print()" class="rounded-lg bg-white/15 px-4 py-2 text-xs font-semibold text-white transition hover:bg-white/25">Cetak</button>
          <a href="{{ route('admin.pengurusan-staff.export-csv', $search ? ['search' => $search] : []) }}" class="rounded-lg bg-emerald-500 px-4 py-2 text-xs font-semibold text-white transition hover:bg-emerald-600">Export CSV</a>
          <a href="{{ route('admin.dashboard') }}" class="rounded-lg bg-white/15 px-4 py-2 text-xs font-semibold text-white transition hover:bg-white/25">Kembali Dashboard</a>
        </div>
      </div>
    </div>

    <div class="hidden print:block text-center">
      <h1 class="text-xl font-bold">Laporan Pengurusan Staff</h1>
      <p class="text-sm text-slate-500">Dicetak pada {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
      <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Jumlah Staff</p>
        <p class="mt-2 text-2xl font-bold text-slate-800">{{ $totalStaff }}</p>
      </div>
      <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Selesai</p>
        <p class="mt-2 text-2xl font-bold text-emerald-800">{{ $selesaiCount }}</p>
      </div>
      <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-amber-700">Sedang Berjalan</p>
        <p class="mt-2 text-2xl font-bold text-amber-800">{{ $berjalanCount }}</p>
      </div>
      <div class="rounded-xl border border-indigo-200 bg-indigo-50 p-4 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-indigo-700">Belum Mula</p>
        <p class="mt-2 text-2xl font-bold text-indigo-800">{{ $belumCount }}</p>
      </div>
    </div>

    @if(session('status'))
      <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</div>
    @endif

    @if($errors->any())
      <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        <ul class="list-disc list-inside space-y-1">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm no-print">
      <form method="GET" class="grid grid-cols-1 gap-3 sm:grid-cols-[1fr_auto_auto] sm:items-center">
        <div>
          <label for="search" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Carian</label>
          <input id="search" name="search" value="{{ $search }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" placeholder="Cari nama, no. ic, atau unit jabatan">
        </div>
        <button class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">Cari</button>
        <a href="{{ route('admin.pengurusan-staff') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Set Semula</a>
      </form>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
      @if($search)
        <div class="no-print border-b border-blue-100 bg-blue-50 px-4 py-2 text-sm text-blue-700">
          Keputusan carian untuk <strong>{{ $search }}</strong> — {{ $rows->count() }} rekod dijumpai
        </div>
      @endif

      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-slate-100 text-slate-700">
            <tr>
              <th class="px-4 py-3 text-left font-semibold">Nama</th>
              <th class="px-4 py-3 text-left font-semibold">No. IC</th>
              <th class="px-4 py-3 text-left font-semibold">Unit/Jabatan</th>
              <th class="px-4 py-3 text-left font-semibold">Jawatan</th>
              <th class="px-4 py-3 text-left font-semibold">Email</th>
              <th class="px-4 py-3 text-left font-semibold">Tarikh Daftar</th>
              <th class="px-4 py-3 text-left font-semibold">Progress</th>
              <th class="px-4 py-3 text-left font-semibold">Tempoh / Baki</th>
              <th class="px-4 py-3 text-left font-semibold">Tarikh Selesai</th>
              <th class="px-4 py-3 text-center font-semibold">Status</th>
              <th class="px-4 py-3 text-left font-semibold">Tindakan</th>
            </tr>
          </thead>
          <tbody>
            @forelse($rows as $row)
              @php
                $count = (int) $row->completion_count;
                $pct = $totalUnits > 0 ? min(100, round($count / $totalUnits * 100)) : 0;
                $completionDate = $row->max_completion_date ? \Carbon\Carbon::parse($row->max_completion_date)->format('d/m/Y H:i') : '-';
                $tarikhDaftar = $row->staff_created_at ? \Carbon\Carbon::parse($row->staff_created_at)->format('d/m/Y H:i') : '-';
                $startDate = $row->staff_created_at ? \Carbon\Carbon::parse($row->staff_created_at) : null;
                $endDate = ($row->status === 'SELESAI' && $row->max_completion_date) ? \Carbon\Carbon::parse($row->max_completion_date) : \Carbon\Carbon::now();
                $tempohLabel = '-';
                $bakiLabel = '-';
                $tempohRed = false;
                $bakiRed = false;

                if ($startDate) {
                  $diffDays = (int) $startDate->diffInDays($endDate);
                  $diffMonths = (int) $startDate->diffInMonths($endDate);
                  $diffWeeks = (int) $startDate->diffInWeeks($endDate);
                  $tempohRed = $diffDays > 90;

                  if ($diffDays < 7) {
                    $tempohLabel = $diffDays.' hari';
                  } elseif ($diffDays < 30) {
                    $tempohLabel = $diffWeeks.' minggu';
                  } else {
                    $tempohLabel = $diffMonths.' bulan';
                  }

                  if ($row->status === 'SELESAI') {
                    $bakiLabel = 'Selesai';
                  } else {
                    $bakiDays = 90 - $diffDays;
                    if ($bakiDays <= 0) {
                      $bakiLabel = 'Lewat '.abs($bakiDays).' hari';
                      $bakiRed = true;
                    } elseif ($bakiDays < 7) {
                      $bakiLabel = $bakiDays.' hari lagi';
                      $bakiRed = true;
                    } elseif ($bakiDays < 30) {
                      $bakiLabel = (int) ($bakiDays / 7).' minggu lagi';
                    } else {
                      $bakiLabel = (int) ($bakiDays / 30).' bulan lagi';
                    }
                  }
                }
              @endphp
              <tr class="border-t border-slate-100 align-top hover:bg-slate-50/70">
                <td class="px-4 py-3 font-medium text-slate-900">{{ $row->nama_penuh }}</td>
                <td class="px-4 py-3 font-mono text-xs text-slate-700">{{ $row->no_ic }}</td>
                <td class="px-4 py-3 text-slate-700">{{ $row->unit_jabatan }}</td>
                <td class="px-4 py-3 text-slate-700">{{ $row->jawatan }}</td>
                <td class="px-4 py-3 text-slate-700">{{ $row->email }}</td>
                <td class="px-4 py-3 text-slate-700">{{ $tarikhDaftar }}</td>
                <td class="px-4 py-3">
                  <div class="flex items-center gap-2">
                    <span class="w-12 shrink-0 text-xs font-bold text-slate-700">{{ $count }}/{{ $unitTarget }}</span>
                    <div class="progress-bar-wrap h-2 min-w-[72px] flex-1 overflow-hidden rounded-full bg-slate-200">
                      <div class="h-2 rounded-full {{ $row->status === 'SELESAI' ? 'bg-emerald-500' : ($row->status === 'BELUM MULA' ? 'bg-slate-300' : 'bg-amber-400') }}" style="width: {{ $pct }}%"></div>
                    </div>
                  </div>
                </td>
                <td class="px-4 py-3 text-xs">
                  <div class="font-semibold {{ $tempohRed ? 'text-red-600' : 'text-slate-700' }}">{{ $tempohLabel }}{{ $tempohRed ? ' ⚠' : '' }}</div>
                  <div class="mt-1 {{ $bakiRed ? 'text-red-600' : ($bakiLabel === 'Selesai' ? 'text-emerald-600' : 'text-slate-500') }}">{{ $bakiLabel }}</div>
                </td>
                <td class="px-4 py-3 text-slate-700">{{ $completionDate }}</td>
                <td class="px-4 py-3 text-center">
                  <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $row->status === 'SELESAI' ? 'bg-emerald-100 text-emerald-700' : ($row->status === 'BELUM MULA' ? 'bg-indigo-100 text-indigo-700' : 'bg-amber-100 text-amber-700') }}">{{ $row->status }}</span>
                </td>
                <td class="px-4 py-3">
                  <div class="flex flex-wrap gap-2">
                    @if($row->status === 'SELESAI')
                      <a href="{{ route('admin.staff-progress.certificate', ['staffId' => $row->id]) }}" target="_blank" class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-700">Cetak Sijil</a>
                    @else
                      <span class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-500">Cetak Sijil</span>
                    @endif
                    <button
                      class="open-edit rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-indigo-700"
                      data-id="{{ $row->id }}"
                      data-name="{{ $row->nama_penuh }}"
                      data-noic="{{ $row->no_ic }}"
                      data-unit="{{ $row->unit_jabatan }}"
                      data-jawatan="{{ $row->jawatan }}"
                      data-email="{{ $row->email }}"
                    >Edit</button>
                    <form method="POST" action="{{ route('admin.pengurusan-staff.delete-user') }}" onsubmit="return confirm('Padam rekod pengguna ini?')">
                      @csrf
                      <input type="hidden" name="user_id" value="{{ $row->id }}">
                      <button class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-red-700">Padam</button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="11" class="px-4 py-10 text-center text-slate-400">Tiada rekod staff dijumpai.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4">
    <div class="w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-5 shadow-xl">
      <div class="mb-4">
        <h2 class="text-lg font-bold text-slate-900">Edit Staff</h2>
        <p class="text-xs text-slate-500">Kemaskini maklumat staff tanpa perlu buka halaman lain.</p>
      </div>

      <form method="POST" action="{{ route('admin.pengurusan-staff.edit-user') }}" class="space-y-3">
        @csrf
        <input type="hidden" id="editUserId" name="user_id">

        <div>
          <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Nama Penuh</label>
          <input id="editName" name="nama_penuh" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" required>
        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
          <div>
            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">No. IC</label>
            <input id="editNoIc" name="no_ic" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" required>
          </div>
          <div>
            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Email</label>
            <input id="editEmail" name="email" type="email" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" required>
          </div>
        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
          <div>
            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Unit/Jabatan</label>
            <input id="editUnit" name="unit_jabatan" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" required>
          </div>
          <div>
            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Jawatan</label>
            <input id="editJawatan" name="jawatan" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" required>
          </div>
        </div>

        <div class="mt-4 flex justify-end gap-2">
          <button type="button" id="closeModal" class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-100">Batal</button>
          <button class="rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-blue-700">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    const modal = document.getElementById('editModal');
    const closeModal = document.getElementById('closeModal');
    const editUserId = document.getElementById('editUserId');
    const editName = document.getElementById('editName');
    const editNoIc = document.getElementById('editNoIc');
    const editUnit = document.getElementById('editUnit');
    const editJawatan = document.getElementById('editJawatan');
    const editEmail = document.getElementById('editEmail');

    document.querySelectorAll('.open-edit').forEach((btn) => {
      btn.addEventListener('click', () => {
        editUserId.value = btn.dataset.id;
        editName.value = btn.dataset.name;
        editNoIc.value = btn.dataset.noic;
        editUnit.value = btn.dataset.unit;
        editJawatan.value = btn.dataset.jawatan;
        editEmail.value = btn.dataset.email;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      });
    });

    function hideModal() {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    closeModal.addEventListener('click', hideModal);
    modal.addEventListener('click', (event) => {
      if (event.target === modal) {
        hideModal();
      }
    });
  </script>
</body>
</html>
