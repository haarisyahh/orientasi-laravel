# Orientasi Laravel Migration Roadmap

## Current Status

- Laravel app scaffolded in `orientasi-laravel/`
- Home page migrated to Blade (`resources/views/home.blade.php`)
- Laravel routes created for home and temporary bridge redirects in `routes/web.php`
- Legacy bridge base URL configurable via `ORIENTASI_LEGACY_URL`

### Completed Native Laravel Modules

- Staff registration and login module
- Staff dashboard module (unit list + orientation status modal)
- Generic unit page module (`/staff/unit/{unitSlug}`)
- Unit progress/completion APIs:
   - `/staff/unit-progress` (GET/POST)
   - `/staff/unit-completion` (GET)
   - `/staff/completion-save` (POST)
- Staff certificate module (`/staff/certificate`)
- Penyelia registration, login, dashboard, verification module
- Admin login and dashboard module
- Admin advanced tools module:
   - Staff progress
   - Reset unit user
   - Laporan (edit/delete user)
- Admin profile module:
   - Update personal info
   - Change password
   - Upload profile picture

### Still Using Legacy Links (Temporary)

- Any legacy pages not yet mapped to Laravel routes will continue through `ORIENTASI_LEGACY_URL`

## Phase 1 - Foundation (Completed / In Progress)

1. Initialize Laravel project in separate folder (safe side-by-side migration)
2. Keep current legacy PHP app running without interruption
3. Recreate landing page UI in Blade
4. Add temporary redirects from Laravel routes to legacy pages

## Phase 2 - Database & Auth Core

1. Create Laravel migrations from existing SQL schema (`database_migration.sql`, role mapping migrations)
2. Implement Eloquent models:
   - User
   - Unit
   - Completion
   - PenyeliaMapping
3. Implement authentication guards/roles:
   - Staff
   - Penyelia
   - Admin
4. Replace:
   - `login.php`
   - `login_penyelia.php`
   - `login_Admin.php`
   - `logout.php`

## Phase 3 - Feature-by-Feature Porting

Port module routes/controllers/views in this order:

1. Registration flows
   - `register.php`
   - `register_penyelia.php`
2. Main dashboards
   - `main_dashboard.php`
   - `staff_dashboard.php`
   - `penyelia_dashboard.php`
   - `dashboard_admin.php`
3. Progress tracking APIs
   - `save_completion.php`
   - `save_unit_progress.php`
   - `get_completion_status.php`
   - `get_unit_progress.php`
4. Unit pages (`unit_*.php`) via one reusable controller + Blade templates
5. Admin tools and reports
   - `laporan.php`
   - `admin_staff_progress.php`
   - `admin_reset_units.php`

Status: Completed (auth, dashboard, unit flows, advanced admin tools completed in Laravel)

## Phase 4 - Cutover

1. Replace remaining bridge redirects with native Laravel pages (if any)
2. Move all static assets to Laravel `public/assets`
3. Run full UAT by role
4. Switch web root to Laravel `public/`
5. Archive legacy PHP files

## Notes

- Keep URLs stable where possible by matching legacy endpoints through Laravel routes.
- Use middleware (`auth`, `role`) for access control consistency.
- Migrate and verify one module at a time to reduce risk.
