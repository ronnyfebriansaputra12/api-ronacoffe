<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_user' => 'required',
            'email' => ' email|unique:users',
            'no_telp' => 'numeric',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password|min:6',
            'role' => 'in:owner,admin,karyawan',
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5048',
        ]);

        if ($validator->fails()) {  
            return response()->json(['error'=>$validator->errors()], 401); 
        } 

        $user = User::create([
            'nama_user'=>$request->nama_user,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'password_confirmation'=>Hash::make($request->password_confirmation),
        ]);

        return response()->json([
            'message' => 'Data berhasil ditambahkan',
            'data' => $user,
            'status_code' => 200
        ]);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (Hash::check($request->get('password'), $user->password)) {
                $payload = $this->jwt($request->token, $user);
                $token = JWT::encode($payload, env("JWT_SECRET"), 'HS256');

                return $this->sendResponse(true, 'Ok', [
                    'UserAuth' => [
                        'user_id' => $user->user_id,
                        'nama_user' => $user->nama_user,
                        'email' => $user->email,
                        'role' => $user->role,
                    ],
                    'platform' => $request->token->platform,
                    'scope' => $payload['scope'],
                    'type' => $request->token->type ?? '',
                    'issuedAt' => $payload['iat'],
                    'expiredAt' => $payload['exp'],
                    'token' => $token,
                ])->setStatusCode(Response::HTTP_OK);
            }
        }

        return $this->sendResponse(false, "These credentials do not match our records")
            ->setStatusCode(Response::HTTP_BAD_REQUEST);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ], Response::HTTP_OK);
    }

    public function profile(Request  $id)
    {
        $customer = User::find($id->auth->sub);
        return response()->json(['message' => 'success', 'data' => $customer]);
    }

    public function profedit(Request $request,$id)
    {
        // dd($request->password);

        $validate = $request->validate([
            'nama_user' => 'max:50',
            'email' => ' email',
            'no_hp' => 'numeric',
            'password' => 'min:6|nullable',
            'password_confirmation' => 'nullable|same:password|min:6',
            'role' => 'max:50',
            'posisi' => 'max:50',
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5048',
        ]);

        if($request->hasFile('avatar')){

            $imagePath = $request->file('avatar')->getRealPath();
            $result = Cloudinary::upload($imagePath, [
                'folder' => 'avatar',
                'transformation' => [
                    'width' => 320,
                    'height' => 320,
                    'crop' => 'limit',
                ],
            ]);
            $imageUrl = $result->getSecurePath();
            $validate['avatar'] = $imageUrl;

                if ($request->filled('password') && $request->filled('password_confirmation')) {
                    $validate['password'] = Hash::make($request->password);
                    $validate['password_confirmation'] = Hash::make($request->password_confirmation);
                } else {
                    // Jika password tidak diubah, hapus validasi dan atribut password
                    unset($validate['password']);
                    unset($validate['password_confirmation']);
                }
                $user = User::find($id);
                    if (!$user) {
                        return $this->sendResponse(false, 'User not found', null);
                    }

            $produk = User::where('user_id', $id)->update($validate);
            return $this->sendResponse(true, 'Ok', $produk);
            
        }

        if ($request->filled('password') && $request->filled('password_confirmation')) {
            $validate['password'] = Hash::make($request->password);
            $validate['password_confirmation'] = Hash::make($request->password_confirmation);
        } else {
            // Jika password tidak diubah, hapus validasi dan atribut password
            unset($validate['password']);
            unset($validate['password_confirmation']);
        }
        $user = User::find($id);
            if (!$user) {
                return $this->sendResponse(false, 'User not found', null);
            }

        $produk = User::where('user_id', $id)->update($validate);
        return $this->sendResponse(true, 'Ok', $produk);


    }

    public function getAllUser()
    {
        $user = User::all();
        return $this->sendResponse(true, 'Ok', $user);

    }
}