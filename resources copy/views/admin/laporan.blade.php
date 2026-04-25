<!doctype html>
<html lang="ms">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Pengurusan Staff</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
  <div class="max-w-7xl mx-auto p-4">
    <div class="mb-4 flex items-center justify-between gap-2 flex-wrap">
      <h1 class="text-xl font-bold">Pengurusan Staff</h1>
      <a href="{{ route('admin.dashboard') }}" class="rounded bg-slate-700 px-3 py-2 text-xs font-semibold text-white">Kembali Dashboard</a>
    </div>

    @if(session('status'))
      <div class="mb-3 rounded border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700">{{ session('status') }}</div>
    @endif

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
            <th class="px-3 py-2 text-left">Jawatan</th>
            <th class="px-3 py-2 text-left">Email</th>
            <th class="px-3 py-2 text-left">Status</th>
            <th class="px-3 py-2 text-left">Tindakan</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rows as $row)
            <tr class="border-t">
              <td class="px-3 py-2">{{ $row->nama_penuh }}</td>
              <td class="px-3 py-2">{{ $row->no_ic }}</td>
              <td class="px-3 py-2">{{ $row->unit_jabatan }}</td>
              <td class="px-3 py-2">{{ $row->jawatan }}</td>
              <td class="px-3 py-2">{{ $row->email }}</td>
              <td class="px-3 py-2">{{ $row->status }}</td>
              <td class="px-3 py-2">
                <div class="flex flex-wrap gap-1">
                  <button
                    class="open-edit rounded bg-indigo-600 px-2 py-1 text-xs font-semibold text-white"
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
                    <button class="rounded bg-red-600 px-2 py-1 text-xs font-semibold text-white">Padam</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="px-3 py-4 text-center text-gray-500">Tiada rekod ditemui.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
    <div class="w-full max-w-md rounded bg-white p-4 shadow-lg">
      <h2 class="mb-3 text-lg font-semibold">Edit Staff</h2>
      <form method="POST" action="{{ route('admin.pengurusan-staff.edit-user') }}" class="space-y-2">
        @csrf
        <input type="hidden" id="editUserId" name="user_id">
        <label class="block text-xs font-semibold">Nama Penuh</label>
        <input id="editName" name="nama_penuh" class="w-full rounded border px-3 py-2 text-sm" required>
        <label class="block text-xs font-semibold">No. IC</label>
        <input id="editNoIc" name="no_ic" class="w-full rounded border px-3 py-2 text-sm" required>
        <label class="block text-xs font-semibold">Unit/Jabatan</label>
        <input id="editUnit" name="unit_jabatan" class="w-full rounded border px-3 py-2 text-sm" required>
        <label class="block text-xs font-semibold">Jawatan</label>
        <input id="editJawatan" name="jawatan" class="w-full rounded border px-3 py-2 text-sm" required>
        <label class="block text-xs font-semibold">Email</label>
        <input id="editEmail" name="email" type="email" class="w-full rounded border px-3 py-2 text-sm" required>
        <div class="mt-3 flex justify-end gap-2">
          <button type="button" id="closeModal" class="rounded border px-3 py-2 text-xs font-semibold">Batal</button>
          <button class="rounded bg-blue-600 px-3 py-2 text-xs font-semibold text-white">Simpan</button>
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

    document.querySelectorAll('.open-edit').forEach(btn => {
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
      if (event.target === modal) hideModal();
    });
  </script>
</body>
</html>
