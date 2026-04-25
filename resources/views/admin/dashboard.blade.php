<!doctype html>
<html lang="ms">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --brand-navy: #0f2744;
      --brand-teal: #0f766e;
      --brand-sky: #eaf3f5;
      --brand-line: rgba(15, 39, 68, 0.1);
      --brand-shadow: 0 24px 60px rgba(15, 39, 68, 0.1);
    }

    body {
      font-family: 'Poppins', sans-serif;
      background:
        radial-gradient(circle at top left, rgba(15, 118, 110, 0.08), transparent 32%),
        linear-gradient(180deg, #f3f7fa 0%, #eef3f7 100%);
      color: #10243c;
    }

    .dashboard-shell {
      position: relative;
      overflow: hidden;
    }

    .dashboard-shell::before,
    .dashboard-shell::after {
      content: '';
      position: fixed;
      width: 320px;
      height: 320px;
      border-radius: 999px;
      pointer-events: none;
      z-index: 0;
      filter: blur(6px);
    }

    .dashboard-shell::before {
      top: -120px;
      right: -80px;
      background: radial-gradient(circle, rgba(15, 118, 110, 0.16) 0%, rgba(15, 118, 110, 0) 70%);
    }

    .dashboard-shell::after {
      left: -120px;
      bottom: 12%;
      background: radial-gradient(circle, rgba(15, 39, 68, 0.12) 0%, rgba(15, 39, 68, 0) 70%);
    }

    .surface-card {
      position: relative;
      z-index: 1;
      border: 1px solid var(--brand-line);
      background: rgba(255, 255, 255, 0.92);
      backdrop-filter: blur(10px);
      box-shadow: var(--brand-shadow);
    }

    .stat-card,
    .quick-link,
    .form-panel {
      transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .stat-card:hover,
    .quick-link:hover,
    .form-panel:hover {
      transform: translateY(-2px);
      box-shadow: 0 24px 44px rgba(15, 39, 68, 0.12);
      border-color: rgba(15, 118, 110, 0.2);
    }

    .hero-panel {
      position: relative;
      overflow: hidden;
      background:
        linear-gradient(135deg, rgba(15, 39, 68, 0.98) 0%, rgba(20, 78, 115, 0.96) 45%, rgba(15, 118, 110, 0.92) 100%);
      color: #fff;
    }

    .hero-panel::after {
      content: '';
      position: absolute;
      inset: 20px;
      border: 1px solid rgba(255, 255, 255, 0.12);
      border-radius: 24px;
      pointer-events: none;
    }

    .stat-value {
      letter-spacing: -0.04em;
    }

    .quick-link {
      display: flex;
      align-items: flex-start;
      gap: 14px;
      min-height: 100%;
      text-decoration: none;
    }

    .quick-icon {
      width: 48px;
      height: 48px;
      border-radius: 16px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      font-size: 1rem;
      font-weight: 700;
    }

    .quick-arrow {
      width: 34px;
      height: 34px;
      border-radius: 999px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: rgba(255, 255, 255, 0.75);
      color: #0f2744;
      font-weight: 700;
      margin-left: auto;
      flex-shrink: 0;
    }

    .field-input {
      width: 100%;
      border-radius: 14px;
      border: 1px solid #cbd5e1;
      padding: 0.75rem 0.95rem;
      font-size: 0.92rem;
      color: #0f172a;
      background: #fff;
      outline: none;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .field-input:focus {
      border-color: #0f766e;
      box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.12);
    }

    @media (max-width: 768px) {
      .hero-panel::after {
        inset: 14px;
      }
    }
  </style>
</head>
<body class="min-h-screen">
  @php
    $statCards = [
      [
        'label' => 'Staff Baru',
        'value' => $staffCount,
        'accent' => 'text-emerald-700',
        'ring' => 'border-emerald-200 bg-emerald-50/90',
        'iconBg' => 'bg-emerald-600',
        'icon' => 'SB',
      ],
      [
        'label' => 'Penyelia',
        'value' => $penyeliaCount,
        'accent' => 'text-amber-700',
        'ring' => 'border-amber-200 bg-amber-50/90',
        'iconBg' => 'bg-amber-500',
        'icon' => 'PY',
      ],
      [
        'label' => 'Selesai Orientasi',
        'value' => $completedUsersCount,
        'accent' => 'text-indigo-700',
        'ring' => 'border-indigo-200 bg-indigo-50/90',
        'iconBg' => 'bg-indigo-600',
        'icon' => 'OK',
      ],
    ];

    $linkStyles = [
      'Pengurusan Staff' => [
        'card' => 'border-emerald-200 bg-emerald-50/95 text-emerald-900 hover:bg-emerald-100/95',
        'icon' => 'bg-emerald-600 text-white',
        'desc' => 'Semak, cari dan urus maklumat staf baharu.',
        'abbr' => 'ST',
      ],
      'Pengurusan Penyelia' => [
        'card' => 'border-violet-200 bg-violet-50/95 text-violet-900 hover:bg-violet-100/95',
        'icon' => 'bg-violet-600 text-white',
        'desc' => 'Pantau akaun penyelia dan status pengesahan unit.',
        'abbr' => 'PN',
      ],
      'Pengurusan Admin' => [
        'card' => 'border-cyan-200 bg-cyan-50/95 text-cyan-900 hover:bg-cyan-100/95',
        'icon' => 'bg-cyan-600 text-white',
        'desc' => 'Kawal peranan admin, akses dan keselamatan akaun.',
        'abbr' => 'AD',
      ],
      'Profil Admin' => [
        'card' => 'border-indigo-200 bg-indigo-50/95 text-indigo-900 hover:bg-indigo-100/95',
        'icon' => 'bg-indigo-600 text-white',
        'desc' => 'Kemaskini maklumat profil serta tetapan akaun admin.',
        'abbr' => 'PR',
      ],
      'Kemaskini Slaid / Kandungan Unit' => [
        'card' => 'border-rose-200 bg-rose-50/95 text-rose-900 hover:bg-rose-100/95',
        'icon' => 'bg-rose-600 text-white',
        'desc' => 'Urus kandungan visual dan slaid bagi setiap unit orientasi.',
        'abbr' => 'SL',
      ],
      'Reset Unit User - untuk testing sahaja' => [
        'card' => 'border-amber-200 bg-amber-50/95 text-amber-900 hover:bg-amber-100/95',
        'icon' => 'bg-amber-600 text-white',
        'desc' => 'Gunakan untuk set semula kemajuan unit dalam persekitaran ujian.',
        'abbr' => 'RS',
      ],
      'Tandakan Unit Selesai - untuk testing sahaja' => [
        'card' => 'border-green-200 bg-green-50/95 text-green-900 hover:bg-green-100/95',
        'icon' => 'bg-green-600 text-white',
        'desc' => 'Tandakan penyempurnaan unit secara manual untuk tujuan semakan.',
        'abbr' => 'TS',
      ],
    ];
  @endphp

  <div class="dashboard-shell">
    <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8 lg:py-7 space-y-5">
      <section class="hero-panel surface-card rounded-[28px] px-5 py-6 sm:px-7 sm:py-7 lg:px-8 lg:py-8">
        <div class="relative z-10 flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
          <div class="max-w-3xl">
            
            <h1 class="mt-4 text-2xl font-bold tracking-tight sm:text-3xl lg:text-[2.2rem]">Dashboard Admin Hospital Baling</h1>
            <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-200 sm:text-[15px]">
              Selamat datang, {{ $adminName }}. Memusatkan statistik orientasi, pintasan ke fungsi pentadbiran, dan pengurusan akaun admin.
            </p>
            <div class="mt-5 flex flex-wrap gap-3 text-xs text-white/85">
              <div class="rounded-full border border-white/12 bg-white/8 px-3 py-2">Statistik semasa</div>
              <div class="rounded-full border border-white/12 bg-white/8 px-3 py-2">Akses fungsi admin</div>
              @if($isSuperAdmin)
                <div class="rounded-full border border-white/12 bg-amber-400/15 px-3 py-2 text-amber-100">Mod super admin aktif</div>
              @endif
            </div>
          </div>

          <div class="flex flex-col items-start gap-3 lg:items-end">
            <div class="rounded-2xl border border-white/12 bg-white/10 px-4 py-3 text-left text-xs leading-6 text-white/85">
              <div class="font-semibold uppercase tracking-[0.14em] text-white/70">Status Akses</div>
              <div class="mt-1 text-sm font-semibold text-white">{{ $isSuperAdmin ? 'Super Admin' : 'Admin' }}</div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
              @csrf
              <button class="rounded-xl bg-red-600 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-red-700">Log Keluar</button>
            </form>
          </div>
        </div>
      </section>

      <div class="grid grid-cols-1 gap-4 lg:grid-cols-[minmax(0,1.2fr)_minmax(320px,0.8fr)]">
        <div class="space-y-4">
          @if(session('status'))
            <div class="surface-card rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</div>
          @endif

          @if($errors->any())
            <div class="surface-card rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
              <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <section class="surface-card rounded-[24px] p-4 sm:p-5">
            <div class="flex flex-wrap items-center justify-between gap-3">
              <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-teal-700">Statistik Utama</p>
                <h2 class="mt-2 text-xl font-bold text-slate-900">Ringkasan orientasi semasa</h2>
              </div>
              
            </div>

            <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-3">
              @foreach($statCards as $stat)
                <div class="stat-card rounded-2xl border p-4 {{ $stat['ring'] }}">
                  <div class="flex items-start justify-between gap-3">
                    <div>
                      <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">{{ $stat['label'] }}</p>
                      <p class="stat-value mt-3 text-3xl font-bold {{ $stat['accent'] }}">{{ $stat['value'] }}</p>
                    </div>
                    <div class="{{ $stat['iconBg'] }} inline-flex h-11 w-11 items-center justify-center rounded-2xl text-xs font-bold tracking-[0.12em] text-white shadow-lg">
                      {{ $stat['icon'] }}
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </section>

          <section class="surface-card rounded-[24px] p-4 sm:p-5">
            <div class="flex flex-wrap items-center justify-between gap-3">
              <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-teal-700">Fungsi Admin</p>
                <h2 class="mt-2 text-xl font-bold text-slate-900">Akses pantas ke modul pentadbiran</h2>
              </div>
              
            </div>

            <div class="mt-4 grid grid-cols-1 gap-3 xl:grid-cols-2">
              @foreach($links as $label => $url)
                @php
                  $style = $linkStyles[$label] ?? [
                    'card' => 'border-slate-200 bg-white text-slate-800 hover:bg-slate-50',
                    'icon' => 'bg-slate-700 text-white',
                    'desc' => 'Akses ke modul pentadbiran yang telah dikonfigurasikan dalam sistem.',
                    'abbr' => 'FN',
                  ];
                @endphp
                <a href="{{ $url }}" class="quick-link rounded-2xl border p-4 {{ $style['card'] }}">
                  <div class="quick-icon {{ $style['icon'] }}">{{ $style['abbr'] }}</div>
                  <div class="min-w-0">
                    <h3 class="text-sm font-bold leading-6">{{ $label }}</h3>
                    <p class="mt-1 text-sm leading-6 opacity-80">{{ $style['desc'] }}</p>
                  </div>
                  <span class="quick-arrow" aria-hidden="true">&rarr;</span>
                </a>
              @endforeach
            </div>
          </section>
        </div>

        <div class="space-y-4">
          <section class="surface-card rounded-[24px] p-4 sm:p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-teal-700">Maklumat Akses</p>
            <h2 class="mt-2 text-xl font-bold text-slate-900">Ringkasan peranan admin</h2>
            <div class="mt-4 space-y-3 text-sm leading-7 text-slate-600">
              <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                Mengekalkan statistik, fungsi admin dan pengurusan akaun.
              </div>
              <div class="rounded-2xl border border-teal-100 bg-teal-50 p-4 text-teal-900">
                Gunakan pintasan fungsi untuk akses cepat ke modul pengurusan staff, penyelia, admin, profil dan kandungan unit.
              </div>
              @if($isSuperAdmin)
                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-amber-900">
                  Akaun anda mempunyai akses tambahan untuk fungsi testing dan pendaftaran admin baharu.
                </div>
              @endif
            </div>
          </section>

          @if($isSuperAdmin)
            <section class="form-panel surface-card rounded-[24px] p-4 sm:p-5">
              
              <h2 class="mt-2 text-xl font-bold text-slate-900">Tambah admin baharu</h2>
              <p class="mt-2 text-sm leading-7 text-slate-600">
                Isi ID Admin dan kata laluan. Nama penuh serta email akan dijana automatik berdasarkan ID Admin.
              </p>

              <form method="POST" action="{{ route('admin.add-admin') }}" class="mt-5 space-y-4">
                @csrf
                <div>
                  <label class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">ID Admin (No. IC)</label>
                  <input name="no_ic" value="{{ old('no_ic') }}" class="field-input" placeholder="Contoh: 900101011234" required>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                  <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Kata Laluan</label>
                    <input name="password" type="password" class="field-input" placeholder="Minimum 6 aksara" required>
                  </div>
                  <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Sahkan Kata Laluan</label>
                    <input name="password_confirmation" type="password" class="field-input" required>
                  </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-xs leading-6 text-slate-500">
                  Untuk edit maklumat lanjut, gunakan modul Profil Admin selepas akaun berjaya didaftarkan.
                </div>

                <button class="w-full rounded-2xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 sm:w-auto">Tambah Admin</button>
              </form>
            </section>
          @endif
        </div>
      </div>
    </div>
  </div>
</body>
</html>
