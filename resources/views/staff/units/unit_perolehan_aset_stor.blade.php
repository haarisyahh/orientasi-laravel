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
@elseif($isPerolehanQuizSlide)
  <div class="stor-fullscreen" id="quizCard">
    <div class="quiz-title">2.2 KUIZ: PEROLEHAN DAN ASET</div>
    <div class="quiz-question">1. PILIH YANG MANA BETUL BAGI DUA KATEGORI ASET ALIH KERAJAAN.</div>
    <div class="quiz-options">
      <label class="quiz-option"><input type="radio" name="quiz_q1" value="A">A. Aset Bernilai Rendah &amp; Harta Modal</label>
      <label class="quiz-option"><input type="radio" name="quiz_q1" value="B">B. Aset Bernilai Rendah &amp; Aset Bernilai Tinggi</label>
    </div>
    <div class="quiz-question">2. ANTARA BERIKUT, YANG MANAKAH KAEDAH PEROLEHAN?</div>
    <div class="quiz-options">
      <label class="quiz-option"><input type="radio" name="quiz_q2" value="A">A. Pembelian terus</label>
      <label class="quiz-option"><input type="radio" name="quiz_q2" value="B">B. Sebut harga</label>
      <label class="quiz-option"><input type="radio" name="quiz_q2" value="C">C. Tender</label>
      <label class="quiz-option"><input type="radio" name="quiz_q2" value="D">D. Semua di atas</label>
    </div>
    <div class="quiz-actions">
      <button type="button" class="quiz-btn" id="quizSubmit">Semak</button>
      <button type="button" class="quiz-btn" id="quizReset">Buat Semula</button>
    </div>
    <div class="quiz-feedback" id="quizFeedback"></div>
  </div>
@elseif($isPerolehanInfoSlide)
  <div class="stor-fullscreen" id="slideImage">
    <div>
      <div class="custom-shape-header">1.3 PEROLEHAN</div>
      <div class="custom-shape" style="margin-bottom: 12px;">
        <div class="custom-shape-title">RUJUKAN DAN PEKELILING BERKAITAN PEROLEHAN</div>
        <div class="custom-shape-text">
          AKSES KE
          <a href="https://ppp.treasury.gov.my/" target="_blank" rel="noopener noreferrer">PORTAL PEKELILING PERBENDAHARAAN</a><br>
          BIDANG - PEROLEHAN KERAJAAN (PK)<br>
          CONTOH PK YANG BERKAITAN:
          <ul class="custom-bullets">
            <li>PK 2.9 SEBUT HARGA</li>
            <li>PK 2.10 PEMBELIAN TERUS (BEKALAN DAN PERKHIDMATAN)</li>
            <li>PK 5.1 PEROLEHAN MENGGUNAKAN SISTEM EPEROLEHAN</li>
          </ul>
        </div>
      </div>
      <div class="custom-shape">
        <div class="custom-shape-text">
          BORANG-BORANG BERKAITAN PEROLEHAN BOLEH DIAKSES DI PORTAL
          <a href="http://10.159.102.74/intra/pengurusan.html" target="_blank" rel="noopener noreferrer">INTRANET HOSPITAL BALING</a><br><br>
          SISTEM YANG DIGUNAKAN:
          <a href="https://www.eperolehan.gov.my/" target="_blank" rel="noopener noreferrer">ePEROLEHAN</a>
        </div>
        <div class="custom-image-wrap">
          <img src="{{ asset('assets/unit_perolehan_aset_stor/perolehan.png') }}" alt="Perolehan" class="custom-image" onerror="this.style.display='none'">
        </div>
      </div>
    </div>
  </div>
