<!doctype html>
<html lang="ms">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Pengurusan Penyelia</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-800">
  @php
    $penyeliaCount = $rows->count();
    $activeTotal = isset($activeCount) ? (int) $activeCount : $penyeliaCount;
    $inactiveTotal = isset($inactiveCount) ? (int) $inactiveCount : 0;
  @endphp

  <div class="mx-auto max-w-7xl space-y-4 p-4 sm:p-6">
    <div class="rounded-2xl bg-gradient-to-r from-slate-900 via-teal-800 to-slate-900 p-5 text-white shadow-lg">
      <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
          <h1 class="text-2xl font-bold tracking-tight">Pengurusan Penyelia</h1>
          <p class="mt-1 text-sm text-slate-200">Kemaskini maklumat penyelia dan urus semula kata laluan dengan cepat.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="rounded-lg bg-white/15 px-4 py-2 text-xs font-semibold text-white transition hover:bg-white/25">Kembali Dashboard</a>
      </div>
    </div>

    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
      <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Jumlah Penyelia</p>
        <p class="mt-2 text-2xl font-bold text-slate-800">{{ $penyeliaCount }}</p>
      </div>
      <div class="rounded-xl border border-cyan-200 bg-cyan-50 p-4 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-cyan-700">Aktif</p>
        <p class="mt-2 text-2xl font-bold text-cyan-800">{{ $activeTotal }}</p>
      </div>
      <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Tidak Aktif</p>
        <p class="mt-2 text-2xl font-bold text-emerald-800">{{ $inactiveTotal }}</p>
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

    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
      <form method="GET" class="grid grid-cols-1 gap-3 sm:grid-cols-[1fr_auto_auto] sm:items-center">
        <div>
          <label for="search" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Carian</label>
          <input id="search" name="search" value="{{ $search }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-200" placeholder="Cari nama, no ic, unit, atau jawatan">
        </div>
        <button class="rounded-lg bg-cyan-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-cyan-700">Cari</button>
        <a href="{{ route('admin.pengurusan-penyelia') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Set Semula</a>
      </form>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
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
              <th class="px-4 py-3 text-left font-semibold">Tindakan</th>
            </tr>
          </thead>
          <tbody>
            @forelse($rows as $row)
              <tr class="border-t border-slate-100 align-top hover:bg-slate-50/70">
                <td class="px-4 py-3 font-medium text-slate-900">{{ $row->nama_penuh }}</td>
                <td class="px-4 py-3 text-slate-700">{{ $row->no_ic }}</td>
                <td class="px-4 py-3 text-slate-700">
                  <span class="inline-flex rounded-full bg-cyan-100 px-2.5 py-1 text-xs font-semibold text-cyan-700">{{ $row->unit_jabatan }}</span>
                </td>
                <td class="px-4 py-3 text-slate-700">{{ $row->jawatan }}</td>
                <td class="px-4 py-3 text-slate-700">{{ $row->email }}</td>
                <td class="px-4 py-3 text-slate-700">{{ $row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i') : '-' }}</td>
                <td class="px-4 py-3">
                  <div class="flex flex-wrap gap-2">
                    <button
                      class="open-edit rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-indigo-700"
                      data-id="{{ $row->id }}"
                      data-name="{{ $row->nama_penuh }}"
                      data-noic="{{ $row->no_ic }}"
                      data-unit="{{ $row->unit_jabatan }}"
                      data-jawatan="{{ $row->jawatan }}"
                      data-email="{{ $row->email }}"
                    >Edit</button>

                    <form method="POST" action="{{ route('admin.pengurusan-penyelia.reset-password') }}" onsubmit="return confirm('Reset kata laluan penyelia ini kepada User000?')">
                      @csrf
                      <input type="hidden" name="user_id" value="{{ $row->id }}">
                      <button class="rounded-lg bg-amber-500 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-amber-600">Reset Password</button>
                    </form>

                    <form method="POST" action="{{ route('admin.pengurusan-penyelia.delete-user') }}" onsubmit="return confirm('Padam rekod pengguna ini?')">
                      @csrf
                      <input type="hidden" name="user_id" value="{{ $row->id }}">
                      <button class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-red-700">Padam</button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="px-4 py-8 text-center text-sm text-slate-500">Tiada rekod penyelia ditemui.</td>
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
        <h2 class="text-lg font-bold text-slate-900">Edit Maklumat Penyelia</h2>
        <p class="text-xs text-slate-500">Pastikan No. IC dan email disemak sebelum simpan perubahan.</p>
      </div>

      <form method="POST" action="{{ route('admin.pengurusan-penyelia.edit-user') }}" class="space-y-3">
        @csrf
        <input type="hidden" id="editUserId" name="user_id">

        <div>
          <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Nama Penuh</label>
          <input id="editName" name="nama_penuh" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-200" required>
        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
          <div>
            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">No. IC</label>
            <input id="editNoIc" name="no_ic" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-200" required>
          </div>
          <div>
            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Email</label>
            <input id="editEmail" name="email" type="email" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-200" required>
          </div>
        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
          <div>
            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Unit/Jabatan</label>
            <input id="editUnit" name="unit_jabatan" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-200" required>
          </div>
          <div>
            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Jawatan</label>
            <input id="editJawatan" name="jawatan" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-200" required>
          </div>
        </div>

        <div class="mt-4 flex justify-end gap-2">
          <button type="button" id="closeModal" class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-100">Batal</button>
          <button class="rounded-lg bg-cyan-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-cyan-700">Simpan Perubahan</button>
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
