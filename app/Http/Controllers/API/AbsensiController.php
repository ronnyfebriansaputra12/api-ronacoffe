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
            $query->whereRaw("user_id LIKE '%" . $request->get('keyword') . "%'");
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
        $absen->jam_keluar = NULL;

        $check = Absensi::where('user_id', $request->auth->sub)->whereDate('created_at', Carbon::today())->first();
        if($check){
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah absen hari ini'
            ]);
        }
        $absen->save();

        return $this->sendResponse(true, 'Ok', $absen);


    }

    public function update($id)
    {
        $absen = Absensi::find($id);

        if(!$absen){
            return response()->json([
                'success' => false,
                'message' => 'Absen jam masuk belum ada'
            ]);
        }

        $absen->jam_keluar = date('Y-m-d H:i:s');

        if($absen->jam_keluar < $absen->jam_masuk){
            return response()->json([
                'success' => false,
                'message' => 'Jam keluar tidak boleh kurang dari jam masuk'
            ]);
        }

        if($absen->jam_keluar == $absen->jam_masuk){
            return response()->json([
                'success' => false,
                'message' => 'Jam keluar tidak boleh sama dengan jam masuk'
            ]);
        }

        $waktu_pulang = date('H:i:s');

        if ($waktu_pulang < '10:34:00') {
            return response()->json([
                'success' => false,
                'message' => 'Belum waktu nya absen pulang BROO 🤚, silahkan ambil jam 17.00'
            ]);
        }

        $result = Absensi::where('user_id', $absen->user_id)->first();
        $test = $result->jam_keluar;
        if($test){
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah absen pulang hari ini'
            ]);
        }
        $absen->save();
        return $this->sendResponse(true, 'Terimakasih', $absen);
    }
}
