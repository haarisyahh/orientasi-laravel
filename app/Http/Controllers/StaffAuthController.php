<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class StaffAuthController extends Controller
{
    private const ROLE_STAFF = 'staff_baru';

    private const DASHBOARD_UNITS = [
        ['label' => 'UNIT KEWANGAN', 'slug' => 'unit_kewangan'],
        ['label' => 'UNIT SUMBER MANUSIA', 'slug' => 'unit_sumber_manusia'],
        ['label' => 'UNIT PEROLEHAN, ASET & STOR', 'slug' => 'unit_perolehan_aset_stor'],
        ['label' => 'UNIT PENGURUSAN MAKLUMAT', 'slug' => 'unit_pengurusan_maklumat'],
        ['label' => 'UNIT REKOD PERUBATAN', 'slug' => 'unit_rekod_perubatan'],
        ['label' => 'UNIT KUALITI', 'slug' => 'unit_kualiti'],
        ['label' => 'UNIT PATOLOGI & TRANSFUSI', 'slug' => 'unit_patologi_transfusi'],
        ['label' => 'UNIT KESELAMATAN & KESIHATAN PEKERJAAN', 'slug' => 'unit_keselamatan_kesihatan_pekerjaan'],
        ['label' => 'UNIT FARMASI', 'slug' => 'unit_farmasi'],
        ['label' => 'UNIT KAWALAN INFEKSI', 'slug' => 'unit_kawalan_infeksi'],
        ['label' => 'UNIT KEJURUTERAAN', 'slug' => 'unit_kejuruteraan'],
    ];

    public function showRegister()
    {
        return view('auth.staff-register', [
            'unitOptions' => $this->unitOptions(),
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'nama_penuh' => ['required', 'string', 'max:255'],
            'no_ic' => ['required', 'string', 'max:20'],
            'unit_jabatan' => ['required', 'string', 'max:255'],
            'jawatan' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ], [
            'required' => 'Semua medan adalah wajib diisi.',
            'email.email' => 'Format email tidak sah.',
        ]);

        $noIcExists = DB::table('staff_baru')->where('no_ic', $data['no_ic'])->exists();

        if ($noIcExists) {
            return back()
                ->withErrors(['no_ic' => 'No. IC ini sudah didaftarkan.'])
                ->withInput();
        }

        $emailExists = DB::table('staff_baru')->where('email', $data['email'])->exists();

        if ($emailExists) {
            return back()
                ->withErrors(['email' => 'Email ini sudah didaftarkan.'])
                ->withInput();
        }

        DB::table('staff_baru')->insert([
            'nama_penuh' => trim($data['nama_penuh']),
            'no_ic' => trim($data['no_ic']),
            'unit_jabatan' => trim($data['unit_jabatan']),
            'jawatan' => trim($data['jawatan']),
            'email' => trim($data['email']),
            'password' => Hash::make('User123'),
            'role' => self::ROLE_STAFF,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('register.staff')
            ->with('success', 'Pendaftaran berjaya! Kata laluan default anda: User123. Sila log masuk.');
    }

    public function showLogin()
    {
        if (session('user_id') && session('role') === self::ROLE_STAFF) {
            return redirect()->route('staff.dashboard');
        }

        return view('auth.staff-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'no_ic' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string'],
        ], [
            'required' => 'Sila isi semua medan.',
        ]);

        $staff = DB::table('staff_baru')
            ->select('id', 'nama_penuh', 'no_ic', 'password', 'role')
            ->where('no_ic', trim($credentials['no_ic']))
            ->first();

        if (!$staff) {
            return back()->withErrors([
                'no_ic' => 'No. IC tidak dijumpai atau bukan akaun Staff Baru. Sila gunakan halaman log masuk yang betul.',
            ])->withInput();
        }

        if ($staff->role !== self::ROLE_STAFF) {
            return back()->withErrors([
                'no_ic' => 'Akaun ini bukan untuk Staff Baru. Sila gunakan halaman log masuk yang betul.',
            ])->withInput();
        }

        if (!Hash::check($credentials['password'], $staff->password)) {
            return back()->withErrors([
                'password' => 'Kata laluan tidak sah.',
            ])->withInput();
        }

        $mapping = DB::table('user_role_mapping')
            ->select('old_user_id')
            ->where('new_user_id', $staff->id)
            ->where('role', self::ROLE_STAFF)
            ->first();

        $request->session()->regenerate();
        $request->session()->put([
            'user_id' => $mapping?->old_user_id ?: $staff->id,
            'staff_id' => $staff->id,
            'nama_penuh' => $staff->nama_penuh,
            'no_ic' => $staff->no_ic,
            'role' => $staff->role,
        ]);

        return redirect()->route('staff.dashboard');
    }

    public function dashboard(Request $request)
    {
        if (!$request->session()->has('user_id') || $request->session()->get('role') !== self::ROLE_STAFF) {
            return redirect()->route('login.staff');
        }

        $oldUserId = (int) $request->session()->get('user_id');
        $completedUnits = $this->fetchCompletedUnits($oldUserId);

        return view('staff.dashboard', [
            'units' => self::DASHBOARD_UNITS,
            'completedUnits' => $completedUnits,
            'staffName' => (string) $request->session()->get('nama_penuh', ''),
            'unitRouteTemplate' => route('staff.unit.view', ['unitSlug' => '__UNIT__']),
        ]);
    }

    public function completionStatus(Request $request): JsonResponse
    {
        if (!$request->session()->has('user_id') || $request->session()->get('role') !== self::ROLE_STAFF) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $oldUserId = (int) $request->session()->get('user_id');
        $completedUnits = $this->fetchCompletedUnits($oldUserId);

        return response()->json([
            'success' => true,
            'completed_units' => $completedUnits,
            'total' => count($completedUnits),
        ]);
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function unitOptions(): array
    {
        return [
            'ASET', 'CARA KERJA', 'CSSD/CSSU', 'FARMASI DAN BEKALAN', 'FARMASI LOGISTIK', 'FISIOTERAPI',
            'HASIL', 'HDU', 'JPL', 'KAWALAN INFEKSI', 'KECEMASAN DAN TRAUMA', 'KEJURURAWATAN',
            'KEJURUTERAAN', 'KESELAMATAN', 'KEWANGAN', 'KLINIK PAKAR', 'KUALITI', 'LATIHAN', 'NEFROLOGI',
            'OBSTETRIK DAN GINEKOLOGI', 'OT/CSSU', 'PATOLOGI', 'PEDIATRIK', 'PENGIMEJAN DIAGNOSTIK',
            'PENGURUSAN', 'PENTADBIRAN', 'PENYELIA HOSPITAL', 'PENYELIAAN JURURAWAT', 'PERUBATAN',
            'PERUBATAN RESPIRATORI', 'PESAKIT AM DALAM', 'PUSAT SUMBER', 'REHABILITASI',
            'REKOD PERUBATAN', 'SAJIAN', 'SUMBER MANUSIA', 'TABUNG DARAH', 'PENGURUSAN MAKLUMAT',
            'TIADA JABATAN', 'UKI', 'UKKP', 'UNIT A&E', 'UNIT PESAKIT LUAR', 'UPL', 'WAD 1', 'WAD 2',
            'WAD 3', 'WAD 4 & 5', 'WAD 6 & 7', 'WAD MELOR 1', 'WAD MELOR 2', 'WAD MELOR 3',
        ];
    }

    private function fetchCompletedUnits(int $oldUserId): array
    {
        $rows = DB::table('user_orientations')
            ->select('unit_slug', 'is_verified')
            ->where('user_id', $oldUserId)
            ->get();

        $completed = [];

        foreach ($rows as $row) {
            $slug = preg_replace('/\.php$/', '', (string) $row->unit_slug);
            if ($slug === 'unit_patologi_transfusi' && (int) $row->is_verified !== 1) {
                continue;
            }
            $completed[] = $slug;
        }

        return array_values(array_unique($completed));
    }
}
