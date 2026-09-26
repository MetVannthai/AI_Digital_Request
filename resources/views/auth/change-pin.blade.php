<x-guest-layout>
    <div class="mb-6"><p class="text-sm font-semibold uppercase tracking-[0.16em] text-violet-600">First login setup</p><h1 class="mt-2 text-2xl font-bold text-slate-900">Change your PIN</h1><p class="mt-2 text-sm text-slate-600">Choose a new 6-digit PIN before entering the stock desk.</p></div>
    @if($errors->any())<div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-700"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form method="POST" action="{{ route('pin.update') }}" class="space-y-5">@csrf @method('PUT')
        <div>
            <x-input-label for="new_pin" :value="__('New PIN Code')" />
            <div class="pin-code-group mt-2" data-pin-group>
                @for ($i = 0; $i < 6; $i++)
                    <input
                        type="password"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        maxlength="1"
                        aria-label="New PIN digit {{ $i + 1 }}"
                        data-pin-input
                        class="pin-digit"
                        autocomplete="one-time-code"
                    >
                @endfor
                <input type="hidden" name="new_pin" data-pin-hidden>
            </div>
            <x-input-error :messages="$errors->get('new_pin')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="new_pin_confirmation" :value="__('Confirm New PIN')" />
            <div class="pin-code-group mt-2" data-pin-group>
                @for ($i = 0; $i < 6; $i++)
                    <input
                        type="password"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        maxlength="1"
                        aria-label="Confirm PIN digit {{ $i + 1 }}"
                        data-pin-input
                        class="pin-digit"
                        autocomplete="one-time-code"
                    >
                @endfor
                <input type="hidden" name="new_pin_confirmation" data-pin-hidden>
            </div>
            <x-input-error :messages="$errors->get('new_pin_confirmation')" class="mt-2" />
        </div>

        <x-primary-button class="w-full justify-center">Save new PIN</x-primary-button>
    </form>
</x-guest-layout>
