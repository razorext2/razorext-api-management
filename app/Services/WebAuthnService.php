<?php

namespace App\Services;

use App\Models\User;
use App\Models\WebAuthnCredential;
use Exception;
use lbuchs\WebAuthn\Binary\ByteBuffer;
use lbuchs\WebAuthn\WebAuthn;

class WebAuthnService
{
    protected WebAuthn $webauthn;

    public function __construct()
    {
        ByteBuffer::$useBase64UrlEncoding = true;

        $rpName = config('app.name', 'RazorAPI');
        $rpId = request()->getHost();
        if ($rpId === 'localhost' || $rpId === '127.0.0.1') {
            $rpId = 'localhost';
        }

        $this->webauthn = new WebAuthn($rpName, $rpId, ['android-key', 'android-safetynet', 'apple', 'fido-u2f', 'none', 'packed', 'tpm']);
    }

    public function getRegistrationArgs(User $user): array
    {
        $userIdHex = (string) $user->id;
        $createArgs = $this->webauthn->getCreateArgs($userIdHex, $user->email, $user->name, 60, false, 'preferred');

        session(['webauthn_challenge' => (string) $this->webauthn->getChallenge()]);

        return json_decode(json_encode($createArgs), true);
    }

    public function processRegistration(User $user, string $name, string $clientDataJSON, string $attestationObject): WebAuthnCredential
    {
        $challenge = session('webauthn_challenge');
        if (! $challenge) {
            throw new Exception('Sesi tantangan WebAuthn tidak ditemukan atau kedaluwarsa.');
        }

        $clientDataBytes = base64_decode($clientDataJSON);
        $attestationBytes = base64_decode($attestationObject);

        $data = $this->webauthn->processCreate($clientDataBytes, $attestationBytes, $challenge, false, true, false);

        session()->forget('webauthn_challenge');

        return WebAuthnCredential::create([
            'user_id' => $user->id,
            'name' => $name,
            'credential_id' => base64_encode($data->credentialId),
            'public_key' => $data->credentialPublicKey,
            'attestation_format' => $data->attestationFormat,
            'sign_count' => $data->signatureCounter ?? 0,
            'user_handle' => (string) $user->id,
        ]);
    }

    public function getLoginArgs(?User $user = null): array
    {
        $allowedCredentials = [];

        if ($user) {
            $credentials = $user->webauthnCredentials;
            foreach ($credentials as $cred) {
                $allowedCredentials[] = base64_decode($cred->credential_id);
            }
        } else {
            $credentials = WebAuthnCredential::all();
            foreach ($credentials as $cred) {
                $allowedCredentials[] = base64_decode($cred->credential_id);
            }
        }

        $getArgs = $this->webauthn->getGetArgs($allowedCredentials, 60, null, false, 'preferred');

        session(['webauthn_challenge' => (string) $this->webauthn->getChallenge()]);

        return json_decode(json_encode($getArgs), true);
    }

    public function processLogin(string $clientDataJSON, string $authenticatorData, string $signature, string $id): User
    {
        $challenge = session('webauthn_challenge');
        if (! $challenge) {
            throw new Exception('Sesi tantangan WebAuthn tidak ditemukan atau kedaluwarsa.');
        }

        $credential = WebAuthnCredential::where('credential_id', $id)->first();

        if (! $credential) {
            throw new Exception('Kredensial Passkey tidak terdaftar.');
        }

        $clientDataBytes = base64_decode($clientDataJSON);
        $authenticatorDataBytes = base64_decode($authenticatorData);
        $signatureBytes = base64_decode($signature);

        $this->webauthn->processGet(
            $clientDataBytes,
            $authenticatorDataBytes,
            $signatureBytes,
            $credential->public_key,
            $challenge,
            $credential->sign_count
        );

        session()->forget('webauthn_challenge');

        $credential->update([
            'sign_count' => $credential->sign_count + 1,
        ]);

        return $credential->user;
    }
}
