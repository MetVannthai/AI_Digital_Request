<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="pin" :value="__('PIN Code')" />

            <div class="pin-code-group mt-2" data-pin-group>
                @for ($i = 0; $i < 6; $i++)
                    <input
                        type="password"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        maxlength="1"
                        aria-label="PIN digit {{ $i + 1 }}"
                        data-pin-input
                        class="pin-digit"
                        autocomplete="one-time-code"
                    >
                @endfor
                <input type="hidden" name="pin" data-pin-hidden>
            </div>

            <x-input-error :messages="$errors->get('pin')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
