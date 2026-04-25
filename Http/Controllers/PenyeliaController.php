<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PenyeliaController extends Controller
{
    private const ROLE = 'penyelia';

    public function showRegister()
    {
        return view('penyelia.register', ['unitOptions' => $this->unitOptions()]);
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

        $noIcExists = DB::table('penyelia')->where('no_ic', $data['no_ic'])->exists()
            || DB::table('staff_baru')->where('no_ic', $data['no_ic'])->exists();

        if ($noIcExists) {
            return back()->withErrors(['no_ic' => 'No. IC ini sudah didaftarkan.'])->withInput();
        }

        $emailExists = DB::table('penyelia')->where('email', $data['email'])->exists()
            || DB::table('staff_baru')->where('email', $data['email'])->exists();

        if ($emailExists) {
            return back()->withErrors(['email' => 'Email ini sudah didaftarkan.'])->withInput();
        }

        DB::table('penyelia')->insert([
            'nama_penuh' => trim($data['nama_penuh']),
            'no_ic' => trim($data['no_ic']),
            'unit_jabatan' => trim($data['unit_jabatan']),
            'jawatan' => trim($data['jawatan']),
            'email' => trim($data['email']),
            'password' => Hash::make('User000'),
            'role' => self::ROLE,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('register.penyelia')->with('success', 'Pendaftaran berjaya! Kata laluan default anda: User000. Sila log masuk.');
    }

    public function showLogin()
    {
        if (session('role') === self::ROLE && session('penyelia_id')) {
            return redirect()->route('penyelia.dashboard');
        }

        return view('penyelia.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'no_ic' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string'],
        ], [
            'required' => 'Sila isi semua medan.',
        ]);

        $penyelia = DB::table('penyelia')
            ->select('id', 'nama_penuh', 'no_ic', 'password', 'role')
            ->where('no_ic', trim($credentials['no_ic']))
            ->first();

        if (!$penyelia) {
            return back()->withErrors(['no_ic' => 'No. IC tidak dijumpai atau bukan akaun Penyelia.'])->withInput();
        }

        if ($penyelia->role !== self::ROLE) {
            return back()->withErrors(['no_ic' => 'Akaun ini bukan untuk Penyelia.'])->withInput();
        }

        if (!Hash::check($credentials['password'], $penyelia->password)) {
            return back()->withErrors(['password' => 'Kata laluan tidak sah.'])->withInput();
        }

        $request->session()->regenerate();
        $request->session()->put([
            'user_id' => $penyelia->id,
            'penyelia_id' => $penyelia->id,
            'nama_penuh' => $penyelia->nama_penuh,
            'no_ic' => $penyelia->no_ic,
            'role' => $penyelia->role,
        ]);

        return redirect()->route('penyelia.dashboard');
    }

    public function dashboard(Request $request)
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.penyelia');
        }

        $penyeliaId = (int) $request->session()->get('penyelia_id');
        $penyeliaName = (string) $request->session()->get('nama_penuh', 'Penyelia');

        $unitJabatan = (string) DB::table('penyelia')->where('id', $penyeliaId)->value('unit_jabatan');
        $unitSlug = $this->unitSlugByDepartment($unitJabatan);

        $staffRows = DB::table('staff_baru as sb')
            ->leftJoin('user_role_mapping as urm', function ($join) {
                $join->on('urm.new_user_id', '=', 'sb.id')->where('urm.role', '=', 'staff_baru');
            })
            ->leftJoin('user_orientations as uo', function ($join) use ($unitSlug) {
                $join->on(DB::raw('COALESCE(urm.old_user_id, sb.id)'), '=', 'uo.user_id')
                    ->where('uo.unit_slug', '=', $unitSlug);
            })
            ->select(
                'sb.id', 'sb.nama_penuh', 'sb.no_ic', 'sb.unit_jabatan',
                DB::raw('COALESCE(urm.old_user_id, sb.id) as old_user_id'),
                'uo.id as orientation_id', 'uo.is_verified', 'uo.completion_date', 'uo.verified_at'
            )
            ->orderBy('sb.nama_penuh')
            ->get();

        $totalStaff = $staffRows->count();
        $completedCount = $staffRows->filter(fn ($row) => $row->orientation_id !== null)->count();
        $verifiedCount = $staffRows->filter(fn ($row) => $row->orientation_id !== null && (int) $row->is_verified === 1)->count();
        $notCompletedCount = $totalStaff - $completedCount;

        return view('penyelia.dashboard', [
            'penyeliaName' => $penyeliaName,
            'unitJabatan' => $unitJabatan,
            'unitSlug' => $unitSlug,
            'staffRows' => $staffRows,
            'totalStaff' => $totalStaff,
            'completedCount' => $completedCount,
            'verifiedCount' => $verifiedCount,
            'notCompletedCount' => $notCompletedCount,
            'completionPercentage' => $totalStaff > 0 ? round(($completedCount / $totalStaff) * 100) : 0,
            'verificationPercentage' => $totalStaff > 0 ? round(($verifiedCount / $totalStaff) * 100) : 0,
        ]);
    }

    public function verify(Request $request): JsonResponse
    {
        if (!$this->isAuthorized($request)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'old_user_id' => ['required', 'integer', 'min:1'],
            'unit_slug' => ['required', 'string'],
        ]);

        $penyeliaId = (int) $request->session()->get('penyelia_id');
        $oldUserId = (int) $data['old_user_id'];
        $unitSlug = trim($data['unit_slug']);

        $affectedRows = 0;

        try {
            $affectedRows = DB::table('user_orientations')
                ->where('user_id', $oldUserId)
                ->where('unit_slug', $unitSlug)
                ->where('is_verified', 0)
                ->update([
                    'is_verified' => 1,
                    'verified_by' => $penyeliaId,
                    'verified_at' => now(),
                ]);
        } catch (\Throwable $exception) {
            $affectedRows = DB::table('user_orientations')
                ->where('user_id', $oldUserId)
                ->where('unit_slug', $unitSlug)
                ->where('is_verified', 0)
                ->update([
                    'is_verified' => 1,
                    'verified_at' => now(),
                ]);
        }

        if ($affectedRows > 0) {
            return response()->json(['success' => true, 'message' => 'Orientasi berjaya disahkan']);
        }

        return response()->json(['success' => false, 'message' => 'Tiada rekod untuk dikemaskini atau sudah disahkan']);
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
        return $request->session()->has('penyelia_id') && $request->session()->get('role') === self::ROLE;
    }

    private function unitSlugByDepartment(string $unitJabatan): string
    {
        $map = [
            'Unit Kewangan' => 'unit_kewangan',
            'Unit Sumber Manusia' => 'unit_sumber_manusia',
            'Unit Kejururawatan' => 'unit_kejururawatan',
            'Unit Kejuruteraan' => 'unit_kejuruteraan',
            'Unit Rekod Perubatan' => 'unit_rekod_perubatan',
            'Unit Farmasi' => 'unit_farmasi',
            'Unit Kualiti' => 'unit_kualiti',
            'Unit Kawalan Infeksi' => 'unit_kawalan_infeksi',
            'Unit Keselamatan Kesihatan Pekerjaan' => 'unit_keselamatan_kesihatan_pekerjaan',
            'Unit Pengurusan Maklumat' => 'unit_pengurusan_maklumat',
            'Unit Patologi & Transfusi' => 'unit_patologi_transfusi',
            'Unit Perolehan Aset Stor' => 'unit_perolehan_aset_stor',
            'Unit Penyeliaan' => 'unit_penyeliaan',
        ];

        if (isset($map[$unitJabatan])) {
            return $map[$unitJabatan];
        }

        $normalized = strtolower($unitJabatan);
        $normalized = str_replace('&', '', $normalized);
        $normalized = preg_replace('/[^a-z0-9]+/', '_', $normalized);

        return trim((string) $normalized, '_');
    }

    private function unitOptions(): array
    {
        return [
            'Unit Kewangan',
            'Unit Sumber Manusia',
            'Unit Kejururawatan',
            'Unit Kejuruteraan',
            'Unit Rekod Perubatan',
            'Unit Farmasi',
            'Unit Kualiti',
            'Unit Kawalan Infeksi',
            'Unit Keselamatan Kesihatan Pekerjaan',
            'Unit Pengurusan Maklumat',
            'Unit Patologi & Transfusi',
            'Unit Perolehan Aset Stor',
            'Unit Penyeliaan',
        ];
    }
}
