<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;
use App\Models\User;

class AbsensiController extends Controller
{
    public function index($id)
    {
        echo"test";
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|integer',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string'
        ]);

        $absensi = Absensi::create([
            'user_id' => $request->input('user_id'),
            'tanggal' => $request->input('tanggal'),
            'keterangan' => $request->input('keterangan')
        ]);

        return response()->json([
            'success' => true,
            'data' => $absensi
        ]);
    }

    public function show($id)
    {
        $absensi = Absensi::with('user')->find($id);

    }

    public function update(Request $request, Absensi $absensi)
    {

    }
}
