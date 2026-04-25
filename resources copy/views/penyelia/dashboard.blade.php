<!doctype html>
<html lang="ms">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Penyelia - Program Orientasi Hospital Baling</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700;800&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
  <style>
    body { font-family: 'Poppins', sans-serif; }
    .bg-image {
      position: fixed;
      inset: 0;
      background-image: url('{{ asset('assets/background.jpg') }}');
      background-size: cover;
      background-position: center;
      z-index: -2;
    }
    .bg-overlay {
      position: fixed;
      inset: 0;
      background: rgba(255, 255, 255, 0.95);
      z-index: -1;
    }
  </style>
</head>
<body class="min-h-screen bg-gray-100 flex flex-col">
  <div class="bg-image" aria-hidden="true"></div>
  <div class="bg-overlay" aria-hidden="true"></div>

  <header class="w-full bg-gradient-to-r from-blue-900 to-blue-800 text-white px-5 py-4 shadow flex flex-wrap items-center justify-between gap-3">
    <div class="flex-1 text-center">
      <h1 class="text-xl font-extrabold tracking-wide uppercase">Dashboard Penyelia {{ strtoupper($unitJabatan) }}</h1>
      <p class="text-lg opacity-90 mt-1">{{ $penyeliaName }}</p>
    
    </div>
    <form method="POST" action="{{ route('penyelia.logout') }}">
      @csrf
      <button class="rounded bg-red-600 px-3 py-2 text-xs font-semibold uppercase tracking-wide hover:bg-red-700">Log Keluar</button>
    </form>
  </header>

  <main class="w-full flex-1 px-4 py-4">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
      <div class="rounded bg-emerald-600 text-white p-3 shadow text-center">
        <div class="text-[10px] font-bold uppercase tracking-wide">Staff Baru</div>
        <div class="text-2xl font-extrabold mt-1">{{ $totalStaff }}</div>
      </div>
      <div class="rounded bg-blue-600 text-white p-3 shadow text-center">
        <div class="text-[10px] font-bold uppercase tracking-wide">Selesai Penilaian</div>
        <div class="text-2xl font-extrabold mt-1">{{ $completedCount }}</div>
      </div>
      <div class="rounded bg-red-600 text-white p-3 shadow text-center">
        <div class="text-[10px] font-bold uppercase tracking-wide">Belum Selesai</div>
        <div class="text-2xl font-extrabold mt-1">{{ $notCompletedCount }}</div>
      </div>
      <div class="rounded bg-blue-700 text-white p-3 shadow text-center">
        <div class="text-[10px] font-bold uppercase tracking-wide">Kadar Selesai</div>
        <div class="text-2xl font-extrabold mt-1">{{ $completionPercentage }}%</div>
      </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-3">
      <section class="rounded bg-white p-4 shadow">
        <h2 class="text-xs font-extrabold uppercase tracking-wide mb-3">Status Penilaian Orientasi</h2>
        <div class="relative h-56">
          <canvas id="statusChart"></canvas>
        </div>
      </section>

      <section class="xl:col-span-2 rounded bg-white shadow overflow-hidden">
        <div class="bg-gradient-to-r from-blue-900 to-blue-800 text-white px-4 py-3 flex flex-wrap items-center justify-between gap-2">
          <h2 class="text-xs font-extrabold uppercase tracking-wide">Senarai Staff Baru</h2>
          <div class="flex items-center gap-2">
            <input id="staffSearchInput" class="rounded border border-gray-300 px-3 py-1.5 text-xs text-gray-900" placeholder="Cari Nama / ID Pengguna">
            <button id="staffSearchBtn" type="button" class="rounded bg-blue-600 hover:bg-blue-700 px-3 py-1.5 text-xs font-semibold">&#128269;</button>
          </div>
        </div>

        <div class="overflow-x-auto max-h-[420px]">
          <table class="min-w-full text-xs">
            <thead class="bg-gray-100 sticky top-0">
              <tr>
                <th class="border-b px-3 py-2 text-left uppercase tracking-wide">No</th>
                <th class="border-b px-3 py-2 text-left uppercase tracking-wide">Nama Staff Baru</th>
                <th class="border-b px-3 py-2 text-left uppercase tracking-wide">ID Pengguna</th>
                <th class="border-b px-3 py-2 text-left uppercase tracking-wide">Status Penilaian</th>
                <th class="border-b px-3 py-2 text-left uppercase tracking-wide">Tarikh Selesai</th>
                <th class="border-b px-3 py-2 text-left uppercase tracking-wide">Tindakan</th>
              </tr>
            </thead>
            <tbody id="staffRows">
              @forelse($staffRows as $i => $row)
                @php
                  $isCompleted = $row->orientation_id !== null;
                  $isVerified = $isCompleted && (int) $row->is_verified === 1;
                  $completionDate = $row->completion_date ? \Carbon\Carbon::parse($row->completion_date)->format('d/m/Y H:i') : '-';
                @endphp
                <tr data-filter="{{ strtolower($row->nama_penuh.' '.$row->no_ic) }}" class="odd:bg-white even:bg-gray-50 hover:bg-blue-50">
                  <td class="border-b px-3 py-2 font-semibold">{{ $i + 1 }}</td>
                  <td class="border-b px-3 py-2 font-medium">{{ $row->nama_penuh }}</td>
                  <td class="border-b px-3 py-2">{{ $row->no_ic }}</td>
                  <td class="border-b px-3 py-2">
                    @if($isCompleted)
                      <span class="inline-block rounded border border-emerald-600 bg-emerald-100 text-emerald-800 px-2 py-1 font-semibold text-[10px] uppercase">Selesai</span>
                    @else
                      <span class="inline-block rounded border border-red-600 bg-red-100 text-red-800 px-2 py-1 font-semibold text-[10px] uppercase">Belum Selesai</span>
                    @endif
                  </td>
                  <td class="border-b px-3 py-2">{{ $completionDate }}</td>
                  <td class="border-b px-3 py-2">
                    <div class="flex items-center gap-1">
                      <button class="rounded bg-gray-300 text-gray-700 px-2 py-1 text-[10px] font-semibold uppercase" disabled>Lihat</button>
                      @if($isCompleted && !$isVerified)
                        <button class="verify-btn rounded bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 text-[10px] font-semibold uppercase" data-old-user-id="{{ $row->old_user_id }}" data-unit-slug="{{ $unitSlug }}">Sahkan</button>
                      @elseif($isVerified)
                        <button class="rounded bg-emerald-600 text-white px-2 py-1 text-[10px] font-semibold uppercase" disabled>Disahkan</button>
                      @else
                        <button class="rounded bg-gray-300 text-gray-700 px-2 py-1 text-[10px] font-semibold uppercase" disabled>Sahkan</button>
                      @endif
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="px-3 py-6 text-center text-gray-500">Tiada staff baru dalam unit ini</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </main>

  <footer class="text-center py-3 text-[10px] text-gray-500 border-t bg-white/90">
    Hakcipta Terpelihara &copy; 2026 Unit Pengurusan Maklumat Hospital Baling, Kementerian Kesihatan Malaysia
  </footer>

  <script>
    Chart.register(ChartDataLabels);

    const completed = Number(@json($completedCount));
    const notCompleted = Number(@json($notCompletedCount));
    const total = completed + notCompleted;
    const chartCanvas = document.getElementById('statusChart');

    if (chartCanvas) {
      new Chart(chartCanvas.getContext('2d'), {
        type: 'pie',
        data: {
          labels: ['Selesai', 'Belum Selesai'],
          datasets: [{
            data: [completed, notCompleted],
            backgroundColor: ['#3b82f6', '#ef4444'],
            borderColor: ['#2563eb', '#dc2626'],
            borderWidth: 2,
            hoverOffset: 8,
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom',
              labels: {
                font: { family: "'Poppins', Arial, sans-serif", size: 12, weight: '600' },
                padding: 16,
                usePointStyle: true,
                pointStyle: 'circle',
              },
            },
            datalabels: {
              color: '#fff',
              font: { weight: 'bold', size: 14 },
              formatter: function (value) {
                const percentage = total > 0 ? ((value / total) * 100).toFixed(0) : 0;
                return percentage + '%';
              },
            },
          },
        },
      });
    }

    document.querySelectorAll('.verify-btn').forEach(btn => {
      btn.addEventListener('click', async function () {
        if (!confirm('Adakah anda pasti mahu mengesahkan orientasi staf ini?')) {
          return;
        }

        const payload = {
          old_user_id: Number(this.dataset.oldUserId),
          unit_slug: this.dataset.unitSlug,
        };

        try {
          const res = await fetch(@json(route('penyelia.verify')), {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': @json(csrf_token()),
            },
            body: JSON.stringify(payload),
          });

          const data = await res.json();
          if (data.success) {
            alert('Orientasi berjaya disahkan!');
            window.location.reload();
          } else {
            alert('Ralat: ' + (data.message || 'Gagal mengesahkan orientasi'));
          }
        } catch (error) {
          console.error(error);
          alert('Ralat teknikal berlaku');
        }
      });
    });

    const searchInput = document.getElementById('staffSearchInput');
    const searchBtn = document.getElementById('staffSearchBtn');

    function filterStaffTable() {
      const query = (searchInput?.value || '').toLowerCase().trim();

      document.querySelectorAll('#staffRows tr[data-filter]').forEach(row => {
        const text = row.dataset.filter || '';
        row.style.display = !query || text.includes(query) ? '' : 'none';
      });
    }

    searchInput?.addEventListener('input', filterStaffTable);
    searchBtn?.addEventListener('click', filterStaffTable);
    searchInput?.addEventListener('keypress', function (event) {
      if (event.key === 'Enter') {
        filterStaffTable();
      }
    });
  </script>
</body>
</html>
