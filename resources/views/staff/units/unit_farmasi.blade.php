@if(!$hasSlides)
  <div class="hero" role="img" aria-label="Hospital building background"></div>
@elseif($currentSlide === $totalSlides)
  <div class="completion-card" id="slideImage">
    <div class="completion-title">Status Kemajuan: <span class="progress-text-value">0%</span></div>
    <div class="progress-circle" role="img" aria-label="Status kemajuan 0%">
      <svg viewBox="0 0 120 120" aria-hidden="true">
        <circle class="progress-circle-track" cx="60" cy="60" r="52"></circle>
        <circle class="progress-circle-fill" cx="60" cy="60" r="52"></circle>
      </svg>
      <div class="progress-circle-text">0%</div>
    </div>
    <div class="completion-message">Tahniah! Anda telah berjaya lengkap {{ $unitTitle }}.</div>
  </div>
@elseif($isFarmasiDirectorySlide)
  <div class="directory-card" id="slideImage">
    <div class="directory-title">FARMASI DIRECTORY</div>
    <div class="directory-lead-wrap">
      <div class="directory-item directory-item-lead"><div class="directory-name">FADLIZA BINTI MOHD HUSSEIN</div><div class="directory-role">KETUA PEGAWAI FARMASI</div><div class="directory-ext">EXT: 3061</div></div>
    </div>
    <div class="directory-grid directory-grid-rest">
      <div class="directory-item"><div class="directory-name">JOCELYN HOH SHU LIN</div><div class="directory-role">Y/M FARMASI PESAKIT DALAM</div><div class="directory-ext">EXT: 1320</div></div>
      <div class="directory-item"><div class="directory-name">MOHAMAD SYAFIQ BIN SALLEH</div><div class="directory-role">Y/M FARMASI PESAKIT LUAR</div><div class="directory-ext">EXT: 3060</div></div>
      <div class="directory-item"><div class="directory-name">LIM SAY HOON</div><div class="directory-role">Y/M FARMASI MAKLUMAT UBAT</div><div class="directory-ext">EXT: 3060</div></div>
      <div class="directory-item"><div class="directory-name">LIM JYE YI ?</div><div class="directory-role">Y/M TDM</div><div class="directory-ext">EXT: 1324</div></div>
      <div class="directory-item"><div class="directory-name">NURUL DIYANAH BINTI MOHD ZAKI</div><div class="directory-role">Y/M FARMASI LOGISTIK</div><div class="directory-ext">EXT: 1211</div></div>
      <div class="directory-item"><div class="directory-name">OI AUN CHYI</div><div class="directory-role">Y/M FARMASI KLINIKAL</div><div class="directory-ext">EXT: 1320</div></div>
      <div class="directory-item"><div class="directory-name">NOORHIDAYAH BINTI RAMLI</div><div class="directory-role">Y/M FARMASI SATELIT</div><div class="directory-ext">EXT: 3183</div></div>
    </div>
    <div class="directory-link">
      <a href="https://sites.google.com/view/farmasihbalinghub/direktori-jabatan-farmasi" target="_blank" rel="noopener noreferrer">CLICK HERE TO ACCESS OTHER EXT NO</a>
    </div>
  </div>
@elseif($isFarmasiMedErrorsSlide)
  <div class="med-errors-card" id="slideImage">
    <div class="med-errors-title">MEDICATION ERRORS IN HOSPITALS</div>
    <div class="med-errors-section">
      <div class="med-errors-section-title">Example of Actual Errors Happened in Malaysia in 2024</div>
      <div class="med-errors-links">
        <a class="med-errors-link" href="{{ route('staff.unit.view', ['unitSlug' => $unitSlug, 'slide' => 10]) }}">PRESCRIBING &amp; ADMINISTRATION ERROR</a>
        <a class="med-errors-link" href="{{ route('staff.unit.view', ['unitSlug' => $unitSlug, 'slide' => 11]) }}">ADMINISTRATION ERROR</a>
        <a class="med-errors-link" href="{{ route('staff.unit.view', ['unitSlug' => $unitSlug, 'slide' => 12]) }}">ADMINISTRATION ERROR</a>
      </div>
    </div>
    <div class="med-errors-section">
      <div class="med-errors-section-title">Example of Actual Errors Happened in Hospital Baling</div>
      <div class="med-errors-links">
        <a class="med-errors-link" href="{{ route('staff.unit.view', ['unitSlug' => $unitSlug, 'slide' => 14]) }}">2024</a>
        <a class="med-errors-link" href="{{ route('staff.unit.view', ['unitSlug' => $unitSlug, 'slide' => 15]) }}">2025</a>
      </div>
    </div>
  </div>
