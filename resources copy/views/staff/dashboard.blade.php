<!doctype html>
<html lang="ms">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Program Orientasi Staff Hospital Baling</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}?v=2">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
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
    <div class="container">
        <div class="card">
            <div class="user-header">
                <div>
                    <h2 style="margin: 0; font-size: 14px; color: #666;">Selamat datang,</h2>
                    <div class="user-info">
                        <strong style="font-size: 16px; color: #1a1a1a;">{{ $staffName }}</strong>
                        <form action="{{ route('logout.staff') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" style="color: #ef4444; text-decoration: none; font-weight: 600; font-size: 12px; background: #fee2e2; padding: 6px 12px; border-radius: 6px; border: none; cursor: pointer;">Log Keluar</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="header">
                <div class="logo-badges">
                    <div class="logo-badge"><img src="{{ asset('assets/logo1.png') }}" alt="logo left" onerror="this.style.display='none'"></div>
                    <div class="logo-badge"><img src="{{ asset('assets/logo2.png') }}" alt="logo right" onerror="this.style.display='none'"></div>
                </div>

                <h1 class="title">PROGRAM ORIENTASI STAFF HOSPITAL BALING</h1>
            </div>

            <div class="content">
                @foreach($units as $unit)
                    <div class="pill" data-unit="{{ $unit['slug'] }}">{{ $unit['label'] }}</div>
                @endforeach
            </div>

            <div class="center-button">
                <button id="checkBtn" class="check-btn">Semak Status Orientasi</button>
            </div>

            <div class="footer">Hakcipta Terpelihara © 2026 Unit Pengurusan Maklumat Hospital Baling, Kementerian Kesihatan Malaysia</div>

            <img src="{{ asset('assets/building.png') }}" alt="" style="position:absolute;left:50%;top:50%;transform:translate(-50%,-40%);opacity:0.08;max-width:75%;pointer-events:none;filter:grayscale(1);" onerror="this.style.display='none'">

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
        </div>
    </div>

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
