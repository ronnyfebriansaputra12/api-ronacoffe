<?php

namespace App\Http\Controllers\API;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;


class PengeluaranController extends Controller
{
  public function index(Request $request)
    {

        $query = Pengeluaran::query();

        if ($request->has('keyword')) {
            $query->whereRaw("pengeluaran LIKE '%" . $request->get('keyword') . "%'");
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
        $validate = $request->validate([
            'pengeluaran' => 'required|numeric',
        ]);

        if($validate){
            $pengeluaran = Pengeluaran::create($request->all());
            return $this->sendResponse(true, 'Data berhasil ditambahkan', $pengeluaran);
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
        ]);
        $result = Pengeluaran::where('id', $id)->update($validate);

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil diubah',
            'data' => $validate
        ]);

    }
}
