<!doctype html>
<html lang="ms">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Reset Unit</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
  <div class="max-w-7xl mx-auto p-4">
    <div class="mb-4 flex items-center justify-between gap-2 flex-wrap">
      <h1 class="text-xl font-bold">Tetapkan Semula Unit</h1>
      <a href="{{ route('admin.dashboard') }}" class="rounded bg-slate-700 px-3 py-2 text-xs font-semibold text-white">Kembali Dashboard</a>
    </div>

    <div class="mb-4">
      <input
        id="userSearchInput"
        class="w-full max-w-md rounded border px-3 py-2 text-sm"
        placeholder="Cari nama / ID pengguna"
      >
    </div>

    <div id="userCards" class="grid grid-cols-1 lg:grid-cols-2 gap-4">
      @foreach($users as $user)
        @php $completedMap = $statusMap[$user->id] ?? []; @endphp
        <div class="rounded bg-white p-4 shadow" data-filter="{{ strtolower($user->nama_penuh.' '.$user->no_ic) }}">
          <div class="mb-2">
            <h2 class="font-semibold">{{ $user->nama_penuh }}</h2>
            <p class="text-xs text-gray-500">{{ $user->no_ic }} • {{ $user->unit_jabatan }}</p>
          </div>

          <div class="space-y-2 max-h-72 overflow-auto pr-1">
            @foreach($units as $slug => $label)
              @php $done = isset($completedMap[$slug]); @endphp
              <div class="flex items-center justify-between rounded border px-2 py-2 {{ $done ? 'bg-emerald-50 border-emerald-200' : 'bg-gray-50 border-gray-200' }}">
                <div class="text-xs font-medium">{{ $label }}</div>
                @if($done)
                  <button class="reset-unit rounded bg-red-600 px-2 py-1 text-xs font-semibold text-white" data-user-id="{{ $user->id }}" data-unit-slug="{{ $slug }}">Reset</button>
                @else
                  <span class="text-[10px] text-gray-400">-</span>
                @endif
              </div>
            @endforeach
          </div>

          <button class="reset-all mt-3 w-full rounded bg-amber-600 px-3 py-2 text-xs font-semibold text-white" data-user-id="{{ $user->id }}">Reset Semua Unit</button>
        </div>
      @endforeach
    </div>
  </div>

  <script>
    async function doReset(payload) {
      const res = await fetch(@json(route('admin.reset-units.action')), {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': @json(csrf_token())
        },
        body: JSON.stringify(payload)
      });
      const data = await res.json();
      if (!data.success) throw new Error(data.message || 'Gagal');
    }

    document.querySelectorAll('.reset-unit').forEach(btn => {
      btn.addEventListener('click', async function(){
        if (!confirm('Reset unit ini?')) return;
        try {
          await doReset({ action: 'reset_unit', user_id: Number(this.dataset.userId), unit_slug: this.dataset.unitSlug });
          location.reload();
        } catch (e) {
          alert(e.message);
        }
      });
    });

    document.querySelectorAll('.reset-all').forEach(btn => {
      btn.addEventListener('click', async function(){
        if (!confirm('Reset SEMUA unit untuk pengguna ini?')) return;
        try {
          await doReset({ action: 'reset_all', user_id: Number(this.dataset.userId) });
          location.reload();
        } catch (e) {
          alert(e.message);
        }
      });
    });

    const userSearchInput = document.getElementById('userSearchInput');

    function filterUserCards() {
      const query = (userSearchInput?.value || '').toLowerCase().trim();
      document.querySelectorAll('#userCards [data-filter]').forEach(card => {
        const text = card.dataset.filter || '';
        card.style.display = !query || text.includes(query) ? '' : 'none';
      });
    }

    userSearchInput?.addEventListener('input', filterUserCards);
  </script>
</body>
</html>
