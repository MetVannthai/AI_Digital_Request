<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePinRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PinController extends Controller
{
    public function edit(): View
    {
        return view('auth.change-pin');
    }

    public function update(ChangePinRequest $request): RedirectResponse
    {
        if (Hash::check($request->string('new_pin')->toString(), $request->user()->pin)) {
            throw ValidationException::withMessages(['new_pin' => 'Your new PIN must differ from the current PIN.']);
        }

        $request->user()->update(['pin' => Hash::make($request->string('new_pin')->toString()), 'must_change_pin' => false]);

        return redirect()->route('dashboard')->with('success', 'PIN changed successfully.');
    }
}
