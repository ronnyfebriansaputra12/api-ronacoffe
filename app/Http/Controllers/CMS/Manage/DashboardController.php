<?php

namespace App\Http\Controllers\CMS\Manage;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function cekAbsensi()
    {
        $users = User::where('role', 'karyawan')->get();
        $data = [];
        foreach ($users as $key => $user){
            $cek = Absensi::where('user_id', $user->user_id)->whereDate('created_at', Carbon::today())->first();
            if(!$cek){
                $data[] = $user;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Ok',
            'data' => $data
        ]);
    }
}
