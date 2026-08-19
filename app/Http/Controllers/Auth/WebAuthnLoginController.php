<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\WebAuthnService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class WebAuthnLoginController extends Controller
{
    public function options(WebAuthnService $service): JsonResponse
    {
        try {
            $options = $service->getLoginArgs();

            return response()->json([
                'success' => true,
                'options' => $options,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function login(Request $request, WebAuthnService $service): JsonResponse
    {
        $request->validate([
            'id' => 'required|string',
            'clientDataJSON' => 'required|string',
            'authenticatorData' => 'required|string',
            'signature' => 'required|string',
        ]);

        try {
            $user = $service->processLogin(
                $request->clientDataJSON,
                $request->authenticatorData,
                $request->signature,
                $request->id
            );

            if (! $user->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda tidak aktif.',
                ], 403);
            }

            Auth::login($user, true);
            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'redirect' => route('dashboard'),
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
