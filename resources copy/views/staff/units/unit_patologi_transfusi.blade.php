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
    <div class="completion-status" id="progressStatus"></div>
    <div class="completion-message">Tahniah! Anda telah berjaya lengkap {{ $unitTitle }}.</div>
  </div>
@elseif($isPatologiCustomSlide)
  @if($currentSlide === 4)
    <section class="patologi-slide4" id="slideImage" aria-labelledby="urgent-requests-title">
      <div class="patologi-slide4-block">
        <h2 class="patologi-slide4-header one" id="urgent-requests-title">UJIAN SEGERA (URGENT REQUESTS)</h2>
        <div class="patologi-slide4-text">👉Peripheral Blood Film, Respiratory Virus, Hb analysis - Pakar Patologi, Dr Ahmad Zulhimi Bin Ismail
👉Semua permohonan GXM atau komponen darah untuk transfusi - Pegawai Perubatan.</div>
      </div>

      <div class="patologi-slide4-block">
        <h3 class="patologi-slide4-header two">PERMOHONAN UJIAN &amp; KEPUTUSAN UJIAN</h3>
        <div class="patologi-slide4-text">👉Lab Information System (LIS) Hosp. Baling: <a href="http://10.159.102.72/iReportAG/home/Index" target="_blank" rel="noopener noreferrer">http://10.159.102.72/iReportAG/home/Index</a>
👉Lab Information System (LIS) Hosp. Kulim: <a href="http://10.159.96.131/ireportag" target="_blank" rel="noopener noreferrer">http://10.159.96.131/ireportag</a>. Boleh digunakan untuk mengesan keputusan ujian yang dihantar ke Hospital Kulim.</div>
      </div>

      <div class="patologi-slide4-block">
        <h3 class="patologi-slide4-header three">PANDUAN PENAWARAN &amp; INFORMASI UJIAN</h3>
        <div class="patologi-slide4-text">👉<a href="https://hbltest.site/" target="_blank" rel="noopener noreferrer">https://hbltest.site/</a>
👉Semua borang, turn-around-time (TAT) dan maklumat lain boleh diakses melalui pautan ini.</div>
      </div>
    </section>
  @else
    <section class="slide-reminder" id="slideImage" aria-labelledby="reminder-title">
      <h2 class="slide-reminder-title" id="reminder-title">⚠️ Peringatan</h2>
      <p class="slide-reminder-text">Sila berjumpa Penyelia Unit Patologi dan Transfusi untuk melengkapkan aktiviti bersemuka dan Sahkan status Orientasi Anda.</p>
    </section>
  @endif
@elseif($isPatologiQuizSlide)
  <div class="quiz-card" id="quizCard">
    <div class="quiz-title">KUIZ: UNIT PATOLOGI &amp; TRANSFUSI</div>
    <div class="quiz-question">1. PBF urgent must call:</div>
    <div class="quiz-options">
      <label class="quiz-option"><input type="radio" name="quiz_q1" value="A">A. Dr Zulhimi (Pathologist)</label>
      <label class="quiz-option"><input type="radio" name="quiz_q1" value="B">B. Medical officer on-call</label>
    </div>
    <div class="quiz-question">2. GXM must call:</div>
    <div class="quiz-options">
      <label class="quiz-option"><input type="radio" name="quiz_q2" value="A">A. Dr Zulhimi (Pathologist)</label>
      <label class="quiz-option"><input type="radio" name="quiz_q2" value="B">B. Medical officer on-call</label>
    </div>
    <div class="quiz-question">3. To trace FBC, must call:</div>
    <div class="quiz-options">
      <label class="quiz-option"><input type="radio" name="quiz_q3" value="A">A. Medical officer on-call</label>
      <label class="quiz-option"><input type="radio" name="quiz_q3" value="B">B. Hematology counter (ext: 3155)</label>
    </div>
    <div class="quiz-question">4. To trace HPE, must call:</div>
    <div class="quiz-options">
      <label class="quiz-option"><input type="radio" name="quiz_q4" value="A">A. Dr Zulhimi (Pathologist)</label>
      <label class="quiz-option"><input type="radio" name="quiz_q4" value="B">B. Histopathologist in Sultanah Bahiyah</label>
    </div>
    <div class="quiz-actions">
      <button type="button" class="quiz-btn" id="quizSubmit">Semak</button>
      <button type="button" class="quiz-btn" id="quizReset">Buat Semula</button>
    </div>
    <div class="quiz-feedback" id="quizFeedback"></div>
  </div>
@elseif($slideImageUrl)
  @if($isPatologiLinkSlide)
    <div class="slide-link-title">
      <a href="https://hbltest.site/" target="_blank" rel="noopener noreferrer">https://hbltest.site/</a>
    </div>
  @endif
  <img src="{{ $slideImageUrl }}" alt="Slide {{ $currentSlide }}" class="slide-image" id="slideImage" onerror="this.style.display='none'">
@else
  <div style="padding:40px;text-align:center;color:#666;">Slide tidak dijumpai.</div>
@endif
