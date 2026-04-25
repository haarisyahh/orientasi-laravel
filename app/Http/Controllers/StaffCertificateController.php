<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffCertificateController extends Controller
{
    public function show(Request $request)
    {
        if (!$request->session()->has('user_id') || $request->session()->get('role') !== 'staff_baru') {
            return redirect()->route('login.staff');
        }

        $userId = (int) $request->session()->get('user_id');
        $userName = (string) $request->session()->get('nama_penuh', 'Staff');

        $latestCompletion = DB::table('user_orientations')
            ->where('user_id', $userId)
            ->max('completion_date');

        $date = $latestCompletion
            ? Carbon::parse($latestCompletion)
            : now();
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Mac', 4 => 'April', 5 => 'Mei', 6 => 'Jun',
            7 => 'Julai', 8 => 'Ogos', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Disember',
        ];

        $tarikh = $date->format('d').' '.$months[(int) $date->format('m')].' '.$date->format('Y');
        $masa = $date->format('H:i');

        return view('staff.certificate', [
            'userName' => $userName,
            'tarikh' => $tarikh,
            'masa' => $masa,
        ]);
    }
}
