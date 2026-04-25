<!doctype html>
<html lang="ms">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profil Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <style>body{font-family:'Poppins',sans-serif}</style>
</head>
<body class="min-h-screen bg-gray-100">
  <div class="max-w-4xl mx-auto p-4 space-y-4">
    <div class="flex items-center justify-between gap-3 flex-wrap">
      <h1 class="text-xl font-bold">Profil Admin</h1>
      <a href="{{ route('admin.dashboard') }}" class="rounded bg-blue-600 px-3 py-2 text-xs font-semibold text-white">Kembali Dashboard</a>
    </div>

    @if(session('status'))
      <div class="rounded border border-emerald-300 bg-emerald-50 px-3 py-2 text-sm text-emerald-800">{{ session('status') }}</div>
    @endif

    @if($errors->any())
      <div class="rounded border border-red-300 bg-red-50 px-3 py-2 text-sm text-red-800">{{ $errors->first() }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="rounded bg-white p-4 shadow md:col-span-1">
        <h2 class="mb-3 text-sm font-semibold">Gambar Profil</h2>
        <img src="{{ $profilePic }}" alt="Profile" class="mx-auto h-28 w-28 rounded-full border object-cover">
        <form method="POST" action="{{ route('admin.profile.photo') }}" enctype="multipart/form-data" class="mt-3 space-y-2">
          @csrf
          <input type="file" name="profile_picture" accept="image/*" class="block w-full text-xs">
          <button class="w-full rounded bg-slate-700 px-3 py-2 text-xs font-semibold text-white">Muat Naik</button>
        </form>
        <p class="mt-2 text-[11px] text-gray-500">Format imej sahaja, maksimum 2MB.</p>
      </div>

      <div class="rounded bg-white p-4 shadow md:col-span-2 space-y-4">
        <div>
          <h2 class="mb-3 text-sm font-semibold">Maklumat Peribadi</h2>
          <form method="POST" action="{{ route('admin.profile.info') }}" class="space-y-2">
            @csrf
            <div>
              <label class="text-xs font-semibold">Nama Penuh</label>
              <input name="nama_penuh" value="{{ old('nama_penuh', $admin->nama_penuh) }}" class="w-full rounded border px-3 py-2 text-sm" required>
            </div>
            <div>
              <label class="text-xs font-semibold">No. IC</label>
              <input value="{{ $admin->no_ic }}" class="w-full rounded border bg-gray-100 px-3 py-2 text-sm" readonly>
            </div>
            <div>
              <label class="text-xs font-semibold">Email</label>
              <input type="email" name="email" value="{{ old('email', $admin->email) }}" class="w-full rounded border px-3 py-2 text-sm" required>
            </div>
            <button class="rounded bg-blue-600 px-3 py-2 text-xs font-semibold text-white">Simpan Maklumat</button>
          </form>
        </div>

        <div>
          <h2 class="mb-3 text-sm font-semibold">Tukar Kata Laluan</h2>
          <form method="POST" action="{{ route('admin.profile.password') }}" class="space-y-2">
            @csrf
            <div>
              <label class="text-xs font-semibold">Kata Laluan Lama</label>
              <div class="relative">
                <input type="password" name="old_password" class="w-full rounded border px-3 py-2 pr-10 text-sm" required>
                <button type="button" class="password-toggle absolute inset-y-0 right-0 px-3 text-gray-500" aria-label="Tunjuk kata laluan" data-show-text="Tunjuk kata laluan" data-hide-text="Sembunyi kata laluan">
                  <svg class="h-5 w-5 eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    <circle cx="12" cy="12" r="3" />
                  </svg>
                  <svg class="h-5 w-5 eye-off-icon hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.58 10.58A3 3 0 0012 15a3 3 0 002.42-4.42" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.88 5.09A9.77 9.77 0 0112 5c4.48 0 8.27 2.94 9.54 7a10.63 10.63 0 01-4.06 5.16" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.61 6.61A10.58 10.58 0 002.46 12C3.73 16.06 7.52 19 12 19a9.8 9.8 0 005.39-1.61" />
                  </svg>
                </button>
              </div>
            </div>
            <div>
              <label class="text-xs font-semibold">Kata Laluan Baru</label>
              <div class="relative">
                <input type="password" name="new_password" class="w-full rounded border px-3 py-2 pr-10 text-sm" required>
                <button type="button" class="password-toggle absolute inset-y-0 right-0 px-3 text-gray-500" aria-label="Tunjuk kata laluan" data-show-text="Tunjuk kata laluan" data-hide-text="Sembunyi kata laluan">
                  <svg class="h-5 w-5 eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    <circle cx="12" cy="12" r="3" />
                  </svg>
                  <svg class="h-5 w-5 eye-off-icon hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.58 10.58A3 3 0 0012 15a3 3 0 002.42-4.42" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.88 5.09A9.77 9.77 0 0112 5c4.48 0 8.27 2.94 9.54 7a10.63 10.63 0 01-4.06 5.16" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.61 6.61A10.58 10.58 0 002.46 12C3.73 16.06 7.52 19 12 19a9.8 9.8 0 005.39-1.61" />
                  </svg>
                </button>
              </div>
            </div>
            <div>
              <label class="text-xs font-semibold">Sahkan Kata Laluan Baru</label>
              <div class="relative">
                <input type="password" name="confirm_password" class="w-full rounded border px-3 py-2 pr-10 text-sm" required>
                <button type="button" class="password-toggle absolute inset-y-0 right-0 px-3 text-gray-500" aria-label="Tunjuk kata laluan" data-show-text="Tunjuk kata laluan" data-hide-text="Sembunyi kata laluan">
                  <svg class="h-5 w-5 eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    <circle cx="12" cy="12" r="3" />
                  </svg>
                  <svg class="h-5 w-5 eye-off-icon hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.58 10.58A3 3 0 0012 15a3 3 0 002.42-4.42" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.88 5.09A9.77 9.77 0 0112 5c4.48 0 8.27 2.94 9.54 7a10.63 10.63 0 01-4.06 5.16" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.61 6.61A10.58 10.58 0 002.46 12C3.73 16.06 7.52 19 12 19a9.8 9.8 0 005.39-1.61" />
                  </svg>
                </button>
              </div>
            </div>
            <button class="rounded bg-emerald-600 px-3 py-2 text-xs font-semibold text-white">Kemaskini Kata Laluan</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.querySelectorAll('.password-toggle').forEach(button => {
      button.addEventListener('click', () => {
        const wrapper = button.closest('.relative');
        const input = wrapper?.querySelector('input[type="password"], input[type="text"]');
        const eye = button.querySelector('.eye-icon');
        const eyeOff = button.querySelector('.eye-off-icon');

        if (!input) return;

        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';

        eye?.classList.toggle('hidden', !isHidden);
        eyeOff?.classList.toggle('hidden', isHidden);
        button.setAttribute('aria-label', isHidden ? button.dataset.hideText : button.dataset.showText);
      });
    });
  </script>
</body>
</html>