@elseif($isFarmasiMersSlide)
  <div class="mers-card" id="slideImage">
    <div class="mers-text">
      <div class="mers-title">MEDICATION ERROR REPORTING SYSTEM<span>(MERS)</span></div>
      <a class="mers-link" href="https://sites.google.com/moh.gov.my/mers-bapfkkm/home" target="_blank" rel="noopener noreferrer">CLICK HERE TO ACCESS</a>
      <div class="mers-procedure"><a href="{{ route('staff.unit.view', ['unitSlug' => $unitSlug, 'slide' => 24]) }}">MERS REPORTING PROCEDURE</a></div>
    </div>
    <img src="{{ asset('assets/slide_images/farmasi/MOH.png') }}" alt="MOH Data Dashboard" class="mers-image">
  </div>
@elseif($isFarmasiMeFormSlide)
  <div class="me-form-card" id="slideImage">
    <div class="me-form-title">MEDICATION ERROR REPORTING FORM</div>
    <a class="me-form-link" href="https://drive.google.com/file/d/1mHQKHAx22DRGWSFbOyTrPytez_0MYWFC/view?authuser=0&amp;usp=sharing" target="_blank" rel="noopener noreferrer">CLICK HERE TO ACCESS</a>
    <div class="me-form-images">
      <img src="{{ asset('assets/slide_images/farmasi/ME.png') }}" alt="Medication Error Reporting Form">
      <img src="{{ asset('assets/slide_images/farmasi/ME2.png') }}" alt="Medication Error Reporting Form page 2">
    </div>
  </div>
@elseif($isFarmasiMersInfoSlide)
  <div class="mers-info-card" id="slideImage">
    <div class="mers-info-title">Medication Error Reporting<br><span>System (MERS)</span></div>
    <div class="mers-star" aria-hidden="true"><div class="mers-star-text">MERS</div></div>
    <div class="mers-info-list">
      <div class="mers-info-item"><div class="mers-info-item-title">1) WHO can report?</div><div class="mers-info-item-body">All healthcare providers, both in public and private sector.</div></div>
      <div class="mers-info-item"><div class="mers-info-item-title">2) WHAT to report?</div><div class="mers-info-item-body">✅ All medication error (actual error and near misses). <br> ❌ Administrative error.</div></div>
      <div class="mers-info-item"><div class="mers-info-item-title">3) WHEN to report?</div><div class="mers-info-item-body">Anytime when detected an error.</div></div>
      <div class="mers-info-item"><div class="mers-info-item-title">4) WHERE to report?</div><div class="mers-info-item-body">Report to MERS Online: <a href="https://mers.pharmacy.gov.my" target="_blank" rel="noopener noreferrer">https://mers.pharmacy.gov.my</a></div></div>
      <div class="mers-info-item"><div class="mers-info-item-title">5) WHY report?</div><div class="mers-info-item-body">👉 To learn and share experience on ME. <br> 👉 To disseminate info on ME. <br> 👉 To formulate risk reduction strategies.<br>👉 To improve PATIENT SAFETY.</div></div>
      <div class="mers-info-item"><div class="mers-info-item-title">6) HOW to report?</div><div class="mers-info-item-body"><a href="https://pharmacy.moh.gov.my/sites/default/files/document-upload/mers-2025-google-site-reporting-procedure-v3.pdf" target="_blank" rel="noopener noreferrer">Google Site Reporting Guideline: Medication Error Reporting System (MERS) 2025</a></div></div>
    </div>
  </div>
