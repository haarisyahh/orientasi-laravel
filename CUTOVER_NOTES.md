# Cutover Notes (Laravel)

## Current Entry Behavior

- Legacy landing file `../index.html` kini auto-redirect ke `orientasi-laravel/public/`.
- Fallback link tersedia jika browser menyekat auto-redirect.
- Redirect kini menggunakan path mutlak `/orientasi-laravel/public/...` selepas folder Laravel dipindahkan ke `c:\laragon\www\orientasi-laravel`.
- URL legacy berikut kini redirect ke Laravel route setara:
   - `../login.php` -> `/login`
   - `../register.php` -> `/register`
   - `../login_Admin.php` -> `/login-admin`
   - `../login_penyelia.php` -> `/login-penyelia`
   - `../register_penyelia.php` -> `/register-penyelia`
   - `../staff_dashboard.php` -> `/staff/dashboard`
   - `../penyelia_dashboard.php` -> `/penyelia/dashboard`
   - `../dashboard_admin.php` -> `/admin/dashboard`
   - `../profil_admin.php` -> `/admin/profile`
   - `../admin_staff_progress.php` -> `/admin/staff-progress`
   - `../admin_reset_units.php` -> `/admin/reset-units`
   - `../laporan.php` -> `/admin/laporan`
   - `../certificate.php` -> `/staff/certificate`
   - `../main_dashboard.php` -> `/`
   - `../logout.php` -> `/logout-legacy`
   - `../reset.php` -> `/admin/reset-units`
   - `../sen_penyelia_unit.php` -> `/admin/dashboard`
   - `../api_reset_unit.php` -> `/admin/reset-units/action` (307)
   - `../save_completion.php` -> `/staff/completion-save` (307)
   - `../save_unit_progress.php` -> `/staff/unit-progress` (307)
   - `../get_completion_status.php` -> `/staff/completion-status`
   - `../get_unit_progress.php` -> `/staff/unit-progress`
   - `../check_unit_completion.php` -> `/staff/unit-completion`
   - `../verify_orientation.php` -> `/penyelia/verify` (307)
   - `../unit_*.php` -> `/staff/unit/{unitSlug}`

## Local Access

- Legacy URL: `http://localhost/Orientasi/`
- Redirect target: `http://localhost/Orientasi/orientasi-laravel/public/`

## Recommended Production Setup

1. Tukar web root terus ke folder `orientasi-laravel/public`.
2. Pastikan `.env` production diisi (DB, APP_URL, APP_KEY).
3. Jalankan cache command:
   - `php artisan config:cache`
   - `php artisan route:cache`
   - `php artisan view:cache`
4. Jalankan UAT penuh berasaskan `UAT_CHECKLIST.md`.

## Rollback

- Jika perlu rollback segera, gantikan semula kandungan `../index.html` kepada versi lama yang memaparkan menu asal.

## Legacy Archive Reference

- Rujuk `../LEGACY_ARCHIVE_CANDIDATES.md` untuk senarai fail legacy yang selamat diarkibkan secara berperingkat selepas tempoh stabilisasi.
- Snapshot arkib redirect stub telah diwujudkan di `../_legacy_archive/redirect_stubs/`.
