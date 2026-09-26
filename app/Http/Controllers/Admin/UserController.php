<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View { return view('admin.users.index', ['users' => User::with('roles')->orderBy('name')->paginate(20)]); }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View { return view('admin.users.create'); }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $pin = $request->input('pin') ?: (string) random_int(100000, 999999);
        $user = User::create([...$request->safe()->except('pin'), 'username' => Str::lower($request->string('username')->toString()), 'pin' => Hash::make($pin), 'must_change_pin' => true, 'status' => 'active']);
        $user->syncRoles($request->string('role')->toString());
        return to_route('admin.users.index')->with('created_user_credentials', ['username' => $user->username, 'pin' => $pin]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View { return view('admin.users.edit', compact('user')); }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View { return view('admin.users.edit', compact('user')); }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $user->update([...$request->validated(), 'username' => Str::lower($request->string('username')->toString())]);
        $user->syncRoles($request->string('role')->toString());
        return to_route('admin.users.index')->with('success', 'User updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->is(auth()->user())) return back()->with('error', 'You cannot delete your own account.');
        $user->delete();
        return to_route('admin.users.index')->with('success', 'User deleted.');
    }

    public function resetPin(User $user): RedirectResponse
    {
        $pin = (string) random_int(100000, 999999);
        $user->update(['pin' => Hash::make($pin), 'must_change_pin' => true]);
        return to_route('admin.users.index')->with('reset_user_credentials', ['username' => $user->username, 'pin' => $pin]);
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        if ($user->is(auth()->user())) return back()->with('error', 'You cannot deactivate your own account.');
        $user->update(['status' => $user->status === 'active' ? 'inactive' : 'active']);
        return back()->with('success', 'User status updated.');
    }
}
