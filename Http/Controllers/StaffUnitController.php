<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class StaffUnitController extends Controller
{
    private const ROLE_STAFF = 'staff_baru';

    private const UNIT_CONFIG = [
        'unit_kewangan' => ['label' => 'UNIT KEWANGAN', 'folder' => 'kewangan', 'has_slides' => true, 'enabled' => true],
        'unit_sumber_manusia' => ['label' => 'UNIT SUMBER MANUSIA', 'folder' => 'sumber manusia', 'has_slides' => true, 'enabled' => true],
        'unit_perolehan_aset_stor' => ['label' => 'UNIT PEROLEHAN, ASET & STOR', 'folder' => 'perolehan,aset&stor', 'has_slides' => true, 'enabled' => true],
        'unit_pengurusan_maklumat' => ['label' => 'UNIT PENGURUSAN MAKLUMAT', 'folder' => 'pengurusan_maklumat', 'has_slides' => true, 'enabled' => true],
        'unit_rekod_perubatan' => ['label' => 'UNIT REKOD PERUBATAN', 'folder' => 'rekod_perubatan', 'has_slides' => true, 'enabled' => true],
        'unit_kualiti' => ['label' => 'UNIT KUALITI', 'folder' => 'kualiti', 'has_slides' => true, 'enabled' => true],
        'unit_patologi_transfusi' => ['label' => 'UNIT PATOLOGI & TRANSFUSI', 'folder' => 'patologi&transfusi', 'has_slides' => true, 'enabled' => true],
        'unit_keselamatan_kesihatan_pekerjaan' => ['label' => 'UNIT KESELAMATAN & KESIHATAN PEKERJAAN', 'folder' => 'ukkp', 'has_slides' => true, 'enabled' => true],
        'unit_farmasi' => ['label' => 'UNIT FARMASI', 'folder' => 'farmasi', 'has_slides' => true, 'enabled' => true],
        'unit_kawalan_infeksi' => ['label' => 'UNIT KAWALAN INFEKSI', 'folder' => 'kawalan infeksi', 'has_slides' => true, 'enabled' => true],
        'unit_kejuruteraan' => ['label' => 'UNIT KEJURUTERAAN', 'folder' => 'Kejuruteraan', 'has_slides' => true, 'enabled' => true],
        'unit_kejururawatan' => ['label' => 'UNIT KEJURURAWATAN', 'folder' => null, 'has_slides' => false, 'enabled' => false],
        'unit_penyeliaan' => ['label' => 'UNIT PENYELIAAN', 'folder' => null, 'has_slides' => false, 'enabled' => false],
    ];

    public function view(Request $request, string $unitSlug)
    {
        if (!$this->isAuthorized($request)) {
            return redirect()->route('login.staff');
        }

        $config = self::UNIT_CONFIG[$unitSlug] ?? null;
        if (!$config) {
            abort(404);
        }

        $hasSlides = (bool) ($config['has_slides'] ?? true) && !empty($config['folder']);
        $slideFiles = $hasSlides ? $this->getSlideFiles((string) $config['folder']) : [];
        $hasSlides = $hasSlides && count($slideFiles) > 0;
        $totalSlides = $hasSlides ? (count($slideFiles) + 1) : 1;
        if ($unitSlug === 'unit_perolehan_aset_stor' && $hasSlides) {
            $totalSlides = max(19, $totalSlides);
        }
        $currentSlide = (int) $request->query('slide', 1);
        $currentSlide = max(1, min($currentSlide, $totalSlides));

        $currentFile = $slideFiles[$currentSlide - 1] ?? null;
        $slideImageUrl = $currentFile
            ? asset('assets/slide_images/'.$config['folder'].'/'.$currentFile)
            : null;

        return view('staff.unit-layout', [
            'unitSlug' => $unitSlug,
            'unitTitle' => $config['label'],
            'pageTitle' => 'PROGRAM ORIENTASI STAFF HOSPITAL BALING ('.$config['label'].')',
            'units' => self::UNIT_CONFIG,
            'activeUnits' => array_filter(self::UNIT_CONFIG, fn (array $unit): bool => (bool) ($unit['enabled'] ?? true)),
            'currentSlide' => $currentSlide,
            'totalSlides' => $totalSlides,
            'hasSlides' => $hasSlides,
            'slideImageUrl' => $slideImageUrl,
        ]);
    }

    public function getProgress(Request $request): JsonResponse
    {
        if (!$this->isAuthorized($request)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $unitSlug = trim((string) $request->query('unit', ''));
        if (!$this->isValidUnit($unitSlug)) {
            return response()->json(['success' => false, 'message' => 'Unit slug is required'], 400);
        }

        $this->ensureProgressTable();

        $row = DB::table('user_orientation_progress')
            ->select('viewed_slides', 'last_slide', 'total_slides')
            ->where('user_id', $this->oldUserId($request))
            ->where('unit_slug', $unitSlug)
            ->first();

        $viewedSlides = [];
        $lastSlide = null;
        $totalSlides = null;

        if ($row) {
            $decoded = json_decode((string) $row->viewed_slides, true);
            if (is_array($decoded)) {
                $viewedSlides = array_values(array_unique(array_map('intval', $decoded)));
            }
            $lastSlide = $row->last_slide !== null ? (int) $row->last_slide : null;
            $totalSlides = $row->total_slides !== null ? (int) $row->total_slides : null;
        }

        return response()->json([
            'success' => true,
            'unit' => $unitSlug,
            'viewed_slides' => $viewedSlides,
            'last_slide' => $lastSlide,
            'total_slides' => $totalSlides,
        ]);
    }

    public function saveProgress(Request $request): JsonResponse
    {
        if (!$this->isAuthorized($request)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $payload = $request->validate([
            'unit_slug' => ['required', 'string'],
            'viewed_slides' => ['nullable', 'array'],
            'viewed_slides.*' => ['integer'],
            'last_slide' => ['nullable', 'integer'],
            'total_slides' => ['nullable', 'integer'],
        ]);

        $unitSlug = trim($payload['unit_slug']);
        if (!$this->isValidUnit($unitSlug)) {
            return response()->json(['success' => false, 'message' => 'Unit slug is required'], 400);
        }

        $this->ensureProgressTable();

        $viewed = array_values(array_unique(array_filter(
            array_map('intval', (array) ($payload['viewed_slides'] ?? [])),
            fn (int $n): bool => $n > 0
        )));

        DB::table('user_orientation_progress')->updateOrInsert(
            [
                'user_id' => $this->oldUserId($request),
                'unit_slug' => $unitSlug,
            ],
            [
                'viewed_slides' => json_encode($viewed),
                'last_slide' => $payload['last_slide'] ?? null,
                'total_slides' => $payload['total_slides'] ?? null,
                'updated_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'unit' => $unitSlug,
            'viewed_count' => count($viewed),
        ]);
    }

    public function checkCompletion(Request $request): JsonResponse
    {
        if (!$this->isAuthorized($request)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $unitSlug = trim((string) $request->query('unit', ''));
        if (!$this->isValidUnit($unitSlug)) {
            return response()->json(['success' => false, 'message' => 'Unit slug is required'], 400);
        }

        $row = DB::table('user_orientations')
            ->select('is_verified', 'completion_date', 'verified_at')
            ->where('user_id', $this->oldUserId($request))
            ->where('unit_slug', $unitSlug)
            ->first();

        return response()->json([
            'success' => true,
            'unit' => $unitSlug,
            'is_completed' => (bool) $row,
            'is_verified' => $row ? (int) $row->is_verified === 1 : false,
            'completion_date' => $row->completion_date ?? null,
            'verified_at' => $row->verified_at ?? null,
        ]);
    }

    public function saveCompletion(Request $request): JsonResponse
    {
        if (!$this->isAuthorized($request)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $payload = $request->validate([
            'unit_slug' => ['required', 'string'],
        ]);

        $unitSlug = trim($payload['unit_slug']);
        if (!$this->isValidUnit($unitSlug)) {
            return response()->json(['success' => false, 'message' => 'Unit slug is required'], 400);
        }

        $userId = $this->oldUserId($request);
        $exists = DB::table('user_orientations')
            ->where('user_id', $userId)
            ->where('unit_slug', $unitSlug)
            ->exists();

        if (!$exists) {
            try {
                DB::table('user_orientations')->insert([
                    'user_id' => $userId,
                    'unit_slug' => $unitSlug,
                    'is_verified' => 0,
                    'completion_date' => now(),
                    'completed_at' => now(),
                ]);
            } catch (\Throwable $exception) {
                DB::table('user_orientations')->insert([
                    'user_id' => $userId,
                    'unit_slug' => $unitSlug,
                    'is_verified' => 0,
                ]);
            }
        }

        $completed = DB::table('user_orientations')->where('user_id', $userId)->count();

        return response()->json([
            'success' => true,
            'completed' => $completed,
            'message' => 'Completion saved',
        ]);
    }

    private function ensureProgressTable(): void
    {
        DB::statement('CREATE TABLE IF NOT EXISTS user_orientation_progress (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            unit_slug VARCHAR(100) NOT NULL,
            viewed_slides TEXT NOT NULL,
            last_slide INT DEFAULT NULL,
            total_slides INT DEFAULT NULL,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY unique_user_unit (user_id, unit_slug)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
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

    private function isAuthorized(Request $request): bool
    {
        return $request->session()->has('user_id') && $request->session()->get('role') === self::ROLE_STAFF;
    }

    private function oldUserId(Request $request): int
    {
        return (int) $request->session()->get('user_id');
    }

    private function isValidUnit(string $unitSlug): bool
    {
        return $unitSlug !== '' && array_key_exists($unitSlug, self::UNIT_CONFIG);
    }
}
