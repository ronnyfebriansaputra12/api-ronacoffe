<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\PengambilanBarang;
use App\Http\Controllers\Controller;
use App\Http\Resources\PengambilanResource;
use App\Models\Inventory;
use Illuminate\Support\Facades\Validator;

class PengambilanBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = PengambilanBarang::query();

        if ($request->has('keyword')) {
            $query->whereRaw("pengambilan LIKE '%" . $request->get('keyword') . "%'");
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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validasi input
        $validate= $request->validate([
            'inventori_id' => 'required|integer|exists:inventories,id',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $inventori = Inventory::findOrFail($validate['inventori_id']);

        // validasi stok produk
        if ($inventori->stok < $validate['jumlah']) {
            return response()->json([
                'message' => 'Stok produk tidak mencukupi'
            ], 400);
        }

        // kurangi stok produk
        $inventori->stok -= $validate['jumlah'];
        $inventori->save();

        // simpan data transaksi
        $pengambilan = new PengambilanBarang([
            'inventori_id' => $inventori->id,
            'jumlah' => $validate['jumlah'],
            'keterangan' => $validate['keterangan'],
        ]);

        $pengambilan->save();

        return new PengambilanResource($pengambilan);



    
        
    }

    // public function pengurangan(Request $request)
    // {

    //     $inventori = Inventory::all();
    //     dd($inventori);

    
        
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
