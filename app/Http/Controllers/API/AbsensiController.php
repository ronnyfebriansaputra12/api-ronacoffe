<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;
use Carbon\Carbon;
use App\Models\User;
date_default_timezone_set("Asia/Jakarta");

class AbsensiController extends Controller
{
    public function index(Request  $id)
        {

            $absensis = User::find($id->auth->sub);
            // dd($absensis);
            // $absensis = Absensi::where('user_id', Auth::user()->id)->get();
            // dd($absensis);
            foreach($absensis as $item) {
                if ($item->tanggal == date('Y-m-d')) {
                    $item->is_hari_ini = true;
                } else {
                    $item->is_hari_ini = false;
                }
                $datetime = Carbon::parse($item->tanggal)->locale('id');
                $masuk = Carbon::parse($item->masuk)->locale('id');
                $pulang = Carbon::parse($item->pulang)->locale('id');

                $datetime->settings(['formatFunction' => 'translatedFormat']);
                $masuk->settings(['formatFunction' => 'translatedFormat']);
                $pulang->settings(['formatFunction' => 'translatedFormat']);

                $item->tanggal = $datetime->format('l, j F Y');
                $item->masuk = $masuk->format('H:i');
                $item->pulang = $pulang->format('H:i');
            }

            return response()->json([
                'success' => true,
                'data' => $absensis,
                'message' => 'Sukses menampilkan data'
            ]);
    }

    function savePresensi(Request $request)
    {
        $keterangan = "";
        $absensi = Absensi::whereDate('tanggal', '=', date('Y-m-d'))
                        ->where('user_id', Auth::user()->id)
                        ->first();
        if ($absensi == null) {
            $absensi = Absensi::create([
                'user_id' => Auth::user()->id,
                'tanggal' => date('Y-m-d'),
                'masuk' => date('H:i:s')
            ]);
        } else {
            $data = [
                'pulang' => date('H:i:s')
            ];

            Absensi::whereDate('tanggal', '=', date('Y-m-d'))->update($data);

        }
        $absensi = Absensi::whereDate('tanggal', '=', date('Y-m-d'))
                 ->first();

        return response()->json([
            'success' => true,
            'data' => $absensi,
            'message' => 'Sukses simpan'
        ]);
    }

    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'user_id' => 'required|exists:users,id',
    //         'tanggal' => 'required|date',
    //         'keterangan' => 'required|in:present,absent'
    //     ]);

    //     $absensi = new Absensi();
    //     $absensi->user_id = $validatedData['user_id'];
    //     $absensi->tanggal = $validatedData['tanggal'];
    //     $absensi->keterangan = $validatedData['keterangan'];
    //     $absensi->save();

    //     return response()->json(['message' => 'Absensi recorded successfully']);
    //     // $this->validate($request, [
    //     //     'user_id' => 'required|integer',
    //     //     'tanggal' => 'required|date',
    //     //     'keterangan' => 'required|string'
    //     ;

    //     $absensi = Absensi::create([
    //         'user_id' => $request->input('user_id'),
    //         'tanggal' => $request->input('tanggal'),
    //         'keterangan' => $request->input('keterangan')
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'data' => $absensi
    //     ]);
    // }

    // public function show($id)
    // {
    //     $absensi = Absensi::with('user')->find($id);

    // }

    // public function update(Request $request, Absensi $absensi)
    // {

    // }
}
