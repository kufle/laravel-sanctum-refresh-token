<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RefreshTokenController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        //delete old token
        $request->user()->tokens()->delete();
        //add new token
        $at_expiration = 60;
        $access_token = $request->user()->createToken('access_token', ['access-api'], Carbon::now()->addMinutes($at_expiration))->plainTextToken;
        $rt_expiration = 30 * 24 * 60;
        $refreshToken = $request->user()->createToken('refresh_token', ['issue-access-token'], Carbon::now()->addMinutes($rt_expiration))->plainTextToken;
        return response()->json([
            'message' => 'Token refreshed successfully',
            'token' => $access_token,
            'refresh_token' => $refreshToken
        ]);
    }
}
