<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;
use Symfony\Component\HttpFoundation\Response;


class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $query = Inventory::query();

        if ($request->has('keyword')) {
            $query->whereRaw("nama_produk LIKE '%" . $request->get('keyword') . "%'");
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
        $inventori = Inventory::find($id);

        if ($inventori == null) {
            return $this->sendResponse(false, 'Data not found')->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        return $this->sendResponse(true, 'Ok', $inventori);
    }


    public function store(Request $request)
    {
        $validate = $request->validate([
            'kd_barang' => 'required',
            'nama_barang' => 'required',
            'stok' => 'required',
            'harga' => 'required',
            'satuan' => 'required',
        ]);

        if($validate){
            $inventori = Inventory::create($request->all());
            return $this->sendResponse(true, 'Data berhasil ditambahkan', $inventori);
        }
    }
    public function destroy($id)
    {
        $post = Inventory::findOrfail($id);
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
            'kd_barang' => 'required',
            'nama_barang' => 'required',
            'stok'=>'required',
            'harga'=>'required',
            'satuan' => 'required',
        ]);
        $result = Inventory::where('id', $id)->update($validate);

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil diubah',
            'data' => $result
        ]);
        # code...
    }
}
