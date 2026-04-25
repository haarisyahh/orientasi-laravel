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
@elseif($isSumberManusiaVideoSlide)
  <div class="video-title">{{ $sumberManusiaVideoTitle }}</div>
  <div class="video-wrapper" id="slideImage">
    <video class="video-frame" controls playsinline controlsList="noplaybackrate noremoteplayback">
      <source src="{{ asset($sumberManusiaVideoFile) }}" type="video/mp4">
      Browser anda tidak menyokong video.
    </video>
  </div>
@elseif($slideImageUrl)
  <img src="{{ $slideImageUrl }}" alt="Slide {{ $currentSlide }}" class="slide-image" id="slideImage" onerror="this.style.display='none'">
@else
  <div style="padding:40px;text-align:center;color:#666;">Slide tidak dijumpai.</div>
@endif
