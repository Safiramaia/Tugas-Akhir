<x-guest-layout>
    <div
        class="w-full max-w-sm mx-auto bg-gray-50 rounded-xl shadow-lg dark:bg-gray-800 dark:border dark:border-gray-700 mt-10">
        <div class="p-6 sm:p-8 space-y-6">

            <!-- Logo dan Judul -->
            <div class="flex justify-center">
                <a href="#" class="flex flex-col items-center text-xl font-semibold text-gray-900 dark:text-white">
                    <img class="w-24 h-auto mb-2" src="{{ asset('storage/logo/sucofindo.png') }}" alt="logo">
                    SIM PATROLI
                </a>
            </div>

            <h3 class="text-lg font-semibold text-center text-gray-900 md:text-xl dark:text-white">
                Lupa Password?
            </h3>
            <p class="text-sm text-center text-gray-600 dark:text-gray-400">
                Masukkan email Anda dan kami akan mengirimkan link untuk mengatur ulang password Anda.
            </p>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Form -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Email
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="w-full p-2.5 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        placeholder="Masukkan email" required autofocus>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <button type="submit"
                    class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-semibold rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Kirim Link
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
