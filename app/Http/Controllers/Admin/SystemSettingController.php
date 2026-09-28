<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SystemSettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', ['settings' => SystemSetting::current()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'default_min_stock' => ['required', 'integer', 'min:0', 'max:1000000'],
            'require_request_approval' => ['required', 'boolean'],
        ]);

        SystemSetting::current()->update([
            'default_min_stock' => $validated['default_min_stock'],
            'require_request_approval' => $request->boolean('require_request_approval'),
        ]);

        return to_route('admin.settings.edit')->with('success', 'Stock settings saved.');
    }
}
