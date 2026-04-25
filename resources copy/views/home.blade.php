<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Orientasi Staff Hospital Baling</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
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
            background: rgba(0, 0, 0, 0.4);
            z-index: -1;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
        }

        .building-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.05;
            max-width: 80%;
            pointer-events: none;
            z-index: 0;
        }

        .content-wrapper {
            position: relative;
            z-index: 10;
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
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen">

    <div class="bg-image" aria-hidden="true"></div>
    <div class="bg-overlay" aria-hidden="true"></div>

    <div class="glass-card p-8 rounded-2xl shadow-2xl max-w-5xl w-full mx-4 text-center">
        <img src="{{ asset('assets/building.png') }}" alt="" class="building-watermark" onerror="this.style.display='none'">

        <div class="content-wrapper">
            <div class="flex justify-center items-center gap-6 mb-4">
                <div class="w-28 h-28 rounded-full bg-white shadow-lg border flex items-center justify-center">
                    <img src="{{ asset('assets/logo1.png') }}" alt="Logo 1" class="w-16 h-16 object-contain" onerror="this.style.display='none'">
                </div>

                <div class="w-28 h-28 rounded-full bg-white shadow-lg border flex items-center justify-center">
                    <img src="{{ asset('assets/logo2.png') }}" alt="Logo 2" class="w-20 h-20 object-contain" onerror="this.style.display='none'">
                </div>
            </div>

            <h1 class="text-2xl font-extrabold text-gray-800 uppercase tracking-wide">Selamat Datang</h1>
            <h2 class="text-2xl font-extrabold text-gray-800 mt-1 uppercase leading-tight">e-Orientasi Staff Hospital Baling sistem</h2>

            <p class="text-gray-500 mt-3 mb-4 font-medium text-sm">Sila Pilih Peranan Anda untuk Mendaftar / Log Masuk</p>

            <div class="space-y-2">
                <a href="{{ route('register.staff') }}" class="flex justify-center items-center w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-base rounded-lg shadow-lg transition uppercase tracking-wider">👤 Staff Baru</a>

                <a href="{{ route('register.penyelia') }}" class="flex justify-center items-center w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-base rounded-lg shadow-lg transition uppercase tracking-wider">👥 Penyelia Unit</a>

                <a href="{{ route('login.admin') }}" class="flex justify-center items-center w-full py-3 bg-slate-700 hover:bg-slate-800 text-white font-bold text-base rounded-lg shadow-lg transition uppercase tracking-wider">🔐 Admin</a>
            </div>

            <div class="warning-box bg-amber-50 border-l-4 border-amber-500 p-3 mt-4 rounded-r-lg">
                <div class="flex items-start gap-2">
                    <span class="text-2xl flex-shrink-0">⚠️</span>
                    <div class="text-left">
                        <p class="text-amber-900 font-semibold text-sm mb-1">Amaran Keselamatan</p>
                        <p class="text-amber-800 text-xs leading-relaxed">
                            Sila berhati-hati dengan penipuan (scammer) dan elakkan daripada mengklik sebarang pautan yang tidak sah atau mencurigakan. Pastikan anda hanya menggunakan laman web rasmi Hospital Baling.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="fixed bottom-0 w-full bg-white bg-opacity-90 py-3 text-center border-t border-gray-200">
        <p class="text-[10px] md:text-xs text-gray-500 font tracking-tight">
            Hakcipta Terpelihara &copy; 2026 Unit Pengurusan Maklumat Hospital Baling,
            Kementerian Kesihatan Malaysia
        </p>
    </footer>

</body>
</html>
