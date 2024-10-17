<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthenticationRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthenticationController extends Controller
{
    /**
     * Authenticate User.
     */
    public function login(AuthenticationRequest $request): JsonResponse   
    {
        throw_if(! auth()->attempt($request->all()), new HttpException(Response::HTTP_UNAUTHORIZED, 'Invalid credentials.'));
        $user = auth()->user();
        $token = $user->createToken('api-token')->plainTextToken;
        $data = [
            'token' => $token,
            'user' => $user,
        ];

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }
}
