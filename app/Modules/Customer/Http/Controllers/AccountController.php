<?php

namespace App\Modules\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Customer\Http\Requests\UpdateEmailRequest;
use App\Modules\Customer\Http\Requests\UpdateNameRequest;
use App\Modules\Customer\Http\Requests\UpdatePasswordRequest;
use Illuminate\Http\RedirectResponse;

class AccountController extends Controller
{
    public function updateName(UpdateNameRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $user->update(['name' => $request->validated('name')]);

        return redirect()->route('account')->with('success', 'Nama berhasil diperbarui.');
    }

    public function updateEmail(UpdateEmailRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $user->update(['email' => $request->validated('email')]);

        return redirect()->route('account')->with('success', 'Email berhasil diperbarui.');
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        // Password di-hash otomatis oleh cast 'hashed' pada model User.
        $user->update(['password' => $request->validated('password')]);

        // Sesi diperbarui setelah password berubah.
        $request->session()->regenerate();

        return redirect()->route('account')->with('success', 'Password berhasil diganti.');
    }
}
