# UAT Checklist (Laravel Cutover)

## Pre-check

- [ ] `php artisan --version` berjaya
- [ ] `.env` DB credentials betul dan boleh query data
- [ ] Folder `public/assets` mengandungi image/logo yang digunakan UI

## Staff Flow

- [ ] Buka `/register`, daftar staff baru, mesej berjaya dipaparkan
- [ ] Login staff di `/login` dengan kredensial sah
- [ ] Dashboard staff dipaparkan dengan senarai unit
- [ ] Klik satu unit, simpan progress, kembali dashboard
- [ ] `Semak Status Orientasi` papar modal yang betul (warning/success)
- [ ] `Dapatkan Sijil Anda` berfungsi selepas complete orientasi
- [ ] Logout staff berjaya

## Penyelia Flow

- [ ] Daftar penyelia di `/register-penyelia`
- [ ] Login penyelia di `/login-penyelia` atau `/penyelia/login`
- [ ] Dashboard penyelia memaparkan senarai staff
- [ ] Tindakan verify untuk unit patologi/transfusi berjaya
- [ ] Logout penyelia berjaya

## Admin Flow

- [ ] Login admin di `/login-admin` atau `/admin/login`
- [ ] Dashboard admin memaparkan statistik utama
- [ ] Buka `Staff Progress` dan semak data progress dipaparkan
- [ ] Buka `Reset Unit User`, reset satu unit, data progress berubah
- [ ] Buka `Laporan`, edit pengguna berjaya
- [ ] Buka `Laporan`, padam pengguna berjaya
- [ ] Buka `Profil Admin`, kemas kini nama/email berjaya
- [ ] Tukar kata laluan admin berjaya
- [ ] Muat naik gambar profil admin berjaya
- [ ] Logout admin berjaya

## Access Control

- [ ] Route admin (`/admin/*`) redirect ke login bila belum authenticated
- [ ] Route staff (`/staff/*`) redirect ke login bila belum authenticated
- [ ] Route penyelia dashboard redirect ke login bila belum authenticated

## Cutover Validation

- [ ] Landing page legacy `index.html` membuka link ke Laravel route
- [ ] Tiada ralat 404 pada link utama (register/login/admin/penyelia)
- [ ] Data lama masih boleh dibaca dalam modul Laravel