@elseif($isFarmasiHubSlide)
  <div class="hub-card" id="slideImage">
    <div class="hub-title">FARMASI HBALING HUB</div>
    <img src="{{ asset('assets/slide_images/farmasi/hub.png') }}" alt="Farmasi HBaling Hub" class="hub-image">
    <div class="hub-link"><a href="https://sites.google.com/view/farmasihbalinghub/laman-utama" target="_blank" rel="noopener noreferrer">CLICK HERE TO ACCESS 👉</a></div>
    <div class="hub-section"><div class="hub-section-title">DESCRIPTION</div><div class="hub-section-body">A one-stop hub for Hospital Baling pharmacy resources, forms, and guidelines.</div></div>
    <div class="hub-section"><div class="hub-section-title">TUTORIAL</div><div class="hub-section-body"><a href="https://drive.google.com/file/d/1gJsyY-lhXPz88h2lIdVBzEac6RvT1OyN/view?usp=sharing" target="_blank" rel="noopener noreferrer">Open Farmasi HBaling Hub using Intranet HBaling</a><br><a href="https://drive.google.com/file/d/1i2ncadgHpe7zq3Oc_VoQDpnyNz9EyUsp/view?usp=sharing" target="_blank" rel="noopener noreferrer">Open Farmasi HBaling Hub using Google Chrome Search</a></div></div>
    <div class="hub-section" style="grid-column: 1 / -1;">
      <div class="hub-section-title">WHAT YOU CAN ACCESS?</div>
      <table class="hub-table">
        <tr><th>FORMULARI HBALING</th><th>BORANG</th><th>DIREKTORI JABATAN FARMASI</th><th>MEDICATION SAFETY</th><th>PERMOHONAN PEMBIAYAAN UBAT</th><th>RUJUKAN</th><th>BULETIN</th></tr>
        <tr>
          <td>&nbsp;</td>
          <td>Pelbagai borang berkaitan farmasi boleh diakses contohnya Borang Permohonan Kad Alahan Ubat</td>
          <td>Boleh mengakses Direktori Jabatan Farmasi dan senarai Extension No Pharmacy</td>
          <td>👉 Updates on High Alert Medications &amp; Look Alike Sound Alike Medications annually<br>👉 Pertukaran Jenama Ubat</td>
          <td>👉 Tatacara Permohonan Ubat bagi Pesara <br>👉 Tatacara Permohonan Ubat bagi Penjawat Awam Tatacara Permohonan Ubat bagi Penjawat Awam</td>
          <td>Pelbagai Guideline boleh diakses contohnya Dilution Protocol HBaling dan NAG 2024</td>
          <td>Boleh mengakses Buletin Farmasi</td>
        </tr>
      </table>
    </div>
  </div>
@elseif($isFarmasiFormulariSlide)
  <div class="formulari-card" id="slideImage">
    <div class="formulari-title">FORMULARI HBALING</div>
    <div class="formulari-link">👉<a href="https://www.appsheet.com/start/58bd097f-1871-41d8-93ac-be9a2d9ac233" target="_blank" rel="noopener noreferrer">CLICK HERE TO ACCESS</a></div>
    <div class="formulari-section"><div class="formulari-section-title">TUTORIAL</div><div class="formulari-section-body"><a href="https://drive.google.com/file/d/1I29buaBRGojEsOSshhbBP3mrzpSRwkXU/view?usp=sharing" target="_blank" rel="noopener noreferrer">Open Formulari HBaling</a></div></div>
    <div class="formulari-images">
      <img src="{{ asset('assets/slide_images/farmasi/formulari.png') }}" alt="Formulari HBaling">
      <img src="{{ asset('assets/slide_images/farmasi/formulari2.png') }}" alt="Formulari HBaling screen 2">
    </div>
  </div>
@elseif($slideImageUrl)
  <img src="{{ $slideImageUrl }}" alt="Slide {{ $currentSlide }}" class="slide-image" id="slideImage" onerror="this.style.display='none'">
@else
  <div style="padding:40px;text-align:center;color:#666;">Slide tidak dijumpai.</div>
@endif
