<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AbsensiResource;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Date;

class AbsensiController extends Controller
{
    public function index(Request $request){

        $query = Absensi::query();

        if ($request->has('keyword')) {
            $query->whereRaw("name_kuliner LIKE '%" . $request->get('keyword') . "%'");
        }

        if ($request->has('order_by')) {
            $query->orderBy($request->get('order_by'), $request->get('order'));
        }
        $query = $query->paginate((int)$request->get('limit') ?? 10);

        $result = [
            'items'=> $query->items(),
            'currentPage' => $query->currentPage(),
            'from' => $query->firstItem() ?? 0,
            'lastPage' => $query->lastPage(),
            'perPage' => $query->perPage(),
            'to' => $query->lastItem() ?? 0,
            'total' => $query->total()
        ];

        return $this->sendResponse(true, 'Ok', $result);
        // $absensis = Absensi::all();

        // return response()->json([
        //     'success' => true,
        //     'data' => $absensis

        // ])
        }
    public function store(Request $request)
    {
        $absen = new Absensi();
        $absen->user_id = $request->auth->sub;
        $absen->jam_masuk = date('Y-m-d H:i:s');
        $absen->jam_keluar = $request->jam_keluar;
        $absen->save();

        // $validate['jam_masuk'] = Carbon::parse($validate['jam_masuk'])->format('H:i:s');
        // $validate['jam_keluar'] = Carbon::parse($validate['jam_keluar'])->format('H:i:s');

        // if($validate['jam_masuk'] > $validate['jam_keluar']){
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Jam Masuk tidak boleh lebih besar dari Jam Keluar'
        //     ]);
        // }

        // if($validate['jam_masuk'] == $validate['jam_keluar']){
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Jam Masuk tidak boleh sama dengan Jam Keluar'
        //     ]);
        // }

        // if($validate['jam_keluar'] <= '17:00:00'){
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Jam Keluar tidak boleh Kurang dari 17:00:00'
        //     ]);
        // }

        // if($validate['jam_masuk'] > '08:15:00'){
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Jam Masuk tidak boleh lebih besar dari 08:00:00'
        //     ]);
        // }


        // $user = User::find($validate['user_id']);
        // if(!$user){
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'User tidak ditemukan'
        //     ]);
        // }

        // $absensi = Absensi::create([
        //     'user_id' => $request->input('user_id'),
        //     'tanggal' => $request->input('tanggal'),
        //     'jam_masuk' => $request->input('jam_masuk'),
        //     'jam_keluar' => $request->input('jam_keluar'),
        // ]);

    return response()->json([
        'success' => true,
        'data' => $absen
    ]);

    }

    public function test()
    {
        $test = Carbon::now()->format('l, d F Y H:i');
        dd($test);
    }
}
