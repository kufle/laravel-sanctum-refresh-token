<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisterRequest $request)
    {
        $request->merge(['password' => Hash::make($request->password)]);

        $user = User::create($request->all());

        $at_expiration = 60;
        $access_token = $user->createToken('access_token', ['access-api'], Carbon::now()->addMinutes($at_expiration))->plainTextToken;
        $rt_expiration = 30 * 24 * 60;
        $refreshToken = $user->createToken('refresh_token', ['issue-access-token'], Carbon::now()->addMinutes($rt_expiration))->plainTextToken;
        
        return response()->json([
            'message' => 'Register Successfully',
            'token' => $access_token,
            'refresh_token' => $refreshToken
        ]);
    }
}
