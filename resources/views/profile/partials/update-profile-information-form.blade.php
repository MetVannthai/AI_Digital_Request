<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">Update your account details used by the stock desk.</p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div><x-input-label for="username" :value="__('Username')" /><x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username', $user->username)" required /><x-input-error class="mt-2" :messages="$errors->get('username')" /></div>
        <div><x-input-label for="department" :value="__('Department')" /><x-text-input id="department" name="department" type="text" class="mt-1 block w-full" :value="old('department', $user->department)" /><x-input-error class="mt-2" :messages="$errors->get('department')" /></div>
        <div><x-input-label for="phone" :value="__('Phone')" /><x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" /><x-input-error class="mt-2" :messages="$errors->get('phone')" /></div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
