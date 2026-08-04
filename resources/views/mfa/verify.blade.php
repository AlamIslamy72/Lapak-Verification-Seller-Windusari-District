<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Masukkan 6 digit kode dari aplikasi Google Authenticator Anda untuk melanjutkan.
    </div>

    @if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
        {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('mfa.verify.submit') }}">
        @csrf
        <x-input-label for="one_time_password" value="Kode Google Authenticator" />
        <x-text-input id="one_time_password" class="block mt-1 w-full" type="text"
            name="one_time_password" maxlength="6" required autofocus />
        <x-input-error :messages="$errors->get('one_time_password')" class="mt-2" />

        <div class="flex items-center justify-between mt-4">
            <a href="{{ route('mfa.setup') }}"
                class="text-sm text-gray-600 underline hover:text-gray-900">
                Tampilkan QR Code
            </a>

            <x-primary-button>
                Verifikasi
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>