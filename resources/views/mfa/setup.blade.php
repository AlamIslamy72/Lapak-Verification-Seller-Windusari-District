<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Aktivasi Google Authenticator (MFA)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                        {{ $errors->first() }}
                    </div>
                @endif

                <p class="mb-4 text-gray-700">
                    1. Buka aplikasi <strong>Google Authenticator</strong> di HP Anda.<br>
                    2. Scan QR Code di bawah ini.<br>
                    3. Masukkan 6 digit kode yang muncul di aplikasi untuk konfirmasi.
                </p>

                <div class="flex justify-center mb-4">
                    {!! $qrCodeUrl !!}
                </div>

                <p class="text-sm text-gray-500 mb-4 text-center">
                    Atau masukkan kode secret ini secara manual: <br>
                    <span class="font-mono font-bold">{{ $secret }}</span>
                </p>

                <form method="POST" action="{{ route('mfa.confirm') }}">
                    @csrf
                    <label class="block font-medium text-sm text-gray-700 mb-1">
                        Masukkan 6 digit kode dari Google Authenticator
                    </label>
                    <input type="text" name="one_time_password" maxlength="6"
                        class="border-gray-300 rounded-md shadow-sm w-full mb-4"
                        placeholder="123456" autofocus>

                    <button type="submit"
                        class="bg-teal-700 text-white px-4 py-2 rounded-md hover:bg-teal-800">
                        Aktifkan MFA
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
