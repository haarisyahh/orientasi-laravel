<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sijil Penghargaan - Hospital Baling</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --certificate-base-width: 1200;
            --certificate-base-height: 675;
            --certificate-scale: 1;
        }

        body {
            font-family: 'Playfair Display', serif;
            background: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .certificate-page {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .certificate-stage {
            width: min(1200px, 100%);
            display: flex;
            justify-content: center;
        }

        .certificate-container {
            max-width: 1200px;
            width: 100%;
            aspect-ratio: 16 / 9;
            background: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border-radius: 4px;
        }

        .certificate-border {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            width: 100%;
            height: 100%;
        }

        .certificate-content {
            position: relative;
            z-index: 1;
            padding: 40px 50px;
            text-align: center;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .cert-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            margin-bottom: 12px;
        }

        .cert-logo img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            mix-blend-mode: multiply;
            filter: contrast(1.05) saturate(1.1);
        }

        .cert-logo-text {
            text-align: center;
            line-height: 1.2;
            color: #111;
            letter-spacing: 1px;
        }

        .cert-logo-text .kmm {
            font-size: 12px;
            font-weight: 700;
        }

        .cert-logo-text .hb {
            font-size: 10px;
            font-weight: 600;
        }

        .cert-title {
            font-size: 36px;
            font-weight: 700;
            letter-spacing: 6px;
            margin-bottom: 8px;
            color: #000;
        }

        .cert-subtitle {
            font-size: 13px;
            font-weight: 400;
            letter-spacing: 1.5px;
            margin: 0 auto 30px auto;
            color: #333;
            padding-bottom: 10px;
            border-bottom: 1px solid #ccc;
            display: block;
            width: 60%;
        }

        .cert-intro {
            font-size: 12px;
            font-weight: 400;
            letter-spacing: 3px;
            margin-bottom: 15px;
            color: #333;
        }

        .cert-name {
            font-family: 'Playfair Display', serif;
            font-size: 48px;
            font-weight: 700;
            color: #000;
            margin: 15px 0;
            line-height: 1.2;
        }

        .cert-message {
            font-size: 12px;
            font-weight: bold;
            line-height: 1;
            color: #333;
            margin: 14px auto 10px;
            max-width: 500px;
        }

        .cert-details {
            display: flex;
            justify-content: center;
            gap: 60px;
            margin: 8px 0 10px;
            font-size: 12px;
            color: #333;
        }

        .detail-item {
            text-align: center;
        }

        .detail-label {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .detail-value {
            font-weight: 400;
        }

        .cert-signatures {
            display: flex;
            text-align: center;
            justify-content: center;
            margin-top: 4px;
            padding-top: 0;
        }

        .signature-block {
            text-align: center;
            width: 280px;
            max-width: 100%;
            margin: 0 auto;
            transform: translateY(0);
        }

        .signature-image {
            display: block;
            width: 165px;
            max-width: 100%;
            height: 64px;
            object-fit: contain;
            margin: 0 auto 4px;
            mix-blend-mode: multiply;
            filter: grayscale(1) contrast(1.25);
        }

        .signature-line {
            border-top: 2px solid #000;
            margin-bottom: 8px;
            width: 100%;
        }

        .signature-name {
            font-size: 10px;
            font-weight: 700;
            text-transform: none;
            line-height: 1.15;
            text-align: center;
            max-width: 100%;
            margin: 0 auto;
        }

        .signature-role {
            font-size: 9px;
            font-weight: 400;
            text-transform: none;
            line-height: 1.15;
            margin-top: 2px;
            margin-bottom: 0;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .certificate-container {
                max-width: 100%;
                box-shadow: none;
            }

            .no-print {
                display: none !important;
            }

            @page {
                size: landscape;
                margin: 0;
            }
        }

        .button-container {
            position: fixed;
            top: 20px;
            left: 20px;
            right: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            pointer-events: none;
        }

        .icon-btn {
            width: 46px;
            height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 999px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
            text-decoration: none;
            color: white;
            pointer-events: auto;
        }

        .icon-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0,0,0,0.25);
        }

        .icon-btn svg {
            width: 20px;
            height: 20px;
            display: block;
        }

        .icon-close {
            background: #ef4444;
        }

        .icon-close:hover {
            background: #dc2626;
        }

        .icon-download {
            background: #10b981;
        }

        .icon-download:hover {
            background: #059669;
        }

        @media (max-width: 900px) {
            body {
                display: block;
                padding: 72px 10px 16px;
                background: white;
            }

            .certificate-page {
                display: flex;
                justify-content: center;
            }

            .certificate-stage {
                width: 100%;
                height: calc((var(--certificate-base-height) * 1px) * var(--certificate-scale));
                overflow: hidden;
                align-items: flex-start;
            }

            .certificate-container {
                width: calc(var(--certificate-base-width) * 1px);
                max-width: none;
                flex: 0 0 auto;
                transform: scale(var(--certificate-scale));
                transform-origin: top center;
            }

            .button-container {
                top: 14px;
            }
        }

        @media (max-width: 520px) {
            body {
                padding: 74px 8px 16px;
            }
        }
    </style>
