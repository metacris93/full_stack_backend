<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserSignInResource;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return $this->errorApiResponse('Invalid Credentials', Response::HTTP_BAD_REQUEST, $validator->errors()->all());
        }
        if (!auth()->attempt($validator->validated())) {
            return $this->errorApiResponse('Invalid Credentials', Response::HTTP_BAD_REQUEST);
        }
        return $this->successApiResponse(new UserSignInResource(auth()->user()));
    }
    public function logout(Request $request)
    {
        // auth()->user()->token()->revoke();
        // auth()->user()->token()->delete(); // con este funciona la prueba unitaria

        $refreshTokenRepository = app('Laravel\Passport\RefreshTokenRepository');
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($tokenId);

        return $this->successApiResponse(null, false, 'Logged out successfully');
    }
}
