<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\URL;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendResponse($success, $message, $data = null)
    {
        $response['success'] = $success;
        $response['message'] = $message;
        $response['data'] = $data;

        return response()->json($response);
    }

    public function jwt($credential, $customer)
    {
        $payload = [
            'iss' => \URL::to('/'),
            'iat' => time(),
            'exp' => 0,
            'sub' => $customer != null ? $customer->user_id:'',
            'platform' => $credential->platform,
            'scope' => env('APP_ENV'),
            'type' => $credential->type,
        ];

        switch ($payload['platform']) {
            case 'Web':
                $payload['exp'] = time() + 60 * 60 * 24 * env('TOKEN_WEB_EXPIRE', 30);
                break;
            case 'Cms':
                $payload['exp'] = time() + 60 * 60 * 24 * env('TOKEN_BACKOFFICE_EXPIRE', 30);
                break;
            case 'Android':
                $payload['exp'] = time() + 60 * 60 * 24 * env('TOKEN_ANDROID_EXPIRE', 30);
                break;
            case 'IOS':
                $payload['exp'] = time() + 60 * 60 * 24 * env('TOKEN_IOS_EXPIRE', 30);
                break;
        }

        return $payload;
    }
}
