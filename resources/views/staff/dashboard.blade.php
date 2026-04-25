<!doctype html>
<html lang="ms">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Program Orientasi Staff Hospital Baling</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}?v=2">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-navy: #0f2744;
            --brand-teal: #0f766e;
            --brand-sky: #eaf4f5;
            --brand-line: rgba(15, 39, 68, 0.12);
            --brand-shadow: 0 24px 60px rgba(15, 39, 68, 0.18);
        }

        body {
            display: block;
            min-height: 100vh;
            padding: 0;
            color: #10243c;
        }

        .bg-overlay {
            background:
                linear-gradient(120deg, rgba(10, 31, 53, 0.88) 0%, rgba(15, 118, 110, 0.62) 55%, rgba(234, 244, 245, 0.22) 100%),
                rgba(10, 31, 53, 0.28);
        }

        .dashboard-shell {
            position: relative;
            min-height: 100vh;
            padding: 28px 16px 92px;
        }

        .dashboard-card {
            width: min(1180px, calc(100% - 16px));
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.93);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.45);
            border-radius: 30px;
            box-shadow: var(--brand-shadow);
            position: relative;
            overflow: hidden;
        }

        .dashboard-card::before,
        .dashboard-card::after {
            content: '';
            position: absolute;
            border-radius: 999px;
            pointer-events: none;
        }

        .dashboard-card::before {
            width: 320px;
            height: 320px;
            top: -120px;
            right: -90px;
            background: radial-gradient(circle, rgba(15, 118, 110, 0.14) 0%, rgba(15, 118, 110, 0) 70%);
        }

        .dashboard-card::after {
            width: 300px;
            height: 300px;
            left: -100px;
            bottom: -140px;
            background: radial-gradient(circle, rgba(15, 39, 68, 0.12) 0%, rgba(15, 39, 68, 0) 70%);
        }

        .dashboard-grid {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: minmax(280px, 0.88fr) minmax(360px, 1.12fr);
        }

        .hero-panel {
            position: relative;
            padding: 30px 28px 24px;
            color: #f8fbfd;
            background:
                linear-gradient(180deg, rgba(15, 39, 68, 0.97) 0%, rgba(15, 39, 68, 0.92) 100%),
                linear-gradient(135deg, rgba(15, 118, 110, 0.18), rgba(255, 255, 255, 0));
        }

        .hero-panel::after {
            content: '';
            position: absolute;
            inset: 20px;
            border-radius: 26px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            pointer-events: none;
        }

        .action-panel {
            padding: 36px 34px 30px;
        }

        .topbar {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.14);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.88);
        }

        .eyebrow .dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: #86efac;
        }

        .welcome-label {
            margin-top: 20px;
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.16em;
            color: rgba(234, 244, 245, 0.74);
        }

        .welcome-name {
            margin-top: 8px;
            font-size: clamp(1.7rem, 3vw, 2.45rem);
            line-height: 1.12;
            font-weight: 700;
            letter-spacing: -0.03em;
        }

        .hero-copy {
            margin-top: 14px;
            max-width: 34rem;
            font-size: 0.9rem;
            line-height: 1.7;
            color: rgba(248, 251, 253, 0.78);
        }

        .hero-meta {
            margin-top: 22px;
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .meta-card {
            padding: 12px 14px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .meta-card span {
            display: block;
            margin-bottom: 8px;
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.13em;
            text-transform: uppercase;
            color: rgba(234, 244, 245, 0.72);
        }

        .meta-card strong {
            font-size: 0.95rem;
            line-height: 1.5;
            color: #fff;
        }

        .logout-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 14px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(239, 68, 68, 0.18);
            color: #fff;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: rgba(220, 38, 38, 0.24);
        }

        .panel-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(15, 118, 110, 0.08);
            color: var(--brand-teal);
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .panel-title {
            margin-top: 18px;
            font-size: clamp(1.6rem, 3vw, 2.25rem);
            line-height: 1.12;
            font-weight: 700;
            color: var(--brand-navy);
        }

        .panel-copy {
            margin-top: 10px;
            color: #5d7084;
            line-height: 1.75;
            font-size: 0.95rem;
        }

        .content {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin: 24px 0 0;
        }

        .pill {
            min-height: 108px;
            justify-content: flex-start;
            text-align: left;
            padding: 18px 18px 18px 20px;
            border-radius: 22px;
            border: 1px solid var(--brand-line);
            box-shadow: 0 16px 34px rgba(15, 39, 68, 0.08);
            position: relative;
            font-size: 0.95rem;
            line-height: 1.6;
            overflow: hidden;
        }

        .pill::before {
            content: '';
            position: absolute;
            top: 16px;
            right: 18px;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #7a8b9a;
        }

        .pill:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(15, 39, 68, 0.12);
        }

        .pill.completed {
            background: linear-gradient(180deg, #ecfff3, #f7fffa);
            color: #0b5d33;
        }

        .center-button {
            justify-content: flex-start;
            margin: 26px 0 0;
        }

        .check-btn {
            padding: 14px 24px;
            border-radius: 16px;
            background: linear-gradient(135deg, #0f2744 0%, #1c4f78 100%);
            color: #fff;
            border: 0;
            box-shadow: 0 16px 34px rgba(15, 39, 68, 0.2);
            font-size: 0.95rem;
            letter-spacing: 0.02em;
        }

        .check-btn:hover {
            box-shadow: 0 20px 38px rgba(15, 39, 68, 0.24);
        }

        .check-btn.complete {
            background: linear-gradient(135deg, #0f766e 0%, #16a34a 100%);
        }

        .status-panel {
            margin-top: 22px;
            padding: 16px 18px;
            border-radius: 20px;
            border: 1px solid #d9e6ea;
            background: linear-gradient(180deg, #f8fbfd 0%, #f0f6f8 100%);
        }

        .status-panel p {
            margin: 0;
            color: #5f7285;
            font-size: 0.92rem;
            line-height: 1.7;
        }

        .footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            margin-top: 0;
            padding: 14px 16px;
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(14px);
            border-top: 1px solid rgba(15, 39, 68, 0.08);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            font-size: 10px;
            color: #64748b;
            z-index: 30;
        }

        .building-watermark {
            position: absolute;
            left: 50%;
            top: 52%;
            transform: translate(-50%, -50%);
            opacity: 0.06;
            max-width: 78%;
            pointer-events: none;
            filter: grayscale(1);
        }

        .modal {
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.55);
        }

        @media (max-width: 980px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .hero-panel,
            .action-panel {
                padding: 26px 22px;
            }

            .hero-panel::after {
                inset: 14px;
            }
        }

        @media (max-width: 640px) {
            .dashboard-shell {
                padding: 16px 12px 12px;
            }

            .dashboard-card {
                width: 100%;
                border-radius: 24px;
            }

            .content {
                grid-template-columns: 1fr;
            }

            .topbar {
                flex-direction: column;
                align-items: stretch;
            }

            .footer {
                position: static;
                margin-top: 18px;
                border-radius: 16px;
            }
        }

        .pill.progress-complete {
            background: linear-gradient(135deg, #4CAF50, #45a049) !important;
            color: #fff !important;
            box-shadow: 0 6px 16px rgba(76, 175, 80, 0.3) !important;
            font-weight: 700;
        }
        .pill.progress-complete:hover {
            box-shadow: 0 8px 20px rgba(76, 175, 80, 0.4) !important;
        }
    </style>
</head>
<body>
    <div class="bg-image" aria-hidden="true"></div>
    <div class="bg-overlay" aria-hidden="true"></div>
    <main class="dashboard-shell">
        <section class="dashboard-card">
            <div class="dashboard-grid">
                <div class="hero-panel">
                    <div class="topbar">
                        <div class="eyebrow">
                            <span class="dot"></span>
                            Dashboard Orientasi Staff
                        </div>
                        <form action="{{ route('logout.staff') }}" method="POST">
                            @csrf
                            <button type="submit" class="logout-btn">Log Keluar</button>
                        </form>
                    </div>

                    <p class="welcome-label">Selamat Datang</p>
                    <h1 class="welcome-name">{{ $staffName }}</h1>
                    

                    <div class="hero-meta">
                        <div class="meta-card">
                            <span>Dashboard</span>
                            <strong>Memudahkan anda mengakses setiap unit orientasi, menyemak kemajuan semasa, dan melengkapkan proses orientasi secara lebih teratur dan jelas.</strong>
                        </div>
                        <div class="meta-card">
                            <span>Tindakan</span>
                            <strong>Pilih unit untuk mula, sambung atau semak pembelajaran anda.</strong>
                        </div>
                        <div class="meta-card">
                            <span>Status</span>
                            <strong>Butang semakan akan memaparkan baki unit atau akses sijil anda.</strong>
                        </div>
                    </div>
                </div>

                <div class="action-panel">
                    <div class="panel-tag">Akses Unit</div>
                    <p class="panel-copy">
                        Klik pada mana-mana unit di bawah untuk membuka kandungan orientasi. Kemajuan yang telah lengkap akan kekal ditanda seperti sedia ada.
                    </p>

                    <div class="content">
                        @foreach($units as $unit)
                            <div class="pill" data-unit="{{ $unit['slug'] }}">{{ $unit['label'] }}</div>
                        @endforeach
                    </div>

                    <div class="center-button">
                        <button id="checkBtn" class="check-btn">Semak Status Orientasi</button>
                    </div>

                    <br>
                </div>
            </div>

            <img src="{{ asset('assets/building.png') }}" alt="" class="building-watermark" onerror="this.style.display='none'">

            <div id="modal-warning" class="modal-overlay" aria-hidden="true">
                <div class="modal">
                    <div class="modal-icon">❗</div>
                    <h2 class="modal-title">PERINGATAN!</h2>
                    <div class="modal-body">"Sila lengkapkan baki <span id="missingCount"></span> unit lagi untuk menamatkan orientasi."</div>
                    <div class="modal-actions">
                        <button id="btn-kembali" class="btn primary">Kembali</button>
                        <button id="btn-close-warning" class="btn outline">Tutup</button>
                    </div>
                </div>
            </div>

            <div id="modal-success" class="modal-overlay" aria-hidden="true">
                <div class="modal">
                    <div class="modal-icon medal">🏅</div>
                    <h2 class="modal-title">SYABAS DAN SELAMAT DATANG!</h2>
                    <div class="modal-body">Anda kini telah mengenali kesemua unit utama di Hospital Baling. Terima kasih atas komitmen anda sepanjang sesi orientasi ini. Sijil penghargaan anda kini sedia untuk dimuat turun. Selamat bertugas dan selamat berbakti!</div>
                    <div class="modal-actions">
                        <button id="btn-download" class="btn primary">Dapatkan Sijil Anda</button>
                        <button id="btn-close-success" class="btn outline">Tutup</button>
                    </div>
                </div>
            </div>

            <div class="footer">Hakcipta Terpelihara © 2026 Unit Pengurusan Maklumat Hospital Baling, Kementerian Kesihatan Malaysia</div>
        </section>
    </main>

    <script>
        (function(){
            const pills = document.querySelectorAll('.pill');
            const checkBtn = document.getElementById('checkBtn');
            const modalWarning = document.getElementById('modal-warning');
            const modalSuccess = document.getElementById('modal-success');
            const missingCount = document.getElementById('missingCount');
            const btnKembali = document.getElementById('btn-kembali');
            const btnCloseWarning = document.getElementById('btn-close-warning');
            const btnCloseSuccess = document.getElementById('btn-close-success');
            const btnDownload = document.getElementById('btn-download');
            const unitRouteTemplate = @json($unitRouteTemplate);
            const completedUnits = @json($completedUnits);

            function updateCheckButton(){
                const total = pills.length;
                const done = document.querySelectorAll('.pill.completed').length;
                if(done === total){
                    checkBtn.classList.add('complete');
                    checkBtn.setAttribute('aria-pressed','true');
                } else {
                    checkBtn.classList.remove('complete');
                    checkBtn.removeAttribute('aria-pressed');
                }
            }

            function showModal(modal){ modal.setAttribute('aria-hidden','false'); }
            function hideModal(modal){ modal.setAttribute('aria-hidden','true'); }

            function applyCompletionState(arr) {
                pills.forEach(p => {
                      const target = (p.dataset.unit || '').replace(/\.php$/, '');
                    const isCompleted = arr.includes(target);
                    if (isCompleted) {
                        p.classList.add('progress-complete', 'completed');
                    } else {
                        p.classList.remove('progress-complete', 'completed');
                    }
                });
                updateCheckButton();
            }

            async function refreshCompletionStatus() {
                try {
                    const response = await fetch(@json(route('staff.completion-status')), {
                        method: 'GET',
                        headers: {
                            'Cache-Control': 'no-cache',
                            'Pragma': 'no-cache'
                        }
                    });
                    const data = await response.json();
                    if (data.success && Array.isArray(data.completed_units)) {
                        applyCompletionState(data.completed_units);
                    }
                } catch (error) {
                    console.error('Failed to refresh completion status:', error);
                }
            }

            pills.forEach(p => {
                p.addEventListener('click', () => {
                    const target = p.dataset.unit;
                    if (target) {
                        window.location.href = unitRouteTemplate.replace('__UNIT__', target);
                    }
                });
            });

            applyCompletionState(Array.isArray(completedUnits) ? completedUnits : []);

            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    refreshCompletionStatus();
                }
            });

            window.addEventListener('focus', function() {
                refreshCompletionStatus();
            });

            checkBtn.addEventListener('click', () => {
                const total = pills.length;
                const done = document.querySelectorAll('.pill.completed').length;
                if(done === total){
                    showModal(modalSuccess);
                } else {
                    missingCount.textContent = total - done;
                    showModal(modalWarning);
                }
            });

            btnCloseWarning.addEventListener('click', () => hideModal(modalWarning));
            btnKembali.addEventListener('click', () => {
                hideModal(modalWarning);
                const first = Array.from(pills).find(x => !x.classList.contains('completed'));
                if(first){
                    first.scrollIntoView({behavior:'smooth', block:'center'});
                    first.style.transition = 'box-shadow .1s ease, transform .08s ease';
                    first.style.boxShadow = '0 0 0 4px rgba(59,130,246,0.18)';
                    setTimeout(()=> { first.style.boxShadow = ''; }, 800);
                }
            });

            btnCloseSuccess.addEventListener('click', () => hideModal(modalSuccess));

            btnDownload.addEventListener('click', () => {
                window.location.href = @json(route('staff.certificate'));
            });

            document.querySelectorAll('.modal-overlay').forEach(m => {
                m.addEventListener('click', (e) => { if(e.target === m) hideModal(m); });
            });
        })();
    </script>
</body>
</html>