@elseif($isPerolehanAssetSlide)
  <div class="stor-fullscreen" id="slideImage">
    <div>
      <div class="custom-shape-header">1.4 PENGURUSAN ASET ALIH KERAJAAN</div>
      <div class="custom-shape" style="margin-bottom: 12px;">
        <div class="custom-shape-title">RUJUKAN DAN PEKELILING BERKAITAN PENGURUSAN ASET ALIH KERAJAAN:</div>
        <div class="custom-shape-text">
          👉AKSES KE
          <a href="https://ppp.treasury.gov.my/" target="_blank" rel="noopener noreferrer">PORTAL PEKELILING PERBENDAHARAAN</a><br><br>
          👉BIDANG - PENGURUSAN ASET (AM)<br><br>
          👉ANTARA AM YANG BERKAITAN:<br><br>
          AM 2 TATACARA PENGURUSAN ASET ALIH KERAJAAN
        </div>
      </div>
      <div class="custom-shape">
        <div class="custom-shape-text">
          👉BORANG-BORANG BERKAITAN PENGURUSAN ASET ALIH KERAJAAN BOLEH DIAKSES DI PORTAL
          <a href="http://10.159.102.74/intra/pengurusan.html" target="_blank" rel="noopener noreferrer">INTRANET HOSPITAL BALING</a><br><br>
          👉BORANG BERKAITAN ASET ALIH: KEW.PA-1 ~ KEW.PA-37<br><br>
          👉SISTEM YANG DIGUNAKAN:
          <a href="https://sppakkm3.treasury.gov.my/portalSpa/login.cfm" target="_blank" rel="noopener noreferrer">SISTEM PEMANTAUAN PENGURUSAN ASET KERAJAAN MALAYSIA (SPPA)</a>
        </div>
        <div class="custom-image-wrap">
          <img src="{{ asset('assets/unit_perolehan_aset_stor/pemantauan.png') }}" alt="Pemantauan Aset" class="custom-image" onerror="this.style.display='none'">
        </div>
      </div>
    </div>
  </div>
@elseif($isPerolehanStorSlide)
  <div class="stor-fullscreen" id="slideImage">
    <div class="stor-title">2.1 PENGURUSAN STOR</div>

    <div class="stor-section">
      <div class="stor-header blue">STOR</div>
      <div class="stor-section-text">Tempat bagi melaksanakan
penerimaan, merekod, penyimpanan,
penyelenggaraan,  pengendalian dan pengeluaran stok.</div>
    </div>

    <div class="stor-section">
      <div class="stor-header green">STOK:</div>
      <div class="stor-section-text">Barang-barang belum guna dan perlu disimpan atau untuk digunakan terus bagi tujuan operasi atau penyelenggaraan.

        contoh:  bekalan pejabat, ubat-ubatan, alat ganti, keselamatan, makmal, bengkel, dapur dll
      </div>
    </div>

    <div class="stor-section">
      <div class="stor-header orange">RUJUKAN DAN PEKELILING BERKAITAN PENGURUSAN STOR KERAJAAN:</div>
      <div class="stor-section-text">
        AKSES KE <a href="https://ppp.treasury.gov.my/" target="_blank" rel="noopener noreferrer">PORTAL PEKELILING PERBENDAHARAAN</a><br>
BIDANG - PENGURUSAN ASET (AM)
ANTARA AM YANG BERKAITAN:
AM. 6 TATACARA PENGURUSAN STOR KERAJAAN</div>
    </div>

    <div class="stor-section">
      <div class="stor-header purple">4 STOR UTAMA:</div>
      <div class="stor-section-list">
        i. Farmasi  - Jabatan Farmasi <br>
        ii. Sajian - Unit Sajian &amp; Dietetik <br>
        iii. Alat Tulis - Unit Stor, Bah. Pengurusan <br>
        iv. Makmal - Jabatan Patologi &amp; Transfusi
      </div>
    </div>
  </div>
@elseif($slideImageUrl)
  <img src="{{ $slideImageUrl }}" alt="Slide {{ $currentSlide }}" class="slide-image" id="slideImage" onerror="this.style.display='none'">
@else
  <div style="padding:40px;text-align:center;color:#666;">Slide tidak dijumpai.</div>
@endif
