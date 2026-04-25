<!doctype html>
<html lang="ms">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $pageTitle }}</title>
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}?v=2">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Poppins', Arial, sans-serif; background: #f5f5f5; display: flex; min-height: 100vh; margin: 0; padding: 0; }
    .bg-image { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: #ffffff; z-index: -1; pointer-events: none; }
    .bg-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.7); z-index: -1; pointer-events: none; }
    .unit-sidebar { width: 260px; background: #f5dfe0; padding: 15px; display: flex; flex-direction: column; height: 100vh; box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1); overflow-y: auto; flex-shrink: 0; transition: margin-left 0.25s ease, box-shadow 0.25s ease; }
    body.sidebar-collapsed .unit-sidebar { margin-left: -260px; box-shadow: none; }
    .header-sidebar-toggle { border: none; outline: none; background: #fff; color: #222; width: 34px; height: 34px; border-radius: 8px; font-size: 16px; font-weight: 700; line-height: 1; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; transition: 0.2s ease; }
    .header-sidebar-toggle:hover { background: #f0d6d7; }
    .unit-logo { text-align: center; margin-bottom: 15px; display: flex; flex-direction: column; align-items: center; gap: 6px; }
    .unit-logo-badge { width: 50px; height: 50px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); flex-shrink: 0; }
    .unit-logo-badge img { width: 45px; height: 45px; border-radius: 50%; object-fit: contain; }
    .unit-logo-text { font-size: 10px; font-weight: 700; color: #222; text-align: center; line-height: 1.2; }
    .unit-menu { list-style: none; display: flex; flex-direction: column; gap: 5px; flex: 1; overflow-y: auto; padding-right: 3px; }
    .unit-menu li { padding: 0; }
    .unit-menu li a { display: block; padding: 8px 10px; color: #222; text-decoration: none; font-weight: 600; font-size: 10px; border-radius: 6px; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 0.2px; border: 2px solid transparent; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1; }
    .unit-menu-row { display: flex; align-items: center; gap: 6px; }
    .menu-toggle { border: 2px solid #000; background: #f0d6d7; width: 22px; height: 22px; border-radius: 5px; font-weight: 800; font-size: 12px; line-height: 1; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .menu-toggle:hover { background: #f0d6d7; }
    .menu-progress { margin-top: 8px; padding: 8px; background: #f0d6d7; border: 1px solid #e8c5c7; border-radius: 8px; display: none; align-items: center; justify-content: center; }
    .menu-progress.active { display: flex; }
    .unit-menu li a:hover { background: #f0d6d7; color: #000; }
    .unit-menu li a.active { background: #fff; border: 2px solid #e8c5c7; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); color: #1a1a1a; }
    .unit-content { flex: 1; display: flex; flex-direction: column; height: 100vh; overflow: hidden; }
    .unit-header { background: rgba(255, 255, 255, 0.95); padding: 12px 20px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); flex-shrink: 0; }
    .unit-header-row { display: flex; align-items: center; gap: 10px; }
    .unit-title-header { font-size: 18px; font-weight: 800; color: #1a1a1a; letter-spacing: 0.5px; margin: 0; }
    .menu-progress .progress-circle { width: 48px; height: 48px; }
    .unit-main { flex: 1; padding: 15px; background: transparent; display: flex; flex-direction: column; align-items: center; justify-content: flex-start; overflow-y: auto; }
    .slide-container { width: 100%; max-width: none; background: #fff; border-radius: 8px; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1); padding: 15px; display: flex; flex-direction: column; gap: 15px; position: relative; }
    .slide-image { width: 100%; height: auto; border-radius: 6px; object-fit: contain; }
    .video-wrapper { position: relative; }
    .video-frame { width: 100%; border-radius: 6px; background: #000; }
    .video-download { position: absolute; right: 12px; bottom: 12px; border: none; background: transparent; padding: 0; border-radius: 0; font-weight: 700; font-size: 11px; color: #222; text-decoration: none; box-shadow: none; }
    .video-download:hover { text-decoration: underline; }
    .video-title { font-size: 14px; font-weight: 700; color: #1a1a1a; text-align: center; }
    .hero { width: 100%; min-height: 65vh; background: url('{{ asset('assets/background.jpg') }}') center/cover; border-radius: 8px; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1); }
    .slide-controls { margin-top: 6px; }
    .slide-info { font-size: 12px; color: #666; font-weight: 600; text-align: center; }
    .slide-nav {
      position: fixed;
      top: 50%;
      left: var(--slide-nav-left, 280px);
      right: var(--slide-nav-right, 12px);
      transform: translateY(-50%);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0;
      pointer-events: none;
      z-index: 5;
    }
    .nav-btn { border: 2px solid #6cbf51; background: #fff; padding: 10px; border-radius: 6px; font-weight: 700; font-size: 11px; cursor: pointer; transition: 0.2s ease; text-decoration: none; display: flex; align-items: center; justify-content: center; width: 58px; height: 58px; min-width: 58px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15); }
    .slide-nav .nav-btn { pointer-events: auto; }
    .nav-btn svg { width: 32px; height: 32px; display: block; color: #6cbf51; }
    .nav-btn:hover { background: #f6fff2; transform: scale(1.08); }
    .nav-btn:disabled { opacity: 0.5; cursor: not-allowed; }
    .progress-circle { width: 55px; height: 55px; margin: 0 auto; position: relative; display: flex; align-items: center; justify-content: center; }
    .progress-circle svg { width: 100%; height: 100%; transform: rotate(-90deg); }
    .progress-circle-track { fill: none; stroke: #e0e0e0; stroke-width: 12; }
    .progress-circle-fill { fill: none; stroke: #f44336; stroke-width: 12; stroke-linecap: round; transition: stroke-dashoffset 0.4s ease, stroke 0.4s ease; }
    .progress-circle-fill.low { stroke: #e53935; }
    .progress-circle-fill.medium { stroke: #FFA000; }
    .progress-circle-fill.high { stroke: #45a049; }
    .progress-circle-text { position: absolute; font-size: 12px; font-weight: 700; color: #2a2a2a; text-shadow: 0 1px 0 rgba(255, 255, 255, 0.8); }
    .completion-card { border: 2px solid #f0d6d7; border-radius: 10px; padding: 18px; background: #fff7f7; display: flex; flex-direction: column; align-items: center; gap: 12px; text-align: center; }
    .completion-title { font-size: 14px; font-weight: 700; color: #1a1a1a; }
    .completion-message { font-size: 13px; font-weight: 700; color: #1a1a1a; }
    .quiz-card { border: 1px solid #e3e3e3; background: #fafafa; border-radius: 8px; padding: 12px; display: flex; flex-direction: column; gap: 10px; }
    .quiz-title { font-size: 12px; font-weight: 700; color: #222; text-align: center; }
    .quiz-question { font-size: 11px; font-weight: 600; color: #333; margin-top: 4px; }
    .quiz-options { display: flex; flex-direction: column; gap: 6px; }
    .quiz-option { display: flex; align-items: flex-start; gap: 6px; font-size: 11px; color: #333; }
    .quiz-actions { display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 4px; }
    .quiz-btn { border: 2px solid #222; background: #fff; padding: 6px 10px; border-radius: 6px; font-weight: 700; font-size: 10px; cursor: pointer; transition: 0.2s ease; }
    .quiz-btn:hover { background: #f0d6d7; }
    .quiz-feedback { text-align: center; font-size: 10px; font-weight: 700; min-height: 14px; }
    .quiz-feedback.error { color: #e53935; }
    .quiz-feedback.success { color: #2e7d32; }
    .slide-custom { background: #fff; border-radius: 6px; padding: 20px 24px; box-shadow: inset 0 0 0 1px #eee; }
    .slide-custom-title { font-size: 18px; font-weight: 800; color: #1d6fd6; text-align: center; letter-spacing: 0.4px; margin-bottom: 14px; }
    .slide-link-title { font-size: 16px; font-weight: 800; color: #1d6fd6; text-align: center; margin-bottom: 10px; }
    .slide-link-title a { color: inherit; text-decoration: underline; }
    .slide-custom-section-title { font-size: 14px; font-weight: 800; color: #1d6fd6; text-align: center; margin: 18px 0 10px; }
    .slide-custom-list { list-style: disc; padding-left: 22px; display: flex; flex-direction: column; gap: 8px; }
    .slide-custom-list li { font-size: 13px; color: #222; line-height: 1.5; }
    .slide-custom-list .emphasis { font-weight: 700; color: #6b2ca0; }
    .slide-custom-link { color: #1d6fd6; font-weight: 700; text-decoration: underline; }
    .slide-custom-note { font-size: 13px; color: #222; line-height: 1.5; }
    .patologi-slide4 { background: #d9ecff; border-radius: 10px; padding: 18px; display: flex; flex-direction: column; gap: 14px; }
    .patologi-slide4-block { background: #fff; border: 2px solid #9fc2ea; border-radius: 10px; padding: 14px; }
    .patologi-slide4-header { color: #fff; font-size: 14px; font-weight: 800; padding: 10px 12px; border-radius: 8px; margin-bottom: 10px; }
    .patologi-slide4-header.one { background: #2c6fb6; }
    .patologi-slide4-header.two { background: #6a4fb3; }
    .patologi-slide4-header.three { background: #228b7b; }
    .patologi-slide4-text { font-size: 13px; color: #222; line-height: 1.6; white-space: pre-line; }
    .patologi-slide4-text a { color: #1d6fd6; font-weight: 700; text-decoration: underline; }
    .slide-reminder { padding: 26px 28px; background: #fff7f3; border-radius: 10px; border: 2px solid #f2b9a5; text-align: center; }
    .slide-reminder-title { font-size: 20px; font-weight: 800; color: #d64b1d; margin-bottom: 12px; }
    .slide-reminder-text { font-size: 14px; font-weight: 600; color: #333; line-height: 1.6; }
    .completion-status { font-size: 11px; font-weight: 700; color: #666; }
    .custom-info-layout { display: grid; grid-template-columns: 1fr 300px; gap: 18px; align-items: center; }
    .custom-shape { background: #bbc8dd; border: 2px solid #f0d6d7; border-radius: 12px; padding: 20px 22px; width: 100%; overflow: hidden; }
    .custom-shape-header { font-size: 18px; font-weight: 800; color: #1a1a1a; margin-bottom: 12px; text-align: center; }
    .custom-shape-title { font-size: 16px; font-weight: 800; color: #1a1a1a; margin-bottom: 8px; text-transform: uppercase; }
    .custom-shape-text { font-size: 14px; color: #333; line-height: 1.55; overflow-wrap: anywhere; word-break: break-word; }
    .custom-shape-text a { color: #0b57d0; text-decoration: underline; font-weight: 700; overflow-wrap: anywhere; word-break: break-word; }
    .custom-bullets { margin: 8px 0 0 14px; padding: 0; }
    .custom-bullets li { margin: 2px 0; }
    .custom-image-wrap { display: flex; justify-content: center; }
    .custom-image { width: 100%; max-width: fit-content; height: auto; border-radius: 12px; border: 2px solid #e8c5c7; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); background: #fff; }
    .directory-card { border: 2px solid #f0d6d7; border-radius: 10px; padding: 16px; background: #fff7f7; }
    .directory-title { text-align: center; font-size: 18px; font-weight: 800; letter-spacing: 0.5px; margin-bottom: 12px; color: #1a1a1a; }
    .directory-lead-wrap { display: flex; justify-content: center; margin-bottom: 12px; }
    .directory-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 10px; }
    .directory-item { background: #fff; border: 1px solid #f0d6d7; border-radius: 8px; padding: 10px 12px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06); }
    .directory-item-lead { background: #ffe1a8; border: 2px solid #f2b84b; max-width: 460px; width: 100%; text-align: center; }
    .directory-grid-rest .directory-item { background: #e8f2ff; border: 1px solid #b9d3f4; }
    .directory-name { font-weight: 700; font-size: 12px; color: #1a1a1a; margin-bottom: 4px; }
    .directory-role { font-size: 11px; color: #444; font-weight: 600; margin-bottom: 4px; }
    .directory-ext { font-size: 11px; color: #222; font-weight: 700; }
    .directory-link { margin-top: 12px; text-align: center; }
    .directory-link a { display: inline-block; padding: 8px 12px; border: 2px solid #222; border-radius: 6px; text-decoration: none; color: #222; font-weight: 700; font-size: 11px; transition: 0.2s ease; background: #fff; }
    .directory-link a:hover { background: #f0d6d7; }
    .med-errors-card { border: 2px solid #f0d6d7; border-radius: 10px; padding: 16px; background: #fff7f7; }
    .med-errors-title { text-align: center; font-size: 18px; font-weight: 800; letter-spacing: 0.5px; margin-bottom: 6px; color: #1a1a1a; }
    .med-errors-section { background: #fff; border: 1px solid #f0d6d7; border-radius: 8px; padding: 10px 12px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06); }
    .med-errors-section + .med-errors-section { margin-top: 10px; }
    .med-errors-section-title { font-size: 12px; font-weight: 700; color: #1a1a1a; margin-bottom: 8px; }
    .med-errors-links { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 8px; }
    .med-errors-link { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 8px 10px; border: 2px solid #222; border-radius: 6px; text-decoration: none; color: #222; font-weight: 700; font-size: 11px; background: #fff; transition: 0.2s ease; }
    .med-errors-link:hover { background: #f0d6d7; }
    .mers-card { border: 2px solid #f0d6d7; border-radius: 10px; padding: 16px; background: #fff7f7; display: grid; grid-template-columns: minmax(0, 1.1fr) minmax(0, 0.9fr); gap: 12px; align-items: center; }
    .mers-text { display: flex; flex-direction: column; gap: 10px; }
    .mers-title { font-size: 18px; font-weight: 800; letter-spacing: 0.4px; color: #0b3a77; }
    .mers-title span { display: block; font-size: 16px; font-weight: 800; color: #0b3a77; }
    .mers-link { display: inline-block; padding: 8px 12px; border: 2px solid #222; border-radius: 6px; text-decoration: none; color: #222; font-weight: 700; font-size: 11px; background: #fff; transition: 0.2s ease; width: fit-content; }
    .mers-link:hover { background: #f0d6d7; }
    .mers-procedure { font-size: 12px; font-weight: 700; color: #0b3a77; }
    .mers-procedure a { color: #0b3a77; text-decoration: underline; font-weight: 800; }
    .mers-image { width: 100%; max-height: 320px; object-fit: contain; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12); background: #fff; padding: 6px; }
    .me-form-card { border: 2px solid #f0d6d7; border-radius: 10px; padding: 16px; background: #fff7f7; display: flex; flex-direction: column; gap: 12px; align-items: center; }
    .me-form-title { font-size: 18px; font-weight: 800; letter-spacing: 0.4px; color: #0b3a77; text-align: center; }
    .me-form-images { width: 100%; display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 10px; }
    .me-form-images img { width: 100%; height: auto; object-fit: contain; border-radius: 8px; background: #fff; padding: 6px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12); }
    .me-form-link { color: #0b3a77; font-weight: 800; text-decoration: none; border-bottom: 2px solid #0b3a77; padding-bottom: 2px; font-size: 12px; }
    .me-form-link:hover { color: #072b58; border-bottom-color: #072b58; }
    .mers-info-card { border: 2px solid #f0d6d7; border-radius: 10px; padding: 16px; background: #fff7f7; display: flex; flex-direction: column; gap: 12px; }
    .mers-info-title { text-align: center; font-size: 18px; font-weight: 800; color: #1a1a1a; line-height: 1.2; }
    .mers-info-title span { font-weight: 800; }
    .mers-star { width: 155px; height: 155px; margin: 0 auto; background: #0b3a77; clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%); display: flex; align-items: center; justify-content: center; }
    .mers-star-text { color: #fff; font-weight: 700; font-size: 14px; letter-spacing: 0.5px; }
    .mers-info-list { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 10px; }
    .mers-info-item { background: #fff; border: 1px solid #f0d6d7; border-radius: 8px; padding: 10px 12px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06); }
    .mers-info-item-title { font-size: 12px; font-weight: 800; color: #1a1a1a; margin-bottom: 6px; }
    .mers-info-item-body { font-size: 11px; color: #333; line-height: 1.4; }
    .mers-info-item-body a { color: #0b3a77; font-weight: 700; text-decoration: underline; }
    .hub-card { border: 2px solid #f0d6d7; border-radius: 10px; padding: 16px; background: #fff7f7; display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 12px; align-items: start; }
    .hub-title { grid-column: 1 / -1; text-align: center; font-size: 18px; font-weight: 800; color: #0b3a77; }
    .hub-image { width: fit-content; height: auto; object-fit: contain; display: block; margin: 0 auto; border-radius: 8px; background: #fff; padding: 6px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12); }
    .hub-link { grid-column: 1 / -1; text-align: center; }
    .hub-link a { display: inline-block; color: #0b3a77; font-weight: 800; text-decoration: none; border-bottom: 2px solid #0b3a77; padding-bottom: 2px; font-size: 12px; }
    .hub-link a:hover { color: #072b58; border-bottom-color: #072b58; }
    .hub-section { background: #fff; border: 1px solid #f0d6d7; border-radius: 8px; padding: 10px 12px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06); }
    .hub-section-title { font-size: 12px; font-weight: 800; color: #1a1a1a; margin-bottom: 6px; }
    .hub-section-body { font-size: 11px; color: #333; line-height: 1.4; }
    .hub-section-body a { color: #0b3a77; font-weight: 700; text-decoration: underline; }
    .hub-table { width: 100%; border-collapse: collapse; font-size: 11px; }
    .hub-table th, .hub-table td { border: 1px solid #e8c5c7; padding: 6px; text-align: left; min-width: 60px; }
    .hub-table th { background: #f0d6d7; font-weight: 700; color: #1a1a1a; }
    .formulari-card { border: 2px solid #c9e3f7; border-radius: 10px; padding: 16px; background: #e9f4ff; display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 12px; align-items: start; }
    .formulari-title { grid-column: 1 / -1; text-align: center; font-size: 18px; font-weight: 800; color: #0b3a77; }
    .formulari-link { grid-column: 1 / -1; text-align: center; }
    .formulari-link a { display: inline-block; color: #0b3a77; font-weight: 800; text-decoration: none; border-bottom: 2px solid #0b3a77; padding-bottom: 2px; font-size: 12px; }
    .formulari-link a:hover { color: #072b58; border-bottom-color: #072b58; }
    .formulari-section { background: #fff; border: 1px solid #c9e3f7; border-radius: 8px; padding: 10px 12px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06); }
    .formulari-section-title { font-size: 12px; font-weight: 800; color: #1a1a1a; margin-bottom: 6px; }
    .formulari-section-body { font-size: 11px; color: #333; line-height: 1.4; }
    .formulari-section-body a { color: #0b3a77; font-weight: 700; text-decoration: underline; }
    .formulari-images { width: 100%; display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 10px; grid-column: 1 / -1; }
    .formulari-images img { width: 100%; height: auto; object-fit: contain; border-radius: 8px; background: #fff; padding: 6px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12); }
    .stor-fullscreen { width: 100%; height: 100%; background: #d4e6f1; padding: 20px; display: flex; flex-direction: column; gap: 16px; overflow-y: auto; }
    .stor-section { background: #fff; border-radius: 10px; padding: 18px; border: 2px solid #5a8db8; }
    .stor-title { font-size: 20px; font-weight: 800; color: #1a1a1a; margin-bottom: 16px; text-align: center; text-transform: uppercase; letter-spacing: 1px; }
    .stor-header { font-size: 16px; font-weight: 800; color: #fff; padding: 12px 16px; border-radius: 8px; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
    .stor-header.blue { background: #3b7ca8; }
    .stor-header.green { background: #2d8659; }
    .stor-header.orange { background: #d97f3a; }
    .stor-header.purple { background: #7b5ba6; }
    .stor-section-text { font-size: 13px; color: #333; line-height: 1.65; white-space: pre-line; }
    .stor-section-list { font-size: 13px; color: #333; line-height: 1.8; margin-left: 16px; }
    .stor-section-list li { margin: 6px 0; }
    .unit-footer { text-align: center; padding: 10px; font-size: 10px; color: #666; border-top: 1px solid #ddd; background: rgba(255, 255, 255, 0.98); flex-shrink: 0; position: relative; z-index: 10; line-height: 1.4; }
    @media (max-width: 768px) {
      body { flex-direction: column; min-height: 100dvh; }
      .unit-sidebar { width: 100%; height: auto; padding: 10px; flex-direction: row; align-items: center; overflow: visible; }
      body.sidebar-collapsed .unit-sidebar { margin-left: 0; }
      .header-sidebar-toggle { display: none; }
      .unit-logo { margin-bottom: 0; margin-right: 10px; flex-direction: row; gap: 5px; flex-shrink: 0; }
      .unit-menu { flex-direction: row; gap: 3px; flex: 1; overflow-x: auto; overflow-y: hidden; padding-right: 0; }
      .unit-menu li a { padding: 6px 8px; font-size: 9px; white-space: nowrap; }
      .unit-content { width: 100%; height: auto; min-height: 0; overflow: visible; }
      .unit-header { padding: 10px 12px; }
      .unit-title-header { font-size: 14px; }
      .unit-main { padding: 10px 10px 88px; min-height: 300px; }
      .slide-container { padding: 12px; }
      .hero { min-height: 250px; }
      .slide-nav {
        top: auto;
        bottom: 16px;
        transform: none;
        left: var(--slide-nav-left, 10px);
        right: var(--slide-nav-right, 10px);
        z-index: 25;
      }
      .nav-btn { width: 46px; height: 46px; min-width: 46px; border-radius: 999px; }
      .nav-btn svg { width: 24px; height: 24px; }
      .custom-info-layout { grid-template-columns: 1fr; }
      .mers-card { grid-template-columns: 1fr; }
      .hub-card { grid-template-columns: 1fr; }
      .formulari-card { grid-template-columns: 1fr; }
      .unit-footer { padding: 6px; font-size: 8px; }
    }

    @media (max-width: 480px) {
      .unit-logo-badge { width: 42px; height: 42px; }
      .unit-logo-badge img { width: 38px; height: 38px; }
      .unit-logo-text { font-size: 9px; }
      .unit-main { padding: 8px 8px 82px; }
      .slide-container { padding: 10px; border-radius: 6px; }
      .slide-nav { bottom: 12px; }
      .nav-btn { width: 42px; height: 42px; min-width: 42px; }
      .nav-btn svg { width: 20px; height: 20px; }
    }
  </style>
</head>
<body>
  <div class="bg-image"></div>
  <div class="bg-overlay"></div>

  <aside class="unit-sidebar" id="unitSidebar">
    <div class="unit-logo">
      <div class="unit-logo-badge">
        <img src="{{ asset('assets/logo1.png') }}" alt="logo" onerror="this.style.display='none'">
      </div>
      <div class="unit-logo-text">Kementerian Kesihatan Malaysia<br>Hospital Baling</div>
    </div>

    <ul class="unit-menu">
      <li><a href="{{ route('staff.dashboard') }}">DASHBOARD</a></li>
      @foreach($activeUnits as $slug => $unit)
        <li>
          @if($slug === $unitSlug)
            <div class="unit-menu-row">
              <a href="{{ route('staff.unit.view', ['unitSlug' => $slug]) }}" class="active">{{ $unit['label'] }}</a>
              <button type="button" class="menu-toggle" id="menuProgressToggle" aria-expanded="false" aria-controls="menuProgressPanel">▾</button>
            </div>
            <div class="menu-progress" id="menuProgressPanel" aria-hidden="true">
              <div class="progress-circle" role="img" aria-label="Status kemajuan 0%">
                <svg viewBox="0 0 120 120" aria-hidden="true">
                  <circle class="progress-circle-track" cx="60" cy="60" r="52"></circle>
                  <circle class="progress-circle-fill" cx="60" cy="60" r="52"></circle>
                </svg>
                <div class="progress-circle-text">0%</div>
              </div>
            </div>
          @else
            <a href="{{ route('staff.unit.view', ['unitSlug' => $slug]) }}">{{ $unit['label'] }}</a>
          @endif
        </li>
      @endforeach
    </ul>
  </aside>

  <div class="unit-content">
    <header class="unit-header">
      <div class="unit-header-row">
        <button type="button" class="header-sidebar-toggle" id="sidebarToggle" aria-expanded="true" aria-controls="unitSidebar" aria-label="Tutup menu sisi">✕</button>
        <h1 class="unit-title-header">{{ $pageTitle }}</h1>
      </div>
    </header>

    <main class="unit-main">
      <div class="slide-container">
        @php
          $isPerolehanUnit = $unitSlug === 'unit_perolehan_aset_stor';
          $isPerolehanQuizSlide = $isPerolehanUnit && $currentSlide === 17;
          $isPerolehanInfoSlide = $isPerolehanUnit && $currentSlide === 5;
          $isPerolehanAssetSlide = $isPerolehanUnit && $currentSlide === 7;
          $isPerolehanStorSlide = $isPerolehanUnit && $currentSlide === 16;
          $isPatologiUnit = $unitSlug === 'unit_patologi_transfusi';
          $isPatologiQuizSlide = $isPatologiUnit && $currentSlide === 11;
          $isPatologiCustomSlide = $isPatologiUnit && ($currentSlide === 4 || $currentSlide === 12);
          $isPatologiLinkSlide = $isPatologiUnit && ($currentSlide === 5 || $currentSlide === 6);
          $isRekodPerubatanUnit = $unitSlug === 'unit_rekod_perubatan';
          $isUkkpUnit = $unitSlug === 'unit_keselamatan_kesihatan_pekerjaan';
          $isFarmasiUnit = $unitSlug === 'unit_farmasi';
          $isFarmasiDirectorySlide = $isFarmasiUnit && $currentSlide === 3;
          $isFarmasiMedErrorsSlide = $isFarmasiUnit && $currentSlide === 9;
          $isFarmasiMersSlide = $isFarmasiUnit && $currentSlide === 17;
          $isFarmasiMeFormSlide = $isFarmasiUnit && $currentSlide === 18;
          $isFarmasiMersInfoSlide = $isFarmasiUnit && $currentSlide === 23;
          $isFarmasiHubSlide = $isFarmasiUnit && $currentSlide === 35;
          $isFarmasiFormulariSlide = $isFarmasiUnit && $currentSlide === 36;
          $rekodVideoSlides = [
            17 => 'assets/slide_images/rekod_perubatan/VIDEO DEMO CARA PEMINJAMAN RPP (Fizikal_e-Rekod).mp4',
            18 => 'assets/slide_images/rekod_perubatan/VIDEO DEMO CARA VIEW RPP(Fizikal_e-Rekod).mp4',
            22 => 'assets/slide_images/rekod_perubatan/PAPARAN VIDEO DEMO LAPORAN PERUBATAN.mp4',
          ];
          $ukkpVideoSlides = [
            41 => 'assets/slide_images/ukkp/Promosi SOP Pengendalian Alatan Tajam Dan Prosedur Pengambilan Darah UKKP HRPZ II.mp4',
          ];
          $isRekodVideoSlide = $isRekodPerubatanUnit && array_key_exists($currentSlide, $rekodVideoSlides);
          $isUkkpVideoSlide = $isUkkpUnit && array_key_exists($currentSlide, $ukkpVideoSlides);
          $rekodVideoFile = $isRekodVideoSlide ? $rekodVideoSlides[$currentSlide] : null;
          $rekodVideoTitle = $isRekodVideoSlide ? pathinfo($rekodVideoFile, PATHINFO_FILENAME) : null;
          $ukkpVideoFile = $isUkkpVideoSlide ? $ukkpVideoSlides[$currentSlide] : null;
          $ukkpVideoTitle = $isUkkpVideoSlide ? 'SOP Pengendalian Alatan Tajam Dan Prosedur Pengambilan Darah' : null;
        @endphp
        @includeIf('staff.units.' . $unitSlug)

        @if($hasSlides)
          <div class="slide-controls">
            <div class="slide-info">Slaid {{ $currentSlide }} daripada {{ $totalSlides }}</div>
            <div class="slide-nav">
              @if($currentSlide > 1)
                <a href="{{ route('staff.unit.view', ['unitSlug' => $unitSlug, 'slide' => $currentSlide - 1]) }}" class="nav-btn" title="Sebelumnya">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="4" x2="18" y2="20"></line>
                    <polyline points="12 4 4 12 12 20"></polyline>
                  </svg>
                </a>
              @else
                <button class="nav-btn" disabled title="Sebelumnya">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="4" x2="18" y2="20"></line>
                    <polyline points="12 4 4 12 12 20"></polyline>
                  </svg>
                </button>
              @endif

              @if($currentSlide < $totalSlides)
                @if($isPerolehanQuizSlide || $isPatologiQuizSlide)
                  <button class="nav-btn" id="quizNextBtn" data-href="{{ route('staff.unit.view', ['unitSlug' => $unitSlug, 'slide' => $currentSlide + 1]) }}" disabled title="Seterusnya">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                      <line x1="6" y1="4" x2="6" y2="20"></line>
                      <polyline points="12 4 20 12 12 20"></polyline>
                    </svg>
                  </button>
                @elseif($isRekodVideoSlide || $isUkkpVideoSlide)
                  <button class="nav-btn" id="videoNextBtn" data-href="{{ route('staff.unit.view', ['unitSlug' => $unitSlug, 'slide' => $currentSlide + 1]) }}" disabled title="Seterusnya">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                      <line x1="6" y1="4" x2="6" y2="20"></line>
                      <polyline points="12 4 20 12 12 20"></polyline>
                    </svg>
                  </button>
                @else
                  <a href="{{ route('staff.unit.view', ['unitSlug' => $unitSlug, 'slide' => $currentSlide + 1]) }}" class="nav-btn" title="Seterusnya">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                      <line x1="6" y1="4" x2="6" y2="20"></line>
                      <polyline points="12 4 20 12 12 20"></polyline>
                    </svg>
                  </a>
                @endif
              @else
                <button class="nav-btn" disabled title="Seterusnya">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="6" y1="4" x2="6" y2="20"></line>
                    <polyline points="12 4 20 12 12 20"></polyline>
                  </svg>
                </button>
              @endif
            </div>
          </div>
        @endif
      </div>
    </main>

    <footer class="unit-footer">Hakcipta Terpelihara © 2026 Unit Pengurusan Maklumat Hospital Baling, Kementerian Kesihatan Malaysia</footer>
  </div>

  <script>
    (function(){
      const slug = @json($unitSlug);
      const totalSlides = {{ $totalSlides }};
      const currentSlide = {{ $currentSlide }};
      const hasSlides = @json($hasSlides);
      const isPerolehanUnit = slug === 'unit_perolehan_aset_stor';
      const isPatologiUnit = slug === 'unit_patologi_transfusi';
      const isPerolehanQuizSlide = isPerolehanUnit && currentSlide === 17;
      const isPatologiQuizSlide = isPatologiUnit && currentSlide === 11;
      const isQuizSlide = isPerolehanQuizSlide || isPatologiQuizSlide;
      const isRekodPerubatanUnit = slug === 'unit_rekod_perubatan';
      const isUkkpUnit = slug === 'unit_keselamatan_kesihatan_pekerjaan';
      const rekodVideoSlides = [17, 18, 22];
      const isRekodVideoSlide = isRekodPerubatanUnit && rekodVideoSlides.includes(currentSlide);
      const ukkpVideoSlides = [41];
      const isUkkpVideoSlide = isUkkpUnit && ukkpVideoSlides.includes(currentSlide);
      const isVideoSlide = isRekodVideoSlide || isUkkpVideoSlide;
      const quizKey = isPatologiQuizSlide ? ('quiz_' + slug + '_slide11') : ('quiz_' + slug + '_slide17');
      const hasSlideParam = new URLSearchParams(window.location.search).has('slide');
      const storageKey = 'viewedSlides_' + slug;
      let completionSaved = false;
      let isVerified = false;
      let isCompletedOnServer = false;

      const circleElements = Array.from(document.querySelectorAll('.progress-circle-fill'));
      const circleWraps = Array.from(document.querySelectorAll('.progress-circle'));
      const circleMeta = circleElements.map((circle) => {
        const radius = circle.r.baseVal.value;
        const circumference = 2 * Math.PI * radius;
        circle.style.strokeDasharray = circumference + ' ' + circumference;
        circle.style.strokeDashoffset = circumference;
        return { circle, circumference };
      });

      function normalizeViewed(viewed) {
        if (!Array.isArray(viewed)) return [];
        const cleaned = viewed.map(Number).filter(n => Number.isInteger(n) && n > 0 && n <= totalSlides);
        return Array.from(new Set(cleaned));
      }

      function hasLocalCompletion() {
        const units = JSON.parse(localStorage.getItem('completedUnits') || '[]');
        if (Array.isArray(units) && units.includes(slug)) return true;
        const viewed = JSON.parse(localStorage.getItem(storageKey) || '[]');
        return Array.isArray(viewed) && viewed.length >= totalSlides;
      }

      function applyProgressUI(progress) {
        document.querySelectorAll('.progress-circle-text').forEach((el) => {
          el.textContent = progress + '%';
        });

        document.querySelectorAll('.progress-text-value').forEach((el) => {
          el.textContent = progress + '%';
        });

        circleMeta.forEach(({ circle, circumference }) => {
          const offset = circumference - (progress / 100) * circumference;
          circle.style.strokeDashoffset = offset;
          circle.classList.remove('low', 'medium', 'high');
          if (progress <= 39) circle.classList.add('low');
          else if (progress <= 79) circle.classList.add('medium');
          else circle.classList.add('high');
        });

        circleWraps.forEach((wrap) => {
          wrap.setAttribute('aria-label', 'Status kemajuan ' + progress + '%');
        });
      }

      async function clearIfNeeded() {
        try {
          const resp = await fetch(@json(route('staff.unit.check')) + '?unit=' + slug);
          const data = await resp.json();
          if (!data.success) return;

          isCompletedOnServer = !!data.is_completed;

          if (isCompletedOnServer) {
            forceCompletedStateInLocal();
            return;
          }

          if (hasLocalCompletion()) {
            localStorage.removeItem(storageKey);
            localStorage.removeItem('completedUnits');
            completionSaved = false;
          }
        } catch (e) {
          console.error(e);
        }
      }

      async function redirectToLastSlide() {
        if (!hasSlides || hasSlideParam) return false;
        try {
          const resp = await fetch(@json(route('staff.unit.progress.get')) + '?unit=' + slug);
          const data = await resp.json();
          const lastSlide = Number(data.last_slide || 0);
          if (data.success && Number.isInteger(lastSlide) && lastSlide >= 1 && lastSlide <= totalSlides && lastSlide !== currentSlide) {
            window.location.href = @json(route('staff.unit.view', ['unitSlug' => $unitSlug])) + '?slide=' + lastSlide;
            return true;
          }
        } catch (e) {
          console.error(e);
        }
        return false;
      }

      async function syncFromServer() {
        try {
          const resp = await fetch(@json(route('staff.unit.progress.get')) + '?unit=' + slug);
          const data = await resp.json();
          if (!data.success) return;
          const localViewed = normalizeViewed(JSON.parse(localStorage.getItem(storageKey) || '[]'));
          const merged = normalizeViewed(localViewed.concat(data.viewed_slides || []));
          localStorage.setItem(storageKey, JSON.stringify(merged));
        } catch (e) {
          console.error(e);
        }
      }

      async function fetchVerification() {
        if (!isPatologiUnit) return;
        try {
          const resp = await fetch(@json(route('staff.unit.check')) + '?unit=' + slug);
          const data = await resp.json();
          if (data.success) {
            isCompletedOnServer = !!data.is_completed;
            isVerified = !!data.is_verified;
            if (isCompletedOnServer || isVerified) {
              forceCompletedStateInLocal();
            }
          }
        } catch (e) {
          console.error(e);
        }
      }

      async function saveCompletion() {
        await fetch(@json(route('staff.unit.completion.save')), {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': @json(csrf_token())
          },
          body: JSON.stringify({ unit_slug: slug })
        });
      }

      function saveProgress(viewed, lastSlide) {
        fetch(@json(route('staff.unit.progress.save')), {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': @json(csrf_token())
          },
          body: JSON.stringify({
            unit_slug: slug,
            viewed_slides: viewed,
            last_slide: lastSlide,
            total_slides: totalSlides,
          })
        });
      }

      function markCompletedInLocal() {
        const units = JSON.parse(localStorage.getItem('completedUnits') || '[]');
        if (!units.includes(slug)) {
          units.push(slug);
          localStorage.setItem('completedUnits', JSON.stringify(units));
        }
      }

      function allSlidesViewed() {
        if (!hasSlides || totalSlides < 1) return [1];
        return Array.from({ length: totalSlides }, (_, i) => i + 1);
      }

      function forceCompletedStateInLocal() {
        const viewed = allSlidesViewed();
        localStorage.setItem(storageKey, JSON.stringify(viewed));
        markCompletedInLocal();
        completionSaved = true;
        saveProgress(viewed, totalSlides);
      }

      async function updateProgress() {
        let viewed = normalizeViewed(JSON.parse(localStorage.getItem(storageKey) || '[]'));
        const lastSlide = hasSlides ? currentSlide : 1;

        const quizPassed = isCompletedOnServer || !isQuizSlide || localStorage.getItem(quizKey) === 'true';
        if (quizPassed) {
          if (!viewed.includes(lastSlide)) {
            viewed.push(lastSlide);
          }
        } else {
          viewed = viewed.filter(slide => slide !== lastSlide);
        }

        if (isCompletedOnServer) {
          viewed = allSlidesViewed();
        }

        viewed = normalizeViewed(viewed);
        localStorage.setItem(storageKey, JSON.stringify(viewed));

        const baseProgress = hasSlides ? Math.round((viewed.length / totalSlides) * 100) : 100;
        let progress = baseProgress;

        if (isCompletedOnServer) {
          progress = 100;
          if (isPatologiUnit) {
            const statusEl = document.getElementById('progressStatus');
            if (statusEl) statusEl.textContent = '';
          }
        } else if (isPatologiUnit) {
          const statusEl = document.getElementById('progressStatus');
          if (isVerified) {
            progress = 100;
            if (statusEl) statusEl.textContent = '(Disahkan)';
          } else if (baseProgress === 100) {
            progress = 99;
            if (statusEl) statusEl.textContent = '(Menunggu pengesahan penyelia)';
          } else if (statusEl) {
            statusEl.textContent = '';
          }
        }

        applyProgressUI(progress);

        saveProgress(viewed, lastSlide);

        if (baseProgress === 100 && !completionSaved) {
          completionSaved = true;
          markCompletedInLocal();
          await saveCompletion();
        }
      }

      async function init() {
        if (await redirectToLastSlide()) return;
        await clearIfNeeded();
        if (hasSlides) {
          await syncFromServer();
        }
        await fetchVerification();
        await updateProgress();
      }

      init();
      setInterval(() => {
        clearIfNeeded()
          .then(() => hasSlides ? syncFromServer() : Promise.resolve())
          .then(fetchVerification)
          .then(updateProgress);
      }, 30000);

      if (hasSlides) {
        document.querySelectorAll('a.nav-btn').forEach(link => {
          link.addEventListener('click', () => {
            setTimeout(updateProgress, 200);
          });
        });
      }

      window.addEventListener('focus', () => {
        clearIfNeeded()
          .then(() => hasSlides ? syncFromServer() : Promise.resolve())
          .then(fetchVerification)
          .then(updateProgress);
      });

      function updateQuizUI() {
        if (!isQuizSlide) return;
        const nextBtn = document.getElementById('quizNextBtn');
        const feedback = document.getElementById('quizFeedback');
        const passed = localStorage.getItem(quizKey) === 'true';

        if (nextBtn) {
          nextBtn.disabled = !passed;
        }

        if (feedback) {
          if (passed) {
            feedback.textContent = 'Jawapan betul. Anda boleh ke slaid seterusnya.';
            feedback.className = 'quiz-feedback success';
          } else if (!feedback.textContent.trim()) {
            feedback.textContent = '';
            feedback.className = 'quiz-feedback';
          }
        }
      }

      if (isQuizSlide) {
        const submitBtn = document.getElementById('quizSubmit');
        const resetBtn = document.getElementById('quizReset');
        const feedback = document.getElementById('quizFeedback');
        const nextBtn = document.getElementById('quizNextBtn');

        updateQuizUI();

        if (submitBtn) {
          submitBtn.addEventListener('click', () => {
            const q1 = document.querySelector('input[name="quiz_q1"]:checked');
            const q2 = document.querySelector('input[name="quiz_q2"]:checked');

            if (isPatologiQuizSlide) {
              const q3 = document.querySelector('input[name="quiz_q3"]:checked');
              const q4 = document.querySelector('input[name="quiz_q4"]:checked');

              if (!q1 || !q2 || !q3 || !q4) {
                if (feedback) {
                  feedback.textContent = 'Sila jawab semua soalan.';
                  feedback.className = 'quiz-feedback error';
                }
                return;
              }

              const correctPatologi = q1.value === 'A' && q2.value === 'B' && q3.value === 'B' && q4.value === 'B';
              if (correctPatologi) {
                localStorage.setItem(quizKey, 'true');
                updateProgress();
                updateQuizUI();
              } else {
                localStorage.removeItem(quizKey);
                if (feedback) {
                  feedback.textContent = 'Jawapan belum tepat. Sila cuba lagi.';
                  feedback.className = 'quiz-feedback error';
                }
                updateProgress();
                updateQuizUI();
              }
              return;
            }

            if (!q1 || !q2) {
              if (feedback) {
                feedback.textContent = 'Sila jawab semua soalan.';
                feedback.className = 'quiz-feedback error';
              }
              return;
            }

            const correct = q1.value === 'A' && q2.value === 'D';
            if (correct) {
              localStorage.setItem(quizKey, 'true');
              updateProgress();
              updateQuizUI();
            } else {
              localStorage.removeItem(quizKey);
              if (feedback) {
                feedback.textContent = 'Jawapan belum tepat. Sila cuba lagi.';
                feedback.className = 'quiz-feedback error';
              }
              updateProgress();
              updateQuizUI();
            }
          });
        }

        if (resetBtn) {
          resetBtn.addEventListener('click', () => {
            const resetSelector = isPatologiQuizSlide
              ? 'input[name="quiz_q1"], input[name="quiz_q2"], input[name="quiz_q3"], input[name="quiz_q4"]'
              : 'input[name="quiz_q1"], input[name="quiz_q2"]';
            document.querySelectorAll(resetSelector).forEach(input => {
              input.checked = false;
            });
            localStorage.removeItem(quizKey);
            if (feedback) {
              feedback.textContent = '';
              feedback.className = 'quiz-feedback';
            }
            updateProgress();
            updateQuizUI();
          });
        }

        if (nextBtn) {
          nextBtn.addEventListener('click', () => {
            if (localStorage.getItem(quizKey) === 'true') {
              window.location.href = nextBtn.getAttribute('data-href');
            } else if (feedback) {
              feedback.textContent = 'Sila jawab kuiz dengan betul untuk teruskan.';
              feedback.className = 'quiz-feedback error';
            }
          });
        }
      }

      if (isVideoSlide) {
        const video = document.querySelector('.video-frame');
        const nextBtn = document.getElementById('videoNextBtn');

        if (video) {
          let lastTime = 0;
          video.playbackRate = 1;

          if (nextBtn) {
            nextBtn.disabled = true;
          }

          video.addEventListener('ratechange', () => {
            if (video.playbackRate !== 1) {
              video.playbackRate = 1;
            }
          });

          video.addEventListener('timeupdate', () => {
            if (!video.seeking) {
              lastTime = video.currentTime;
            }
          });

          video.addEventListener('seeking', () => {
            if (video.currentTime > lastTime + 0.2) {
              video.currentTime = lastTime;
            }
          });

          video.addEventListener('loadedmetadata', () => {
            video.currentTime = 0;
          });

          video.addEventListener('ended', () => {
            if (nextBtn) {
              nextBtn.disabled = false;
            }
          });
        }

        if (nextBtn) {
          nextBtn.addEventListener('click', () => {
            if (!nextBtn.disabled) {
              window.location.href = nextBtn.getAttribute('data-href');
            }
          });
        }
      }

      const sidebarToggle = document.getElementById('sidebarToggle');
      const sidebar = document.getElementById('unitSidebar');
      const sidebarStorageKey = 'staff_unit_sidebar_collapsed';
      let navSyncTimeoutId = null;

      function scheduleSlideNavSync() {
        syncSlideNavPosition();
        requestAnimationFrame(() => {
          requestAnimationFrame(syncSlideNavPosition);
        });

        if (navSyncTimeoutId) {
          clearTimeout(navSyncTimeoutId);
        }
        navSyncTimeoutId = setTimeout(syncSlideNavPosition, 320);
      }

      function applySidebarState(collapsed) {
        document.body.classList.toggle('sidebar-collapsed', collapsed);

        if (sidebarToggle) {
          sidebarToggle.setAttribute('aria-expanded', String(!collapsed));
          sidebarToggle.setAttribute('aria-label', collapsed ? 'Buka menu sisi' : 'Tutup menu sisi');
          sidebarToggle.textContent = collapsed ? '☰' : '✕';
        }

        scheduleSlideNavSync();
      }

      if (sidebarToggle && sidebar) {
        let collapsedByDefault = false;
        if (window.innerWidth > 768) {
          try {
            collapsedByDefault = localStorage.getItem(sidebarStorageKey) === '1';
          } catch (error) {
            collapsedByDefault = false;
          }
        }

        applySidebarState(collapsedByDefault);

        sidebarToggle.addEventListener('click', () => {
          const isCollapsed = !document.body.classList.contains('sidebar-collapsed');
          applySidebarState(isCollapsed);
          try {
            localStorage.setItem(sidebarStorageKey, isCollapsed ? '1' : '0');
          } catch (error) {
          }
        });

        sidebar.addEventListener('transitionend', (event) => {
          if (event.propertyName === 'margin-left') {
            scheduleSlideNavSync();
          }
        });
      }

      const menuToggle = document.getElementById('menuProgressToggle');
      const menuPanel = document.getElementById('menuProgressPanel');
      if (menuToggle && menuPanel) {
        menuPanel.classList.add('active');
        menuPanel.setAttribute('aria-hidden', 'false');
        menuToggle.setAttribute('aria-expanded', 'true');

        menuToggle.addEventListener('click', () => {
          const isOpen = menuPanel.classList.toggle('active');
          menuPanel.setAttribute('aria-hidden', String(!isOpen));
          menuToggle.setAttribute('aria-expanded', String(isOpen));
        });
      }

      function triggerNextSlideByKeyboard() {
        const activeEl = document.activeElement;
        const tagName = activeEl?.tagName?.toLowerCase();
        if (
          activeEl?.isContentEditable ||
          tagName === 'input' ||
          tagName === 'textarea' ||
          tagName === 'select' ||
          tagName === 'button'
        ) {
          return;
        }

        if (isQuizSlide) {
          const nextBtn = document.getElementById('quizNextBtn');
          if (nextBtn && !nextBtn.disabled) {
            nextBtn.click();
          }
          return;
        }

        if (isVideoSlide) {
          const nextBtn = document.getElementById('videoNextBtn');
          if (nextBtn && !nextBtn.disabled) {
            nextBtn.click();
          }
          return;
        }

        const nextAnchor = document.querySelector('.slide-nav a.nav-btn[title="Seterusnya"]');
        if (nextAnchor && nextAnchor.getAttribute('href')) {
          window.location.href = nextAnchor.getAttribute('href');
        }
      }

      document.addEventListener('keydown', (event) => {
        if (event.altKey || event.ctrlKey || event.metaKey || event.shiftKey || event.repeat) {
          return;
        }

        if (event.key === 'ArrowRight' || event.key === 'PageDown') {
          event.preventDefault();
          triggerNextSlideByKeyboard();
        }
      });

      function syncSlideNavPosition() {
        const nav = document.querySelector('.slide-nav');
        const container = document.querySelector('.slide-container');
        if (!nav || !container) return;

        if (window.innerWidth <= 768) {
          nav.style.setProperty('--slide-nav-left', '10px');
          nav.style.setProperty('--slide-nav-right', '10px');
          return;
        }

        const rect = container.getBoundingClientRect();
        const leftGap = Math.max(Math.round(rect.left + 10), 8);
        const rightGap = Math.max(Math.round(window.innerWidth - rect.right + 10), 8);

        nav.style.setProperty('--slide-nav-left', leftGap + 'px');
        nav.style.setProperty('--slide-nav-right', rightGap + 'px');
      }

      syncSlideNavPosition();
      window.addEventListener('resize', syncSlideNavPosition);
      window.addEventListener('resize', () => {
        if (window.innerWidth <= 768) {
          applySidebarState(false);
        }
      });
      window.addEventListener('scroll', syncSlideNavPosition, { passive: true });
      document.querySelector('.unit-main')?.addEventListener('scroll', syncSlideNavPosition, { passive: true });
    })();
  </script>
</body>
</html>
