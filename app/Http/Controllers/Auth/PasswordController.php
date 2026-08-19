<?php

/** Goal: Handle user password updates, Caller: routes/auth.php, Deps: Hash, Password, PegawaiChangesHistory */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PegawaiChangesHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = $request->user();
        $pegawai = $user->pegawai;

        if ($pegawai) {
            PegawaiChangesHistory::create([
                'pegawai_id' => $pegawai->id,
                'field_name' => 'password',
                'old_value' => null,
                'new_value' => '[updated]',
                'alasan' => 'Perubahan password oleh user',
                'changed_by' => $user->id,
            ]);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'Password updated.');
    }
}
