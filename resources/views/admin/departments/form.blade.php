<div class="space-y-5">
    <div>
        <label class="block text-sm font-medium text-slate-700">Department name</label>
        <input name="name" value="{{ old('name', $department?->name) }}" required class="mt-1 w-full rounded-lg border-slate-300">
        <x-input-error :messages="$errors->get('name')" class="mt-2"/>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700">Description</label>
        <textarea name="description" rows="4" class="mt-1 w-full rounded-lg border-slate-300">{{ old('description', $department?->description) }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2"/>
    </div>

    <div class="flex justify-end gap-3 pt-2">
        <a href="{{ route('admin.departments.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700">Cancel</a>
        <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Save</button>
    </div>
</div>
