<?php

namespace App\Http\Controllers\CMS\Manage;

use App\Models\Inventory;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PengeluaranResource;
use Symfony\Component\HttpFoundation\Response;


class PengeluaranController extends Controller
{
  public function index(Request $request)
    {

        $query = Pengeluaran::query();

        if ($request->has('keyword')) {
            $query->whereRaw("pengeluaran LIKE '%" . $request->get('keyword') . "%'");
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('tanggal', [$request->get('start_date'), $request->get('end_date')]);
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
     * Display the specified resource.
     *
     * @param  \App\Models\Inventori  $kuliner
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pengeluaran = Pengeluaran::find($id);

        if ($pengeluaran == null) {
            return $this->sendResponse(false, 'Data not found')->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        return $this->sendResponse(true, 'Ok', $pengeluaran);
    }


    public function store(Request $request)
    {

        try {
            
            $validate = $request->validate([
                'pengeluaran' => 'required|numeric',
                'tanggal' => 'required',
                'rincian' => 'required',
                'inventori_id' => 'required|numeric',
                'jumlah' => 'required|numeric',
            ]);

            $inventori = Inventory::findOrFail($validate['inventori_id']);

            // tambah stok produk
            $inventori->stok += $validate['jumlah'];
            $inventori->save();

            
            // simpan data transaksi
            $pengeluaran = Pengeluaran::create($validate);
            
            return new PengeluaranResource($pengeluaran);

        } catch (\Throwable $e) {
            return $this->sendResponse(false, $e->getMessage())->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }


    }
    public function destroy($id)
    {
        $post = Pengeluaran::findOrfail($id);
        if($post) {
            $post->delete();
            return response()->json([
                "message" => "success"
            ], 200);
        } else {
            return response()->json([
                "message" => "Post not found"
            ], 404);
        }

    }
    public function update(Request $request, $id)
    {
        $validate = $request ->validate([
            'pengeluaran' => 'numeric',
            'tanggal' => 'date',
            'rincian' => 'string',
            'inventori_id' => 'numeric',
            'jumlah' => 'numeric',
        ]);
        Pengeluaran::where('id', $id)->update($validate);

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil diubah',
            'data' => $validate
        ]);

    }
}
