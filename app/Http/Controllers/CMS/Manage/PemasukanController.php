<?php

namespace App\Http\Controllers\CMS\Manage;
use Carbon\Carbon;
use App\Models\Pemasukan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Date;
use Symfony\Component\HttpFoundation\Response;


class PemasukanController extends Controller
{
  public function index(Request $request)
    {

        $query = Pemasukan::query();

        if ($request->has('keyword')) {
            $query->whereRaw("pemasukan LIKE '%" . $request->get('keyword') . "%'");
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
        $pemasukan = Pemasukan::find($id);

        if ($pemasukan == null) {
            return $this->sendResponse(false, 'Data not found')->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        return $this->sendResponse(true, 'Ok', $pemasukan);
    }


    public function store(Request $request)
    {
        $validate = $request->validate([
            'pemasukan' => 'required|numeric',
            'tanggal' => 'required',
        ]);

        // $validate['pemasukan'] = Date::parse($validate['pemasukan'])->format('Y-m-d');

        if($validate){
            $pemasukan = Pemasukan::create($request->all());
            return $this->sendResponse(true, 'Data berhasil ditambahkan', $pemasukan);
        }
    }

    public function update(Request $request, $id)
    {
        $validate = $request ->validate([
            'pemasukan' => 'numeric',
            'tanggal' => 'date',
        ]);
        $result = Pemasukan::where('id', $id)->update($validate);

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil diubah',
            'data' => $validate
        ]);

    }


    public function destroy($id)
    {
        $post = Pemasukan::findOrfail($id);
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

}
