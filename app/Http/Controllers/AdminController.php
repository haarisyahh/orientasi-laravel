<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    private const ROLE = 'admin';
    private const SUPER_ADMIN_ROLE = 'super_admin';
    private const SUPER_ADMIN_NO_IC = '020806020528';

    private const UNIT_SLIDE_CONFIG = [
        'unit_kewangan' => ['label' => 'UNIT KEWANGAN', 'folder' => 'kewangan'],
        'unit_sumber_manusia' => ['label' => 'UNIT SUMBER MANUSIA', 'folder' => 'sumber manusia'],
        'unit_perolehan_aset_stor' => ['label' => 'UNIT PEROLEHAN, ASET & STOR', 'folder' => 'perolehan,aset&stor'],
        'unit_pengurusan_maklumat' => ['label' => 'UNIT PENGURUSAN MAKLUMAT', 'folder' => 'pengurusan_maklumat'],
        'unit_rekod_perubatan' => ['label' => 'UNIT REKOD PERUBATAN', 'folder' => 'rekod_perubatan'],
        'unit_kualiti' => ['label' => 'UNIT KUALITI', 'folder' => 'kualiti'],
        'unit_patologi_transfusi' => ['label' => 'UNIT PATOLOGI & TRANSFUSI', 'folder' => 'patologi&transfusi'],
        'unit_keselamatan_kesihatan_pekerjaan' => ['label' => 'UNIT KESELAMATAN & KESIHATAN PEKERJAAN', 'folder' => 'ukkp'],
        'unit_farmasi' => ['label' => 'UNIT FARMASI', 'folder' => 'farmasi'],
        'unit_kawalan_infeksi' => ['label' => 'UNIT KAWALAN INFEKSI', 'folder' => 'kawalan infeksi'],
        'unit_kejuruteraan' => ['label' => 'UNIT KEJURUTERAAN', 'folder' => 'Kejuruteraan'],
    ];

    public function showLogin()
    {
        if (session('admin_id') && $this->isAdminRole((string) session('role'))) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'no_ic' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string'],
        ], [
            'required' => 'Sila isi semua medan.',
        ]);

        $admin = DB::table('admin')
            ->select('id', 'nama_penuh', 'no_ic', 'password', 'role')
            ->where('no_ic', trim($credentials['no_ic']))
            ->first();

        if (!$admin) {
            return back()->withErrors([
                'no_ic' => 'No. IC tidak dijumpai atau bukan akaun Admin. Hanya admin dibenarkan.',
            ])->withInput();
        }

        if (!$this->isAdminRole((string) $admin->role)) {
            return back()->withErrors(['no_ic' => 'Akaun ini bukan untuk Admin.'])->withInput();
        }

        if (!Hash::check($credentials['password'], $admin->password)) {
            return back()->withErrors(['password' => 'Kata laluan tidak sah.'])->withInput();
        }

        $this->syncPrimarySuperAdmin();
        $latestRole = (string) DB::table('admin')->where('id', (int) $admin->id)->value('role');
        if ($latestRole !== '' && $this->isAdminRole($latestRole)) {
            $admin->role = $latestRole;
        }

        $request->session()->regenerate();
        $request->session()->put([
            'user_id' => $admin->id,
            'admin_id' => $admin->id,
            'nama_penuh' => $admin->nama_penuh,
            'no_ic' => $admin->no_ic,
            'role' => $admin->role,
        ]);

        return redirect()->route('admin.dashboard');
    }

    public function dashboard(Request $request)
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.admin');
        }

        $isSuperAdmin = $this->isSuperAdmin($request);

        $totalUnits = 11;

        $completedUsersCount = DB::table('staff_baru as sb')
            ->leftJoin('user_role_mapping as urm', function ($join) {
                $join->on('sb.id', '=', 'urm.new_user_id')->where('urm.role', '=', 'staff_baru');
            })
            ->leftJoin('user_orientations as uo', function ($join) {
                $join->on(DB::raw('COALESCE(urm.old_user_id, sb.id)'), '=', 'uo.user_id');
            })
            ->select('sb.id', DB::raw('COUNT(uo.id) as completion_count'))
            ->groupBy('sb.id')
            ->havingRaw('COUNT(uo.id) >= ?', [$totalUnits])
            ->count();

        $links = [
            'Pengurusan Staff' => route('admin.pengurusan-staff'),
            'Pengurusan Penyelia' => route('admin.pengurusan-penyelia'),
            'Pengurusan Admin' => route('admin.pengurusan-admin'),
            'Profil Admin' => route('admin.profile'),
            'Kemaskini Slaid / Kandungan Unit' => route('admin.slide-content'),
        ];

        if ($isSuperAdmin) {
            $links['Reset Unit User - untuk testing sahaja'] = route('admin.reset-units');
            $links['Tandakan Unit Selesai - untuk testing sahaja'] = route('admin.complete-units');
        }

        return view('admin.dashboard', [
            'adminName' => (string) $request->session()->get('nama_penuh', 'Admin'),
            'staffCount' => (int) DB::table('staff_baru')->count(),
            'penyeliaCount' => (int) DB::table('penyelia')->count(),
            'completedUsersCount' => $completedUsersCount,
            'links' => $links,
            'isSuperAdmin' => $isSuperAdmin,
        ]);
    }

    public function addAdmin(Request $request): RedirectResponse
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.admin');
        }

        if (!$this->isSuperAdmin($request)) {
            return redirect()->route('admin.dashboard')->withErrors([
                'auth' => 'Hanya super admin dibenarkan tambah admin.',
            ]);
        }

        $data = $request->validate([
            'no_ic' => ['required', 'string', 'max:20', 'unique:admin,no_ic'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'no_ic.required' => 'ID Admin diperlukan.',
            'no_ic.unique' => 'ID Admin sudah wujud.',
            'password.required' => 'Kata laluan diperlukan.',
            'password.min' => 'Kata laluan mesti sekurang-kurangnya 6 aksara.',
            'password.confirmed' => 'Pengesahan kata laluan tidak sepadan.',
        ]);

        $noIc = trim((string) $data['no_ic']);
        $safeToken = strtolower((string) preg_replace('/[^a-z0-9]/i', '', $noIc));
        if ($safeToken === '') {
            $safeToken = 'admin'.time();
        }

        $baseEmailLocal = $safeToken;
        $email = $baseEmailLocal.'@admin.local';
        $counter = 1;
        while (DB::table('admin')->where('email', $email)->exists()) {
            $email = $baseEmailLocal.$counter.'@admin.local';
            $counter++;
        }

        DB::table('admin')->insert([
            'nama_penuh' => 'Admin '.$noIc,
            'no_ic' => $noIc,
            'email' => $email,
            'password' => Hash::make((string) $data['password']),
            'role' => $this->resolveAdminRoleByNoIc($noIc),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.dashboard')->with('status', 'Admin baru berjaya ditambah.');
    }

    public function slideContent(Request $request)
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.admin');
        }

        $units = self::UNIT_SLIDE_CONFIG;
        $selectedUnitSlug = trim((string) $request->query('unit_slug', array_key_first($units)));
        if (!array_key_exists($selectedUnitSlug, $units)) {
            $selectedUnitSlug = array_key_first($units);
        }

        $selectedUnit = $units[$selectedUnitSlug];
        $folder = (string) $selectedUnit['folder'];
        $slideFiles = $this->getSlideFiles($folder);
        $slides = array_map(function (string $filename, int $index) use ($folder): array {
            return [
                'number' => $index + 1,
                'filename' => $filename,
                'url' => asset('assets/slide_images/'.$folder.'/'.$filename),
            ];
        }, $slideFiles, array_keys($slideFiles));

        return view('admin.slide-content', [
            'adminName' => (string) $request->session()->get('nama_penuh', 'Admin'),
            'units' => $units,
            'selectedUnitSlug' => $selectedUnitSlug,
            'selectedUnit' => $selectedUnit,
            'slides' => $slides,
        ]);
    }

    public function slideContentUpload(Request $request): RedirectResponse
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.admin');
        }

        $data = $request->validate([
            'unit_slug' => ['required', 'string'],
            'slide_file' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:10240'],
            'slide_number' => ['nullable', 'integer', 'min:1'],
        ]);

        $unitSlug = trim($data['unit_slug']);
        if (!array_key_exists($unitSlug, self::UNIT_SLIDE_CONFIG)) {
            return redirect()->route('admin.slide-content')->withErrors(['unit_slug' => 'Unit tidak sah.']);
        }

        $folder = (string) self::UNIT_SLIDE_CONFIG[$unitSlug]['folder'];
        $path = public_path('assets/slide_images/'.$folder);
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        /** @var UploadedFile $file */
        $file = $request->file('slide_file');
        $existingFiles = $this->getSlideFiles($folder);
        $replaceNumber = isset($data['slide_number']) ? (int) $data['slide_number'] : null;
        $targetNumber = $replaceNumber ?? (count($existingFiles) + 1);

        if ($replaceNumber !== null && $replaceNumber > count($existingFiles)) {
            return redirect()->route('admin.slide-content', ['unit_slug' => $unitSlug])
                ->withErrors(['slide_number' => 'Nombor slide untuk ganti tidak wujud.'])
                ->withInput();
        }

        foreach (['png', 'jpg', 'jpeg'] as $ext) {
            $existingPath = $path.DIRECTORY_SEPARATOR.'Slide'.$targetNumber.'.'.$ext;
            if (File::exists($existingPath)) {
                File::delete($existingPath);
            }
        }

        $ext = strtolower((string) $file->getClientOriginalExtension());
        if (!in_array($ext, ['png', 'jpg', 'jpeg'], true)) {
            $ext = 'jpg';
        }

        $file->move($path, 'Slide'.$targetNumber.'.'.$ext);

        return redirect()->route('admin.slide-content', ['unit_slug' => $unitSlug])
            ->with('status', $replaceNumber !== null ? 'Slide berjaya diganti.' : 'Slide baru berjaya ditambah.');
    }

    public function slideContentDelete(Request $request): RedirectResponse
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.admin');
        }

        $data = $request->validate([
            'unit_slug' => ['required', 'string'],
            'slide_number' => ['required', 'integer', 'min:1'],
        ]);

        $unitSlug = trim($data['unit_slug']);
        if (!array_key_exists($unitSlug, self::UNIT_SLIDE_CONFIG)) {
            return redirect()->route('admin.slide-content')->withErrors(['unit_slug' => 'Unit tidak sah.']);
        }

        $folder = (string) self::UNIT_SLIDE_CONFIG[$unitSlug]['folder'];
        $path = public_path('assets/slide_images/'.$folder);
        $slideNumber = (int) $data['slide_number'];

        $deleted = false;
        foreach (['png', 'jpg', 'jpeg'] as $ext) {
            $slidePath = $path.DIRECTORY_SEPARATOR.'Slide'.$slideNumber.'.'.$ext;
            if (File::exists($slidePath)) {
                File::delete($slidePath);
                $deleted = true;
            }
        }

        if (!$deleted) {
            return redirect()->route('admin.slide-content', ['unit_slug' => $unitSlug])
                ->withErrors(['slide_number' => 'Slide yang dipilih tidak ditemui.']);
        }

        $this->renumberSlideFiles($folder);

        return redirect()->route('admin.slide-content', ['unit_slug' => $unitSlug])
            ->with('status', 'Slide berjaya dipadam. Nombor slide telah disusun semula.');
    }

    public function profile(Request $request)
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.admin');
        }

        $adminId = (int) $request->session()->get('admin_id');
        $admin = DB::table('admin')->select('id', 'nama_penuh', 'no_ic', 'email')->where('id', $adminId)->first();

        if (!$admin) {
            return redirect()->route('admin.dashboard')->with('status', 'Akaun admin tidak ditemui.');
        }

        return view('admin.profile', [
            'admin' => $admin,
            'profilePic' => $this->profilePicturePath($adminId),
        ]);
    }

    public function profileUpdateInfo(Request $request): RedirectResponse
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.admin');
        }

        $data = $request->validate([
            'nama_penuh' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        $adminId = (int) $request->session()->get('admin_id');

        DB::table('admin')->where('id', $adminId)->update([
            'nama_penuh' => trim($data['nama_penuh']),
            'email' => trim($data['email']),
            'updated_at' => now(),
        ]);

        $request->session()->put('nama_penuh', trim($data['nama_penuh']));

        return redirect()->route('admin.profile')->with('status', 'Maklumat Peribadi telah berjaya dikemas kini.');
    }

    public function profileUpdatePassword(Request $request): RedirectResponse
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.admin');
        }

        $data = $request->validate([
            'old_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:6'],
            'confirm_password' => ['required', 'same:new_password'],
        ], [
            'confirm_password.same' => 'Kata laluan baru tidak sepadan.',
            'new_password.min' => 'Kata laluan baru mesti sekurang-kurangnya 6 aksara.',
        ]);

        $adminId = (int) $request->session()->get('admin_id');
        $admin = DB::table('admin')->select('password')->where('id', $adminId)->first();

        if (!$admin || !Hash::check($data['old_password'], $admin->password)) {
            return redirect()->route('admin.profile')->withErrors(['old_password' => 'Kata laluan lama tidak betul.']);
        }

        DB::table('admin')->where('id', $adminId)->update([
            'password' => Hash::make($data['new_password']),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.profile')->with('status', 'Kata laluan berjaya dikemas kini.');
    }

    public function profileUploadPhoto(Request $request): RedirectResponse
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.admin');
        }

        $request->validate([
            'profile_picture' => ['required', 'image', 'max:2048'],
        ]);

        /** @var UploadedFile $file */
        $file = $request->file('profile_picture');
        $adminId = (int) $request->session()->get('admin_id');

        $profilesDir = public_path('assets/profiles');
        if (!is_dir($profilesDir)) {
            mkdir($profilesDir, 0755, true);
        }

        $targetPath = $profilesDir.DIRECTORY_SEPARATOR.'admin_'.$adminId.'.jpg';
        $file->move($profilesDir, 'admin_'.$adminId.'.jpg');

        if (!file_exists($targetPath)) {
            return redirect()->route('admin.profile')->withErrors(['profile_picture' => 'Gagal memuat naik gambar profil.']);
        }

        return redirect()->route('admin.profile')->with('status', 'Gambar profil berjaya dimuat naik.');
    }

    public function adminCertificate(Request $request, int $staffId)
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.admin');
        }

        $staff = DB::table('staff_baru as sb')
            ->leftJoin('user_role_mapping as urm', function ($join) {
                $join->on('sb.id', '=', 'urm.new_user_id')->where('urm.role', '=', 'staff_baru');
            })
            ->leftJoin('user_orientations as uo', function ($join) {
                $join->on(DB::raw('COALESCE(urm.old_user_id, sb.id)'), '=', 'uo.user_id');
            })
            ->select(
                'sb.id',
                'sb.nama_penuh',
                DB::raw('COUNT(uo.id) as completion_count'),
                DB::raw('MAX(uo.completion_date) as max_completion_date')
            )
            ->where('sb.id', $staffId)
            ->groupBy('sb.id', 'sb.nama_penuh')
            ->first();

        if (!$staff) {
            return redirect()->route('admin.pengurusan-staff')->withErrors(['cert' => 'Staff tidak ditemui.']);
        }

        if ((int) $staff->completion_count < 11) {
            return redirect()->route('admin.pengurusan-staff')->withErrors(['cert' => 'Staff belum selesai semua unit — sijil tidak boleh dicetak.']);
        }

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Mac', 4 => 'April', 5 => 'Mei', 6 => 'Jun',
            7 => 'Julai', 8 => 'Ogos', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Disember',
        ];

        $date = $staff->max_completion_date
            ? \Carbon\Carbon::parse($staff->max_completion_date)
            : now();

        return view('staff.certificate', [
            'userName'  => (string) $staff->nama_penuh,
            'tarikh'    => $date->format('d').' '.$months[(int) $date->format('m')].' '.$date->format('Y'),
            'masa'      => $date->format('H:i'),
            'backUrl'   => route('admin.pengurusan-staff'),
        ]);
    }

    public function staffProgress(Request $request)
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.admin');
        }

        $params = $request->query('search') !== null
            ? ['search' => trim((string) $request->query('search', ''))]
            : [];

        return redirect()->route('admin.pengurusan-staff', $params);
    }

    public function staffProgressExportCsv(Request $request)
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.admin');
        }

        $params = $request->query('search') !== null
            ? ['search' => trim((string) $request->query('search', ''))]
            : [];

        return redirect()->route('admin.pengurusan-staff.export-csv', $params);
    }

    public function resetUnits(Request $request)
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.admin');
        }

        if (!$this->isSuperAdmin($request)) {
            return redirect()->route('admin.dashboard')->withErrors([
                'auth' => 'Hanya super admin dibenarkan akses fungsi ini.',
            ]);
        }

        $users = DB::table('staff_baru')
            ->select('id', 'nama_penuh', 'no_ic', 'unit_jabatan')
            ->orderBy('nama_penuh')
            ->get();

        $units = [
            'unit_kualiti' => 'UNIT KUALITI',
            'unit_pengurusan_maklumat' => 'UNIT PENGURUSAN MAKLUMAT',
            'unit_patologi_transfusi' => 'UNIT PATOLOGI & TRANSFUSI',
            'unit_kawalan_infeksi' => 'UNIT KAWALAN INFEKSI',
            'unit_rekod_perubatan' => 'UNIT REKOD PERUBATAN',
            'unit_farmasi' => 'UNIT FARMASI',
            'unit_kewangan' => 'UNIT KEWANGAN',
            'unit_perolehan_aset_stor' => 'UNIT PEROLEHAN, ASET & STOR',
            'unit_sumber_manusia' => 'UNIT SUMBER MANUSIA',
            'unit_keselamatan_kesihatan_pekerjaan' => 'UNIT KESELAMATAN & KESIHATAN PEKERJAAN',
            'unit_kejuruteraan' => 'UNIT KEJURUTERAAN',
        ];

        $statusMap = [];
        foreach ($users as $user) {
            $oldUserId = $this->oldUserIdFromNewStaffId((int) $user->id);
            $completedSlugs = DB::table('user_orientations')
                ->where('user_id', $oldUserId)
                ->pluck('unit_slug')
                ->map(fn ($s) => preg_replace('/\.php$/', '', (string) $s))
                ->all();
            $statusMap[$user->id] = array_flip(array_unique($completedSlugs));
        }

        return view('admin.reset-units', [
            'users' => $users,
            'units' => $units,
            'statusMap' => $statusMap,
        ]);
    }

    public function resetUnitsAction(Request $request): JsonResponse
    {
        if (!$this->isAuthorized($request)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        if (!$this->isSuperAdmin($request)) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'action' => ['required', 'string'],
            'user_id' => ['required', 'integer', 'min:1'],
            'unit_slug' => ['nullable', 'string'],
        ]);

        $newUserId = (int) $data['user_id'];
        $oldUserId = $this->oldUserIdFromNewStaffId($newUserId);
        $action = trim($data['action']);

        if ($action === 'reset_unit') {
            $unitSlug = preg_replace('/\.php$/', '', trim((string) ($data['unit_slug'] ?? '')));
            if ($unitSlug === '') {
                return response()->json(['success' => false, 'message' => 'Unit slug is required'], 422);
            }

            DB::table('user_orientations')->where('user_id', $oldUserId)->where('unit_slug', $unitSlug)->delete();
            DB::table('user_orientation_progress')->where('user_id', $oldUserId)->where('unit_slug', $unitSlug)->delete();

            return response()->json(['success' => true, 'message' => 'Unit reset berjaya']);
        }

        if ($action === 'reset_all') {
            DB::table('user_orientations')->where('user_id', $oldUserId)->delete();
            DB::table('user_orientation_progress')->where('user_id', $oldUserId)->delete();

            return response()->json(['success' => true, 'message' => 'Semua unit reset berjaya']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid action'], 422);
    }

    public function laporan(Request $request): RedirectResponse
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.admin');
        }

        return redirect()->route('admin.pengurusan-staff');
    }

    public function pengurusanStaff(Request $request)
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.admin');
        }

        $search = trim((string) $request->query('search', ''));
        $totalUnits = 11;
        $rows = $this->fetchPengurusanStaffRows($search, $totalUnits);

        return view('admin.pengurusan-staff', [
            'rows' => $rows,
            'search' => $search,
            'totalUnits' => $totalUnits,
        ]);
    }

    public function pengurusanStaffDeleteUser(Request $request): RedirectResponse
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.admin');
        }

        $data = $request->validate(['user_id' => ['required', 'integer', 'min:1']]);
        $userId = (int) $data['user_id'];

        DB::table('staff_baru')->where('id', $userId)->delete();

        return redirect()->route('admin.pengurusan-staff')->with('status', 'Pengguna staff berjaya dipadam.');
    }

    public function pengurusanStaffEditUser(Request $request): RedirectResponse
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.admin');
        }

        $data = $request->validate([
            'user_id' => ['required', 'integer', 'min:1'],
            'nama_penuh' => ['required', 'string', 'max:255'],
            'no_ic' => ['required', 'string', 'max:20'],
            'unit_jabatan' => ['required', 'string', 'max:255'],
            'jawatan' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        DB::table('staff_baru')->where('id', (int) $data['user_id'])->update([
            'nama_penuh' => trim($data['nama_penuh']),
            'no_ic' => trim($data['no_ic']),
            'unit_jabatan' => trim($data['unit_jabatan']),
            'jawatan' => trim($data['jawatan']),
            'email' => trim($data['email']),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.pengurusan-staff')->with('status', 'Maklumat staff berjaya dikemas kini.');
    }

    public function pengurusanStaffExportCsv(Request $request)
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.admin');
        }

        $search = trim($request->query('search', ''));
        $totalUnits = 11;
        $rows = $this->fetchPengurusanStaffRows($search, $totalUnits);

        // Generate CSV
        $filename = 'pengurusan_staff_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($rows, $totalUnits) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 support
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, ['Nama Penuh', 'No. IC', 'Unit/Jabatan', 'Jawatan', 'Email', 'Tarikh Daftar', 'Progress', 'Tarikh Selesai', 'Status']);
            
            // Data rows
            foreach ($rows as $row) {
                $tarikhDaftar = $row->staff_created_at ? \Carbon\Carbon::parse($row->staff_created_at)->format('d/m/Y H:i') : '-';
                fputcsv($file, [
                    $row->nama_penuh,
                    $row->no_ic,
                    $row->unit_jabatan,
                    $row->jawatan,
                    $row->email,
                    $tarikhDaftar,
                    $row->completion_count.'/'.$totalUnits,
                    $row->max_completion_date ? \Carbon\Carbon::parse($row->max_completion_date)->format('d/m/Y H:i') : '-',
                    $row->status
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function fetchPengurusanStaffRows(string $search, int $totalUnits)
    {
        $hasProgressTable = Schema::hasTable('user_orientation_progress');

        $query = DB::table('staff_baru as sb')
            ->leftJoin('user_role_mapping as urm', function ($join) {
                $join->on('sb.id', '=', 'urm.new_user_id')->where('urm.role', '=', 'staff_baru');
            })
            ->leftJoin('user_orientations as uo', function ($join) {
                $join->on(DB::raw('COALESCE(urm.old_user_id, sb.id)'), '=', 'uo.user_id');
            });

        if ($hasProgressTable) {
            $query->leftJoin('user_orientation_progress as uop', function ($join) {
                $join->on(DB::raw('COALESCE(urm.old_user_id, sb.id)'), '=', 'uop.user_id');
            });
        }

        $query = $query
            ->select([
                'sb.id',
                'sb.nama_penuh',
                'sb.no_ic',
                'sb.unit_jabatan',
                'sb.jawatan',
                'sb.email',
                'sb.created_at as staff_created_at',
                DB::raw('COUNT(DISTINCT uo.unit_slug) as completion_count'),
                $hasProgressTable
                    ? DB::raw('COUNT(DISTINCT uop.unit_slug) AS started')
                    : DB::raw('0 AS started'),
                DB::raw('MAX(uo.completion_date) as max_completion_date'),
            ])
            ->groupBy('sb.id', 'sb.nama_penuh', 'sb.no_ic', 'sb.unit_jabatan', 'sb.jawatan', 'sb.email', 'sb.created_at')
            ->orderBy('sb.nama_penuh');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('sb.nama_penuh', 'like', '%'.$search.'%')
                    ->orWhere('sb.no_ic', 'like', '%'.$search.'%')
                    ->orWhere('sb.unit_jabatan', 'like', '%'.$search.'%');
            });
        }

        return $query->get()->map(function ($row) use ($totalUnits) {
            $completed = (int) $row->completion_count;
            $started = (int) ($row->started ?? 0);

            if ($completed >= $totalUnits) {
                $status = 'SELESAI';
            } elseif ($completed === 0 && $started > 0) {
                $status = 'SEDANG BERJALAN';
            } elseif ($completed === 0) {
                $status = 'BELUM MULA';
            } else {
                $status = 'SEDANG BERJALAN';
            }

            $row->status = $status;
            return $row;
        });
    }

    public function pengurusanPenyelia(Request $request)
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.admin');
        }

        $search = trim((string) $request->query('search', ''));

        $query = DB::table('penyelia')
            ->select('id', 'nama_penuh', 'no_ic', 'unit_jabatan', 'jawatan', 'email', 'created_at')
            ->orderBy('nama_penuh');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama_penuh', 'like', '%'.$search.'%')
                    ->orWhere('no_ic', 'like', '%'.$search.'%')
                    ->orWhere('unit_jabatan', 'like', '%'.$search.'%');
            });
        }

        $rows = $query->get();
        $activeCount = (int) $rows->count();
        $inactiveCount = Schema::hasTable('penyelia_deleted')
            ? (int) DB::table('penyelia_deleted')->count()
            : 0;

        return view('admin.pengurusan-penyelia', [
            'rows' => $rows,
            'search' => $search,
            'activeCount' => $activeCount,
            'inactiveCount' => $inactiveCount,
        ]);
    }

    public function pengurusanPenyeliaEditUser(Request $request): RedirectResponse
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.admin');
        }

        $data = $request->validate([
            'user_id' => ['required', 'integer', 'min:1'],
            'nama_penuh' => ['required', 'string', 'max:255'],
            'no_ic' => ['required', 'string', 'max:20'],
            'unit_jabatan' => ['required', 'string', 'max:255'],
            'jawatan' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        DB::table('penyelia')->where('id', (int) $data['user_id'])->update([
            'nama_penuh' => trim($data['nama_penuh']),
            'no_ic' => trim($data['no_ic']),
            'unit_jabatan' => trim($data['unit_jabatan']),
            'jawatan' => trim($data['jawatan']),
            'email' => trim($data['email']),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.pengurusan-penyelia')->with('status', 'Maklumat penyelia berjaya dikemas kini.');
    }

    public function pengurusanPenyeliaDeleteUser(Request $request): RedirectResponse
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.admin');
        }

        $data = $request->validate(['user_id' => ['required', 'integer', 'min:1']]);
        $userId = (int) $data['user_id'];

        $penyelia = DB::table('penyelia')
            ->select('id', 'nama_penuh', 'no_ic', 'unit_jabatan', 'jawatan', 'email', 'password', 'created_at', 'updated_at')
            ->where('id', $userId)
            ->first();

        if (!$penyelia) {
            return redirect()->route('admin.pengurusan-penyelia')->withErrors([
                'status' => 'Akaun penyelia tidak ditemui.',
            ]);
        }

        if (Schema::hasTable('penyelia_deleted')) {
            DB::table('penyelia_deleted')->insert([
                'original_id' => (int) $penyelia->id,
                'nama_penuh' => (string) $penyelia->nama_penuh,
                'no_ic' => (string) $penyelia->no_ic,
                'unit_jabatan' => (string) $penyelia->unit_jabatan,
                'jawatan' => (string) $penyelia->jawatan,
                'email' => (string) $penyelia->email,
                'password' => (string) $penyelia->password,
                'created_at' => $penyelia->created_at,
                'updated_at' => $penyelia->updated_at,
                'deleted_at' => now(),
                'deleted_by_admin_id' => (int) $request->session()->get('admin_id'),
            ]);
        }

        DB::table('penyelia')->where('id', $userId)->delete();

        return redirect()->route('admin.pengurusan-penyelia')->with('status', 'Pengguna penyelia berjaya dipadam.');
    }

    public function pengurusanPenyeliaResetPassword(Request $request): RedirectResponse
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.admin');
        }

        $data = $request->validate(['user_id' => ['required', 'integer', 'min:1']]);
        $userId = (int) $data['user_id'];

        $exists = DB::table('penyelia')->where('id', $userId)->exists();
        if (!$exists) {
            return redirect()->route('admin.pengurusan-penyelia')->withErrors([
                'status' => 'Akaun penyelia tidak ditemui.',
            ]);
        }

        DB::table('penyelia')->where('id', $userId)->update([
            'password' => Hash::make('User000'),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.pengurusan-penyelia')->with('status', 'Kata laluan penyelia telah direset ke User000. Maklumkan penyelia untuk log masuk semula dan tukar kata laluan.');
    }

    public function pengurusanAdmin(Request $request)
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.admin');
        }

        $search = trim((string) $request->query('search', ''));
        $currentAdminId = (int) $request->session()->get('admin_id');
        $isSuperAdmin = $this->isSuperAdmin($request);

        $query = DB::table('admin')
            ->select('id', 'nama_penuh', 'no_ic', 'email', 'role', 'created_at')
            ->whereIn('role', [self::ROLE, self::SUPER_ADMIN_ROLE])
            ->orderBy('nama_penuh');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama_penuh', 'like', '%'.$search.'%')
                    ->orWhere('no_ic', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        return view('admin.pengurusan-admin', [
            'rows' => $query->get(),
            'search' => $search,
            'isSuperAdmin' => $isSuperAdmin,
            'currentAdminId' => $currentAdminId,
        ]);
    }

    public function adminUpdate(Request $request): RedirectResponse
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.admin');
        }

        if (!$this->isSuperAdmin($request)) {
            return redirect()->route('admin.pengurusan-admin')->withErrors([
                'auth' => 'Hanya super admin dibenarkan edit admin.',
            ]);
        }

        $data = $request->validate([
            'user_id' => ['required', 'integer', 'min:1'],
            'nama_penuh' => ['required', 'string', 'max:255'],
            'no_ic' => ['required', 'string', 'max:20', 'unique:admin,no_ic,'.(int) $request->input('user_id')],
            'email' => ['required', 'email', 'max:255', 'unique:admin,email,'.(int) $request->input('user_id')],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        $targetId = (int) $data['user_id'];
        $currentAdminId = (int) $request->session()->get('admin_id');
        $target = DB::table('admin')->select('id', 'no_ic')->where('id', $targetId)->first();

        if (!$target) {
            return redirect()->route('admin.pengurusan-admin')->withErrors([
                'auth' => 'Admin sasaran tidak ditemui.',
            ]);
        }

        if ($targetId === $currentAdminId || (string) $target->no_ic === self::SUPER_ADMIN_NO_IC) {
            return redirect()->route('admin.pengurusan-admin')->withErrors([
                'auth' => 'Super admin hanya boleh edit admin lain.',
            ]);
        }

        $updatePayload = [
            'nama_penuh' => trim((string) $data['nama_penuh']),
            'no_ic' => trim((string) $data['no_ic']),
            'email' => trim((string) $data['email']),
            'updated_at' => now(),
        ];

        if (!empty($data['password'])) {
            $updatePayload['password'] = Hash::make((string) $data['password']);
        }

        DB::table('admin')->where('id', $targetId)->update($updatePayload);

        return redirect()->route('admin.pengurusan-admin')->with('status', 'Maklumat admin berjaya dikemas kini.');
    }

    public function adminDelete(Request $request): RedirectResponse
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.admin');
        }

        if (!$this->isSuperAdmin($request)) {
            return redirect()->route('admin.pengurusan-admin')->withErrors([
                'auth' => 'Hanya super admin dibenarkan padam admin.',
            ]);
        }

        $data = $request->validate(['user_id' => ['required', 'integer', 'min:1']]);
        $targetId = (int) $data['user_id'];
        $currentAdminId = (int) $request->session()->get('admin_id');
        $target = DB::table('admin')->select('id', 'no_ic')->where('id', $targetId)->first();

        if (!$target) {
            return redirect()->route('admin.pengurusan-admin')->withErrors([
                'auth' => 'Admin sasaran tidak ditemui.',
            ]);
        }

        if ($targetId === $currentAdminId || (string) $target->no_ic === self::SUPER_ADMIN_NO_IC) {
            return redirect()->route('admin.pengurusan-admin')->withErrors([
                'auth' => 'Super admin hanya boleh padam admin lain.',
            ]);
        }

        DB::table('admin')->where('id', $targetId)->delete();

        return redirect()->route('admin.pengurusan-admin')->with('status', 'Admin berjaya dipadam.');
    }

    public function adminResetPassword(Request $request): RedirectResponse
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.admin');
        }

        if (!$this->isSuperAdmin($request)) {
            return redirect()->route('admin.pengurusan-admin')->withErrors([
                'auth' => 'Hanya super admin dibenarkan reset kata laluan admin.',
            ]);
        }

        $data = $request->validate(['user_id' => ['required', 'integer', 'min:1']]);
        $targetId = (int) $data['user_id'];
        $currentAdminId = (int) $request->session()->get('admin_id');
        $target = DB::table('admin')->select('id', 'no_ic')->where('id', $targetId)->first();

        if (!$target) {
            return redirect()->route('admin.pengurusan-admin')->withErrors([
                'auth' => 'Admin sasaran tidak ditemui.',
            ]);
        }

        if ($targetId === $currentAdminId || (string) $target->no_ic === self::SUPER_ADMIN_NO_IC) {
            return redirect()->route('admin.pengurusan-admin')->withErrors([
                'auth' => 'Super admin hanya boleh reset kata laluan admin lain.',
            ]);
        }

        DB::table('admin')->where('id', $targetId)->update([
            'password' => Hash::make('Adm1n@IT'),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.pengurusan-admin')->with('status', 'Kata laluan admin telah direset ke Adm1n@IT. Maklumkan admin untuk log masuk semula dan tukar kata laluan.');
    }

    public function adminPromoteSuper(Request $request): RedirectResponse
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.admin');
        }

        if (!$this->isSuperAdmin($request)) {
            return redirect()->route('admin.pengurusan-admin')->withErrors([
                'auth' => 'Hanya super admin dibenarkan ubah role admin.',
            ]);
        }

        $data = $request->validate(['user_id' => ['required', 'integer', 'min:1']]);
        $targetId = (int) $data['user_id'];
        $currentAdminId = (int) $request->session()->get('admin_id');
        $target = DB::table('admin')->select('id', 'role')->where('id', $targetId)->first();

        if (!$target) {
            return redirect()->route('admin.pengurusan-admin')->withErrors([
                'auth' => 'Admin sasaran tidak ditemui.',
            ]);
        }

        if ($targetId === $currentAdminId) {
            return redirect()->route('admin.pengurusan-admin')->withErrors([
                'auth' => 'Gunakan akaun lain untuk menukar role anda sendiri.',
            ]);
        }

        if ((string) $target->role === self::SUPER_ADMIN_ROLE) {
            return redirect()->route('admin.pengurusan-admin')->with('status', 'Admin ini sudah pun super admin.');
        }

        DB::table('admin')->where('id', $targetId)->update([
            'role' => self::SUPER_ADMIN_ROLE,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.pengurusan-admin')->with('status', 'Role admin berjaya ditukar kepada super admin.');
    }

    public function adminRemoveSuper(Request $request): RedirectResponse
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.admin');
        }

        if (!$this->isSuperAdmin($request)) {
            return redirect()->route('admin.pengurusan-admin')->withErrors([
                'auth' => 'Hanya super admin dibenarkan ubah role admin.',
            ]);
        }

        $data = $request->validate(['user_id' => ['required', 'integer', 'min:1']]);
        $targetId = (int) $data['user_id'];
        $currentAdminId = (int) $request->session()->get('admin_id');
        $target = DB::table('admin')->select('id', 'no_ic', 'role')->where('id', $targetId)->first();

        if (!$target) {
            return redirect()->route('admin.pengurusan-admin')->withErrors([
                'auth' => 'Admin sasaran tidak ditemui.',
            ]);
        }

        if ($targetId === $currentAdminId) {
            return redirect()->route('admin.pengurusan-admin')->withErrors([
                'auth' => 'Anda tidak boleh turunkan role akaun sendiri.',
            ]);
        }

        if ((string) $target->no_ic === self::SUPER_ADMIN_NO_IC) {
            return redirect()->route('admin.pengurusan-admin')->withErrors([
                'auth' => 'Akaun super admin utama tidak boleh diturunkan role.',
            ]);
        }

        if ((string) $target->role !== self::SUPER_ADMIN_ROLE) {
            return redirect()->route('admin.pengurusan-admin')->with('status', 'Akaun ini bukan super admin.');
        }

        DB::table('admin')->where('id', $targetId)->update([
            'role' => self::ROLE,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.pengurusan-admin')->with('status', 'Role super admin berjaya diturunkan kepada admin.');
    }

    public function completeUnits(Request $request)
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.admin');
        }

        if (!$this->isSuperAdmin($request)) {
            return redirect()->route('admin.dashboard')->withErrors([
                'auth' => 'Hanya super admin dibenarkan akses fungsi ini.',
            ]);
        }

        $users = DB::table('staff_baru')
            ->select('id', 'nama_penuh', 'no_ic', 'unit_jabatan')
            ->orderBy('nama_penuh')
            ->get();

        $units = [
            'unit_kewangan' => 'UNIT KEWANGAN',
            'unit_sumber_manusia' => 'UNIT SUMBER MANUSIA',
            'unit_perolehan_aset_stor' => 'UNIT PEROLEHAN, ASET & STOR',
            'unit_pengurusan_maklumat' => 'UNIT PENGURUSAN MAKLUMAT',
            'unit_rekod_perubatan' => 'UNIT REKOD PERUBATAN',
            'unit_kualiti' => 'UNIT KUALITI',
            'unit_patologi_transfusi' => 'UNIT PATOLOGI & TRANSFUSI',
            'unit_farmasi' => 'UNIT FARMASI',
            'unit_keselamatan_kesihatan_pekerjaan' => 'UNIT KESELAMATAN & KESIHATAN PEKERJAAN',
            'unit_kawalan_infeksi' => 'UNIT KAWALAN INFEKSI',
            'unit_kejuruteraan' => 'UNIT KEJURUTERAAN',
        ];

        $statusMap = [];
        foreach ($users as $user) {
            $oldUserId = $this->oldUserIdFromNewStaffId((int) $user->id);
            $completedSlugs = DB::table('user_orientations')
                ->where('user_id', $oldUserId)
                ->pluck('unit_slug')
                ->map(fn ($s) => preg_replace('/\.php$/', '', (string) $s))
                ->all();
            $statusMap[$user->id] = array_flip(array_unique($completedSlugs));
        }

        return view('admin.complete-units', [
            'users' => $users,
            'units' => $units,
            'statusMap' => $statusMap,
        ]);
    }

    public function completeUnitsAction(Request $request): JsonResponse
    {
        if (!$this->isAuthorized($request)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        if (!$this->isSuperAdmin($request)) {
            return response()->json(['success' => false, 'message' => 'Only super admin is allowed'], 403);
        }

        $data = $request->validate([
            'action' => ['required', 'string'],
            'user_id' => ['required', 'integer', 'min:1'],
            'unit_slug' => ['nullable', 'string'],
        ]);

        $newUserId = (int) $data['user_id'];
        $oldUserId = $this->oldUserIdFromNewStaffId($newUserId);
        $action = trim($data['action']);

        if ($action === 'complete_unit') {
            $unitSlug = preg_replace('/\.php$/', '', trim((string) ($data['unit_slug'] ?? '')));
            if ($unitSlug === '') {
                return response()->json(['success' => false, 'message' => 'Unit slug is required'], 422);
            }

            $exists = DB::table('user_orientations')
                ->where('user_id', $oldUserId)
                ->where('unit_slug', $unitSlug)
                ->exists();

            if (!$exists) {
                try {
                    DB::table('user_orientations')->insert([
                        'user_id' => $oldUserId,
                        'unit_slug' => $unitSlug,
                        'is_verified' => 0,
                        'completion_date' => now(),
                        'completed_at' => now(),
                    ]);
                } catch (\Throwable $exception) {
                    DB::table('user_orientations')->insert([
                        'user_id' => $oldUserId,
                        'unit_slug' => $unitSlug,
                        'is_verified' => 0,
                    ]);
                }
            }

            return response()->json(['success' => true, 'message' => 'Unit ditandakan sebagai selesai']);
        }

        if ($action === 'complete_all') {
            $allUnits = [
                'unit_kewangan', 'unit_sumber_manusia', 'unit_perolehan_aset_stor',
                'unit_pengurusan_maklumat', 'unit_rekod_perubatan', 'unit_kualiti',
                'unit_patologi_transfusi', 'unit_farmasi', 'unit_keselamatan_kesihatan_pekerjaan',
                'unit_kawalan_infeksi', 'unit_kejuruteraan',
            ];

            $existing = DB::table('user_orientations')
                ->where('user_id', $oldUserId)
                ->pluck('unit_slug')
                ->map(fn ($s) => preg_replace('/\.php$/', '', (string) $s))
                ->all();

            $toInsert = array_diff($allUnits, $existing);

            foreach ($toInsert as $slug) {
                try {
                    DB::table('user_orientations')->insert([
                        'user_id' => $oldUserId,
                        'unit_slug' => $slug,
                        'is_verified' => 0,
                        'completion_date' => now(),
                        'completed_at' => now(),
                    ]);
                } catch (\Throwable $exception) {
                    DB::table('user_orientations')->insert([
                        'user_id' => $oldUserId,
                        'unit_slug' => $slug,
                        'is_verified' => 0,
                    ]);
                }
            }

            return response()->json(['success' => true, 'message' => 'Semua unit ditandakan sebagai selesai']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid action'], 422);
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function isAuthorized(Request $request): bool
    {
        return $request->session()->has('admin_id') && $this->isAdminRole((string) $request->session()->get('role'));
    }

    private function isAdminRole(string $role): bool
    {
        return in_array($role, [self::ROLE, self::SUPER_ADMIN_ROLE], true);
    }

    private function isSuperAdmin(Request $request): bool
    {
        return $request->session()->has('admin_id')
            && $request->session()->get('role') === self::SUPER_ADMIN_ROLE;
    }

    private function resolveAdminRoleByNoIc(string $noIc): string
    {
        return trim($noIc) === self::SUPER_ADMIN_NO_IC ? self::SUPER_ADMIN_ROLE : self::ROLE;
    }

    private function syncPrimarySuperAdmin(): void
    {
        DB::table('admin')
            ->where('no_ic', self::SUPER_ADMIN_NO_IC)
            ->where('role', '!=', self::SUPER_ADMIN_ROLE)
            ->update([
                'role' => self::SUPER_ADMIN_ROLE,
                'updated_at' => now(),
            ]);
    }

    private function oldUserIdFromNewStaffId(int $newUserId): int
    {
        $mapped = DB::table('user_role_mapping')
            ->where('new_user_id', $newUserId)
            ->where('role', 'staff_baru')
            ->value('old_user_id');

        return $mapped ? (int) $mapped : $newUserId;
    }

    private function profilePicturePath(int $adminId): string
    {
        $relativePath = 'assets/profiles/admin_'.$adminId.'.jpg';
        $absolutePath = public_path($relativePath);

        if (file_exists($absolutePath)) {
            return asset($relativePath);
        }

        return asset('assets/logo1.png');
    }

    private function getSlideFiles(string $folder): array
    {
        $path = public_path('assets/slide_images/'.$folder);
        if (!File::exists($path)) {
            return [];
        }

        $files = collect(File::files($path))
            ->map(fn ($file) => $file->getFilename())
            ->filter(fn ($name) => preg_match('/^Slide\d+\.(png|PNG|jpg|JPG|jpeg|JPEG)$/', $name) === 1)
            ->values()
            ->all();

        usort($files, function (string $a, string $b): int {
            preg_match('/Slide(\d+)/i', $a, $ma);
            preg_match('/Slide(\d+)/i', $b, $mb);
            return ((int) ($ma[1] ?? 0)) <=> ((int) ($mb[1] ?? 0));
        });

        return $files;
    }

    private function renumberSlideFiles(string $folder): void
    {
        $path = public_path('assets/slide_images/'.$folder);
        if (!File::exists($path)) {
            return;
        }

        $files = $this->getSlideFiles($folder);
        if (count($files) === 0) {
            return;
        }

        $tempMoves = [];

        foreach ($files as $index => $filename) {
            $targetNumber = $index + 1;
            if (preg_match('/^Slide(\d+)\.(png|jpg|jpeg)$/i', $filename, $match) !== 1) {
                continue;
            }

            $currentNumber = (int) $match[1];
            $extension = strtolower((string) $match[2]);

            if ($currentNumber === $targetNumber) {
                continue;
            }

            $sourcePath = $path.DIRECTORY_SEPARATOR.$filename;
            $tempName = '__tmp_slide_'.$targetNumber.'_'.uniqid('', true).'.'.$extension;
            $tempPath = $path.DIRECTORY_SEPARATOR.$tempName;

            File::move($sourcePath, $tempPath);
            $tempMoves[] = [
                'temp' => $tempPath,
                'final' => $path.DIRECTORY_SEPARATOR.'Slide'.$targetNumber.'.'.$extension,
            ];
        }

        foreach ($tempMoves as $move) {
            File::move($move['temp'], $move['final']);
        }
    }
}
