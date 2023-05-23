<?php

namespace App\Http\Controllers\CMS\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class CloudController extends Controller
{
    public function upload(Request $request)
    {
        $imagePath = $request->file('image')->getRealPath();

        // $result = Cloudinary::upload($imagePath);
        $result = Cloudinary::upload($imagePath, [
            'folder' => 'avatar',
            'transformation' => [
                'width' => 320,
                'height' => 320,
                'crop' => 'limit',
            ],
        ]);
        

        // Ambil URL gambar yang diunggah
        $imageUrl = $result->getSecurePath();

        return response()->json(['image_url' => $imageUrl]);
    }
}
