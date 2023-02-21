<?php
namespace App\Http\Controllers\API;

use App\Models\Produk;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Produk::query();

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'nama_produk' => 'required|unique:produks|max:255|min:3|',
            'harga' => 'required|numeric',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5048',
            'deskripsi' => 'required|max:255|min:3',
        ]);

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $name = time().'.'.$gambar->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $gambar->move($destinationPath, $name);
            $validate['gambar'] = $name;
        }
        
        $post = Produk::create($validate);

        return $this->sendResponse(true, 'Ok', $post);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $produk = Produk::find($id);

        if ($produk == null) {
            return $this->sendResponse(false, 'Data not found')->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        return $this->sendResponse(true, 'Ok', $produk);
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
        $validate = $request->validate([
            'nama_produk' => 'unique:produks|max:255|min:3|',
            'harga' => 'numeric',
            'gambar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5048',
            'deskripsi' => 'max:255|min:3',
        ]);

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $name = time().'.'.$gambar->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $gambar->move($destinationPath, $name);

            $data_gambar = Produk::findOrfail($id);
            File::delete(public_path('images/' . $data_gambar->gambar));
            $validate['gambar'] = $name;
        }

        $produk = Produk::where('id', $id)->update($validate);
        return $this->sendResponse(true, 'Ok', $produk);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Produk::findOrfail($id);
        if($post) {
            if($post->gambar) {
                File::delete(public_path('images/' . $post->gambar));
            }
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
