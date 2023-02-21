<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', Rule::unique('customers')->whereNull('deleted_at')],
            'no_telp' => ['required'],
            'password' => ['required', 'min:8'],
        ]);

        if ($validator->fails()) {  
            return response()->json(['error'=>$validator->errors()], 401); 
        } 

        $customer = Customer::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'no_telp'=>$request->no_telp,
            'password'=>Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'Data berhasil ditambahkan',
            'data' => $customer
        ]);
    }

    public function login(Request $request)
    {
        $customer = Customer::where('email', $request->email)->first();
        // print_r($customer);
        // die();

        if ($customer) {
            if (Hash::check($request->get('password'), $customer->password)) {
                $payload = $this->jwt($request->token, $customer);
                $token = JWT::encode($payload, env("JWT_SECRET"), 'HS256');

                return $this->sendResponse(true, 'Ok', [
                    'customerAuth' => [
                        'customer_id' => $customer->customer_id,
                        'name' => $customer->name,
                        'email' => $customer->email,
                        'no_telp' => $customer->no_telp,
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

    public function profile(Request $id)
    {
        $customer = Customer::find($id);
        return response()->json(['message' => 'success', 'data' => $customer]);
    }

    public function profedit(Request $request,$id)
    {
        $customer = Customer::where('customer_id', $id)->first();
        if ($customer) {
            $customer->update($request->all());
            return response()->json([
                'message' => "Success",
                'data' => $customer
            ],200);
        }

        return response()->json([
            'message' => "Tidak ada Customer!"
        ], 404);
    }
}