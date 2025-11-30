<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller 
{


    /**
     * Login endpoint.
     *
     * SPA flow: call GET /sanctum/csrf-cookie then POST /api/login with credentials and credentials: 'include'.
     * Mobile/API flow: include device_name to receive a personal access token.
     *
     * Request payload:
     * - email (required)
     * - password (required)
     * - device_name (optional) -> when present returns a token for API clients
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Token flow for mobile/3rd-party clients
        if ($request->filled('device_name')) {
            $user = User::where('email', $data['email'])->first();

            if (! $user || ! Hash::check($data['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $token = $user->createToken('login')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ]);
        }

        // SPA/session flow (Sanctum)
        if (! Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // regenerate session id
        $request->session()->regenerate();

        return response()->json([
            'user' => $request->user(),
        ]);
    }

    /**
     * Logout. Works for both token-based and session-based auth.
     * If a bearer token is present, revoke that token. Otherwise logout the session.
     */
    public function logout(Request $request)
    {
        // revoke bearer token if present (API/mobile logout)
        if ($request->bearerToken()) {
            $token = $request->user()?->currentAccessToken();
            if ($token) {
                $token->delete();
            }
            return response()->noContent();
        }

        // otherwise destroy the session (SPA logout)
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();
    }

    /**
     * Return the current authenticated user.
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * List personal access tokens for the authenticated user.
     */
    public function tokens(Request $request)
    {
        $tokens = $request->user()->tokens()->get(['id', 'name', 'abilities', 'created_at']);
        return response()->json($tokens);
    }

    /**
     * Revoke a token by id (personal access tokens).
     */
    public function revokeToken(Request $request, $tokenId)
    {
        $token = $request->user()->tokens()->where('id', $tokenId)->first();
        if (! $token) {
            return response()->json(['message' => 'Token not found'], 404);
        }
        $token->delete();
        return response()->noContent();
    }
}
