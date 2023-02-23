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
            'data' => $user
        ]);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        // print_r($user);
        // die();

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
        $validate = $request->validate([
            'nama_user' => 'max:50',
            'email' => ' email|unique:users',
            'no_hp' => 'numeric',
            'password' => 'min:6',
            'password_confirmation' => 'same:password|min:6',
            'role' => 'max:50',
            'posisi' => 'max:50',
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5048',
        ]);

        if ($request->hasFile('avatar')) {
            $gambar = $request->file('avatar');
            $name = time().'.'.$gambar->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $gambar->move($destinationPath, $name);

            $data_gambar = User::findOrfail($id);
            File::delete(public_path('images/' . $data_gambar->gambar));
            $validate['avatar'] = $name;
        }

        $produk = User::where('user_id', $id)->update($validate);
        return $this->sendResponse(true, 'Ok', $produk);
    }
}