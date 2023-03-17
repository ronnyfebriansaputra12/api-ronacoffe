<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AbsensiResource;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;
use Carbon\Carbon;
use App\Models\User;


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
}
