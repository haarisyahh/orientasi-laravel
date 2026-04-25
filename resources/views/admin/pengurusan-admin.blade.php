<!doctype html>
<html lang="ms">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Pengurusan Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-800">
  @php
    $adminCount = $rows->count();
    $superAdminCount = $rows->where('role', 'super_admin')->count();
    $normalAdminCount = $rows->where('role', 'admin')->count();
  @endphp

  <div class="mx-auto max-w-7xl space-y-4 p-4 sm:p-6">
    <div class="rounded-2xl bg-gradient-to-r from-slate-900 via-blue-900 to-slate-900 p-5 text-white shadow-lg">
      <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
          <h1 class="text-2xl font-bold tracking-tight">Pengurusan Admin</h1>
          <p class="mt-1 text-sm text-slate-200">Urus akaun admin, role, dan keselamatan kata laluan dengan lebih teratur.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="rounded-lg bg-white/15 px-4 py-2 text-xs font-semibold text-white transition hover:bg-white/25">Kembali Dashboard</a>
      </div>
    </div>

    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
      <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Jumlah Akaun</p>
        <p class="mt-2 text-2xl font-bold text-slate-800">{{ $adminCount }}</p>
      </div>
      <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Super Admin</p>
        <p class="mt-2 text-2xl font-bold text-emerald-800">{{ $superAdminCount }}</p>
      </div>
      <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">Admin</p>
        <p class="mt-2 text-2xl font-bold text-blue-800">{{ $normalAdminCount }}</p>
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
          <input id="search" name="search" value="{{ $search }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" placeholder="Cari nama, no ic, atau email">
        </div>
        <button class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">Cari</button>
        <a href="{{ route('admin.pengurusan-admin') }}" 
   class="inline-flex items-center justify-center rounded-lg border border-slate-300 p-2 text-slate-700 transition hover:bg-slate-100 hover:text-blue-600" 
   title="Set Semula">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
    </svg>
</a>
      </form>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-slate-100 text-slate-700">
            <tr>
              <th class="px-4 py-3 text-left font-semibold">Nama</th>
              <th class="px-4 py-3 text-left font-semibold">No. IC</th>
              <th class="px-4 py-3 text-left font-semibold">Email</th>
              <th class="px-4 py-3 text-left font-semibold">Role</th>
              <th class="px-4 py-3 text-left font-semibold">Tarikh Daftar</th>
              @if($isSuperAdmin)
                <th class="px-4 py-3 text-left font-semibold">Tindakan</th>
              @endif
            </tr>
          </thead>
          <tbody>
            @forelse($rows as $row)
              @php
                $isProtected = $row->id === $currentAdminId || $row->no_ic === '020806020528';
              @endphp
              <tr class="border-t border-slate-100 align-top hover:bg-slate-50/70">
                <td class="px-4 py-3 font-medium text-slate-900">{{ $row->nama_penuh }}</td>
                <td class="px-4 py-3 text-slate-700">{{ $row->no_ic }}</td>
                <td class="px-4 py-3 text-slate-700">{{ $row->email }}</td>
                <td class="px-4 py-3">
                  @if($row->role === 'super_admin')
                    <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">SUPER ADMIN</span>
                  @else
                    <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700">ADMIN</span>
                  @endif
                </td>
                <td class="px-4 py-3 text-slate-700">{{ $row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i') : '-' }}</td>
                @if($isSuperAdmin)
                  <td class="px-4 py-3">
                    @if(!$isProtected)
                      <div class="flex flex-wrap gap-2">
                        <button
                          class="open-edit rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-indigo-700"
                          data-id="{{ $row->id }}"
                          data-name="{{ $row->nama_penuh }}"
                          data-noic="{{ $row->no_ic }}"
                          data-email="{{ $row->email }}"
                        >Edit</button>

                        <form method="POST" action="{{ route('admin.pengurusan-admin.reset-password') }}" onsubmit="return confirm('Reset kata laluan admin ini kepada Adm1n@IT?')">
                          @csrf
                          <input type="hidden" name="user_id" value="{{ $row->id }}">
                          <button class="rounded-lg bg-amber-500 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-amber-600">Reset Password</button>
                        </form>

                        <form method="POST" action="{{ route('admin.pengurusan-admin.delete-user') }}" onsubmit="return confirm('Padam admin ini?')">
                          @csrf
                          <input type="hidden" name="user_id" value="{{ $row->id }}">
                          <button class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-red-700">Padam</button>
                        </form>

                        @if($row->role !== 'super_admin')
                          <form method="POST" action="{{ route('admin.pengurusan-admin.promote-super') }}" onsubmit="return confirm('Jadikan admin ini sebagai super admin?')">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $row->id }}">
                            <button class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-emerald-700">Add as Super Admin</button>
                          </form>
                        @else
                          <form method="POST" action="{{ route('admin.pengurusan-admin.remove-super') }}" onsubmit="return confirm('Turunkan role super admin kepada admin?')">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $row->id }}">
                            <button class="rounded-lg bg-orange-500 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-orange-600">Buang Super Admin</button>
                          </form>
                        @endif
                      </div>
                    @else
                      <span class="inline-flex rounded bg-slate-100 px-2 py-1 text-xs font-medium text-slate-500">Tiada tindakan</span>
                    @endif
                  </td>
                @endif
              </tr>
            @empty
              <tr>
                <td colspan="{{ $isSuperAdmin ? 6 : 5 }}" class="px-4 py-8 text-center text-sm text-slate-500">Tiada rekod admin ditemui.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @if($isSuperAdmin)
    <div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4">
      <div class="w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-5 shadow-xl">
        <div class="mb-4">
          <h2 class="text-lg font-bold text-slate-900">Edit Maklumat Admin</h2>
          <p class="text-xs text-slate-500">Kata laluan boleh dikosongkan jika tidak mahu diubah.</p>
        </div>

        <form method="POST" action="{{ route('admin.pengurusan-admin.edit-user') }}" class="space-y-3">
          @csrf
          <input type="hidden" id="editUserId" name="user_id">

          <div>
            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Nama Penuh</label>
            <input id="editName" name="nama_penuh" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" required>
          </div>

          <div>
            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">No. IC</label>
            <input id="editNoIc" name="no_ic" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" required>
          </div>

          <div>
            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Email</label>
            <input id="editEmail" name="email" type="email" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" required>
          </div>

          <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            <div>
              <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Kata Laluan Baru</label>
              <input id="editPassword" name="password" type="password" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" placeholder="Opsyenal">
            </div>
            <div>
              <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Sahkan Kata Laluan</label>
              <input id="editPasswordConfirmation" name="password_confirmation" type="password" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
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
      const editEmail = document.getElementById('editEmail');
      const editPassword = document.getElementById('editPassword');
      const editPasswordConfirmation = document.getElementById('editPasswordConfirmation');

      document.querySelectorAll('.open-edit').forEach((btn) => {
        btn.addEventListener('click', () => {
          editUserId.value = btn.dataset.id;
          editName.value = btn.dataset.name;
          editNoIc.value = btn.dataset.noic;
          editEmail.value = btn.dataset.email;
          editPassword.value = '';
          editPasswordConfirmation.value = '';
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
  @endif
</body>
</html>