</head>
<body>
    <div class="button-container no-print">
        <button onclick="downloadCertificate()" class="icon-btn icon-download" aria-label="Muat turun sijil" title="Muat turun sijil">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 3v12"></path>
                <path d="M7 10l5 5 5-5"></path>
                <path d="M5 21h14"></path>
            </svg>
        </button>
        <a href="{{ $backUrl ?? route('staff.dashboard') }}" class="icon-btn icon-close" aria-label="Tutup" title="Tutup">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M18 6L6 18"></path>
                <path d="M6 6l12 12"></path>
            </svg>
        </a>
    </div>

    <div class="certificate-page">
        <div class="certificate-stage">
        <div class="certificate-container">
        <svg class="certificate-border" viewBox="0 0 1200 675" preserveAspectRatio="none">
            <path d="M 960 0 Q 1200 0 1200 200 L 1200 0 Z" fill="#1e3a8a" stroke="#d4af37" stroke-width="3"/>
            <path d="M 800 0 Q 1100 50 1200 250 L 1200 0 Z" fill="#1e40af" opacity="0.8"/>
            <path d="M 240 675 Q 0 675 0 475 L 0 675 Z" fill="#1e3a8a" stroke="#d4af37" stroke-width="3"/>
            <path d="M 400 675 Q 100 625 0 425 L 0 675 Z" fill="#1e40af" opacity="0.8"/>
            <rect x="30" y="30" width="1140" height="615" fill="none" stroke="#d4af37" stroke-width="2"/>
        </svg>

        <div class="certificate-content">
            <div class="cert-logo">
                <img src="{{ asset('assets/logo1.png') }}" alt="Logo Kementerian Kesihatan Malaysia" onerror="this.style.display='none'">
                <div class="cert-logo-text">
                    <div class="kmm">KEMENTERIAN KESIHATAN MALAYSIA</div>
                    <div class="hb">HOSPITAL BALING</div>
                </div>
            </div>
            <h1 class="cert-title">SIJIL PENGHARGAAN</h1>
            <div class="cert-subtitle">HOSPITAL BALING</div>

            <p class="cert-intro">DENGAN SUKACITANYA DIBERIKAN KEPADA</p>

            <div class="cert-name">{{ strtoupper($userName) }}</div>

            <p class="cert-message">
                Tahniah! Anda telah menamatkan Orientasi di Hospital Baling.<br>
                Terima kasih atas komitmen anda sepanjang sesi Orientasi ini.
            </p>

            <div class="cert-details">
                <div class="detail-item">
                    <div class="detail-label">Tarikh:</div>
                    <div class="detail-value">{{ $tarikh }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Masa:</div>
                    <div class="detail-value">{{ $masa }}</div>
                </div>
            </div>

            <div class="cert-signatures">
                <div class="signature-block">
                    <img src="{{ asset('assets/signature-pengarah.png') }}" alt="Tandatangan Pengarah Hospital Baling" class="signature-image" onerror="this.style.display='none'">
                    <div class="signature-line"></div>
                    <div class="signature-name">DR. SELVANAAYAGAM SHANMUGANATHAN</div>
                    <div class="signature-role">Pengarah Hospital Baling</div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    <script>
        function updateCertificateScale() {
            const stage = document.querySelector('.certificate-stage');
            if (!stage) {
                return;
            }

            if (window.innerWidth > 900) {
                document.documentElement.style.setProperty('--certificate-scale', '1');
                stage.style.height = '';
                return;
            }

            const stageWidth = Math.max(stage.clientWidth, 280);
            const scale = Math.min(1, stageWidth / 1200);
            document.documentElement.style.setProperty('--certificate-scale', String(scale));
        }

        async function downloadCertificate() {
            const element = document.querySelector('.certificate-container');
            const userName = document.querySelector('.cert-name').textContent.trim();
            
            try {
                const canvas = await html2canvas(element, {
                    scale: 3,
                    backgroundColor: '#ffffff',
                    allowTaint: true,
                    useCORS: true,
                    logging: false,
                    letterRendering: true
                });
                
                const imgData = canvas.toDataURL('image/png');
                const { jsPDF } = window.jspdf;
                
                const doc = new jsPDF({
                    orientation: 'landscape',
                    unit: 'mm',
                    format: 'a4'
                });
                
                const pageWidth = doc.internal.pageSize.getWidth();
                const pageHeight = doc.internal.pageSize.getHeight();
                
                doc.addImage(imgData, 'PNG', 0, 0, pageWidth, pageHeight);
                
                const fileName = `Sijil_Penghargaan_${userName.replace(/\s+/g, '_')}.pdf`;
                doc.save(fileName);
            } catch (error) {
                console.error('Error generating PDF:', error);
                alert('Ralat semasa menjana sijil. Sila cuba lagi.');
            }
        }
        
        window.addEventListener('DOMContentLoaded', () => {
            updateCertificateScale();

            const nameElement = document.querySelector('.cert-name');
            const name = nameElement.textContent;
            
            if (name.length > 30) {
                nameElement.style.fontSize = '40px';
            } else if (name.length > 20) {
                nameElement.style.fontSize = '44px';
            }
        });

        window.addEventListener('resize', updateCertificateScale);
    </script>
</body>
</html>
