<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect']
            ]);
        }

        $at_expiration = 60;
        $access_token = $user->createToken('access_token', ['access-api'], Carbon::now()->addMinutes($at_expiration))->plainTextToken;
        $rt_expiration = 30 * 24 * 60;
        $refreshToken = $user->createToken('refresh_token', ['issue-access-token'], Carbon::now()->addMinutes($rt_expiration))->plainTextToken;
        
        return response()->json([
            'message' => 'Login Successfully',
            'token' => $access_token,
            'refresh_token' => $refreshToken
        ]);
    }
}
