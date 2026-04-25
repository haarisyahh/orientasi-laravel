<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Orientasi Staff Hospital Baling</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-navy: #0f2744;
            --brand-teal: #0f766e;
            --brand-sky: #d9eef2;
            --brand-panel: rgba(255, 255, 255, 0.92);
            --brand-line: rgba(15, 39, 68, 0.12);
            --brand-shadow: 0 28px 80px rgba(15, 39, 68, 0.24);
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            color: #10243c;
        }

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
            background:
                linear-gradient(115deg, rgba(10, 31, 53, 0.72) 0%, rgba(15, 118, 110, 0.42) 55%, rgba(217, 238, 242, 0.18) 100%),
                rgba(9, 20, 35, 0.2);
            z-index: -1;
        }

        .page-shell {
            position: relative;
            min-height: 100vh;
            padding: 32px 16px 96px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .glass-card {
            width: min(1180px, 100%);
            background: var(--brand-panel);
            backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.45);
            box-shadow: var(--brand-shadow);
            position: relative;
            overflow: hidden;
            border-radius: 32px;
        }

        .glass-card::before,
        .glass-card::after {
            content: '';
            position: absolute;
            border-radius: 999px;
            pointer-events: none;
        }

        .glass-card::before {
            width: 340px;
            height: 340px;
            top: -110px;
            right: -90px;
            background: radial-gradient(circle, rgba(15, 118, 110, 0.16) 0%, rgba(15, 118, 110, 0) 70%);
        }

        .glass-card::after {
            width: 280px;
            height: 280px;
            left: -70px;
            bottom: -120px;
            background: radial-gradient(circle, rgba(15, 39, 68, 0.12) 0%, rgba(15, 39, 68, 0) 70%);
        }

        .content-grid {
            position: relative;
            z-index: 10;
            display: grid;
            grid-template-columns: minmax(0, 1.08fr) minmax(0, 0.92fr);
        }

        .hero-panel {
            position: relative;
            padding: 40px 40px 32px;
            background:
                linear-gradient(180deg, rgba(15, 39, 68, 0.94) 0%, rgba(15, 39, 68, 0.88) 100%),
                linear-gradient(140deg, rgba(15, 118, 110, 0.22), rgba(255, 255, 255, 0));
            color: #f8fbfd;
        }

        .hero-panel::after {
            content: '';
            position: absolute;
            inset: 24px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 28px;
            pointer-events: none;
        }

        .action-panel {
            padding: 40px;
        }

        .brand-header {
            display: flex;
            align-items: flex-start;
            gap: 18px;
            flex-wrap: wrap;
            padding-bottom: 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            max-width: 38rem;
        }

        .logo-stack {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .logo-chip {
            width: 62px;
            height: 62px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 12px 28px rgba(5, 20, 35, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
        }

        .brand-copy {
            min-width: 0;
            padding-top: 2px;
            padding-left: 18px;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .brand-copy > * {
            display: block;
            margin: 0;
        }

        .brand-copy > * + * {
            margin-top: 4px;
        }

        .brand-copy::before {
            content: '';
            position: absolute;
            left: 0;
            top: 2px;
            bottom: 2px;
            width: 1px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.16) 0%, rgba(217, 238, 242, 0.72) 45%, rgba(255, 255, 255, 0.16) 100%);
        }

        .brand-copy span {
            color: rgba(217, 238, 242, 0.76);
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            line-height: 1.25;
        }

        .brand-copy strong {
            color: #ffffff;
            font-size: 1.2rem;
            font-weight: 700;
            line-height: 1.1;
        }

        .brand-copy small {
            color: rgba(248, 251, 253, 0.78);
            font-size: 0.82rem;
            line-height: 1.5;
        }

        .hero-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 28px;
            padding: 6px 12px;
            border-radius: 999px;
            background: rgba(217, 238, 242, 0.14);
            color: #d9eef2;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .hero-title {
            margin: 18px 0 14px;
            font-size: clamp(2rem, 4vw, 3.35rem);
            line-height: 1.08;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .hero-text {
            max-width: 42rem;
            color: rgba(248, 251, 253, 0.76);
            font-size: 1rem;
            line-height: 1.8;
        }

        .hero-caption {
            margin-top: 12px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: rgba(217, 238, 242, 0.82);
            font-size: 0.76rem;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .hero-caption::before {
            content: '';
            width: 28px;
            height: 1px;
            background: rgba(217, 238, 242, 0.65);
            flex-shrink: 0;
        }

        .info-strip {
            margin-top: 28px;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }

        .info-card {
            padding: 16px 18px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .info-card span {
            display: block;
            font-size: 0.72rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(217, 238, 242, 0.72);
            margin-bottom: 8px;
        }

        .info-card strong {
            font-size: 0.98rem;
            line-height: 1.45;
            color: #ffffff;
        }

        .alert-text {
            color: #fecaca !important;
            text-shadow: 0 0 18px rgba(239, 68, 68, 0.22);
            animation: alertPulse 1.6s ease-in-out infinite;
        }

        @keyframes alertPulse {
            0%, 100% {
                color: #fecaca;
                text-shadow: 0 0 0 rgba(239, 68, 68, 0);
            }
            50% {
                color: #f87171;
                text-shadow: 0 0 18px rgba(239, 68, 68, 0.4);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .alert-text {
                animation: none;
            }
        }

        .panel-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(15, 118, 110, 0.08);
            color: var(--brand-teal);
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .action-title {
            margin-top: 18px;
            font-size: clamp(1.7rem, 3vw, 2.4rem);
            line-height: 1.15;
            font-weight: 800;
            color: var(--brand-navy);
        }

        .action-copy {
            margin-top: 12px;
            color: #5f6f82;
            line-height: 1.7;
        }

        .role-list {
            margin-top: 26px;
            display: grid;
            gap: 14px;
        }

        .role-card {
            display: grid;
            grid-template-columns: auto minmax(0, 1fr) auto;
            gap: 16px;
            align-items: center;
            padding: 18px 18px 18px 16px;
            border-radius: 22px;
            background: #ffffff;
            border: 1px solid var(--brand-line);
            box-shadow: 0 18px 40px rgba(15, 39, 68, 0.08);
            text-decoration: none;
            transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
        }

        .role-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 22px 46px rgba(15, 39, 68, 0.14);
            border-color: rgba(15, 118, 110, 0.28);
        }

        .role-icon {
            width: 54px;
            height: 54px;
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            font-weight: 700;
            color: #ffffff;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.28);
        }

        .role-card h3 {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
            color: #12263f;
        }

        .role-card p {
            margin: 4px 0 0;
            color: #66768a;
            font-size: 0.92rem;
            line-height: 1.55;
        }

        .role-arrow {
            width: 40px;
            height: 40px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--brand-navy);
            background: #eef5f7;
            font-size: 1.2rem;
            font-weight: 700;
        }

        .role-card.staff .role-icon {
            background: linear-gradient(135deg, #0f766e 0%, #14967f 100%);
        }

        .role-card.penyelia .role-icon {
            background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
        }

        .role-card.admin .role-icon {
            background: linear-gradient(135deg, #334155 0%, #0f172a 100%);
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse-border {
            0%, 100% {
                border-left-color: #b45309;
            }
            50% {
                border-left-color: #f59e0b;
            }
        }

        .warning-box {
            animation: slideInDown 0.6s ease-out, pulse-border 2s infinite ease-in-out;
            margin-top: 24px;
            background: linear-gradient(135deg, #fff8eb 0%, #fff2d8 100%);
            border-left: 4px solid #d97706;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.6);
        }

        .footer-bar {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            padding: 14px 16px;
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(16px);
            border-top: 1px solid rgba(15, 39, 68, 0.08);
        }

        @media (max-width: 960px) {
            .page-shell {
                padding-top: 18px;
                padding-bottom: 112px;
            }

            .content-grid {
                grid-template-columns: 1fr;
            }

            .hero-panel,
            .action-panel {
                padding: 28px 22px;
            }

            .hero-panel::after {
                inset: 16px;
            }

            .info-strip {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .brand-header {
                gap: 14px;
                padding-bottom: 18px;
            }

            .logo-chip {
                width: 56px;
                height: 56px;
                border-radius: 14px;
            }

            .brand-copy strong {
                font-size: 1.02rem;
            }

            .brand-copy small {
                font-size: 0.76rem;
            }

            .brand-copy {
                padding-top: 0;
                padding-left: 14px;
            }

            .role-card {
                grid-template-columns: auto minmax(0, 1fr);
            }

            .role-arrow {
                display: none;
            }

            .footer-bar {
                position: static;
                margin-top: 20px;
            }
        }
    </style>
</head>

<body>

    <div class="bg-image" aria-hidden="true"></div>
    <div class="bg-overlay" aria-hidden="true"></div>

    <main class="page-shell">
        <section class="glass-card">
            <div class="content-grid">
                <div class="hero-panel">
                    <div class="brand-header">
                        <div class="logo-stack">
                            <div class="logo-chip">
                                <img src="{{ asset('assets/logo1.png') }}" alt="Logo Kementerian Kesihatan Malaysia" class="w-full h-full object-contain" onerror="this.style.display='none'">
                            </div>

                            <div class="logo-chip">
                                <img src="{{ asset('assets/logo2.png') }}" alt="Logo Hospital Baling" class="w-full h-full object-contain" onerror="this.style.display='none'">
                            </div>
                        </div>

                        <div class="brand-copy">
                            <span>KEMENTERIAN KESIHATAN MALAYSIA</span>
                            <strong>Hospital Baling</strong>
                            <span>E-ORIENTASI SISTEM</span>
                        </div>
                    </div>

                    
                    <h1 class="hero-title">e-Orientasi</h1>
                    <p class="hero-caption">Urus orientasi staff dengan lebih jelas dan tersusun.</p>
                    

                    <div class="info-strip">
                        <div class="info-card">
                            <span>Peranan</span>
                            <strong>3 akses utama untuk staf, penyelia dan admin</strong>
                        </div>

                        <div class="info-card">
                            <span>Akses</span>
                            <strong>Reka letak jelas untuk tindakan pantas dan mesra pengguna</strong>
                        </div>

                        <div class="info-card">
                            <span>Keselamatan</span>
                            <strong class="alert-text">Berhati-hati dengan penipuan dan elakkan daripada pautan yang tidak sah atau mencurigakan. Pastikan anda hanya menggunakan laman web rasmi.</strong>
                        </div>
                    </div>
                </div>

                <div class="action-panel">
                    <div class="panel-tag">Akses Sistem</div>
                    <h2 class="action-title">Pilih kategori pengguna anda</h2>
                    <p class="action-copy">
                        Setiap peranan disusun mengikut tugasan sebenar bagi memudahkan proses daftar, semakan dan pengurusan maklumat orientasi.
                    </p>

                    <div class="role-list">
                        <a href="{{ route('register.staff') }}" class="role-card staff" aria-label="Daftar sebagai staff baru">
                            <div class="role-icon">S</div>
                            <div>
                                <h3>Staff Baru</h3>
                                <p>Pendaftaran akaun dan akses awal untuk staf yang baru memulakan orientasi.</p>
                            </div>
                            <span class="role-arrow" aria-hidden="true">&rarr;</span>
                        </a>

                        <a href="{{ route('register.penyelia') }}" class="role-card penyelia" aria-label="Daftar sebagai penyelia unit">
                            <div class="role-icon">P</div>
                            <div>
                                <h3>Penyelia Unit</h3>
                                <p>Akses untuk penyelia memantau, menyemak dan mengesahkan aktiviti bersemuka bersama staf.</p>
                            </div>
                            <span class="role-arrow" aria-hidden="true">&rarr;</span>
                        </a>

                        <a href="{{ route('login.admin') }}" class="role-card admin" aria-label="Log masuk sebagai admin">
                            <div class="role-icon">A</div>
                            <div>
                                <h3>Admin</h3>
                                <p>Log masuk ke modul pentadbiran untuk pengurusan sistem, kandungan dan laporan.</p>
                            </div>
                            <span class="role-arrow" aria-hidden="true">&rarr;</span>
                        </a>
                    </div>

                    
                </div>
            </div>
        </section>
    </main>

    <footer class="footer-bar text-center">
        <p class="text-[10px] md:text-xs text-slate-600 tracking-[0.08em] uppercase">
            Hakcipta Terpelihara &copy; 2026 Unit Pengurusan Maklumat Hospital Baling,
            Kementerian Kesihatan Malaysia
        </p>
    </footer>

</body>
</html>
