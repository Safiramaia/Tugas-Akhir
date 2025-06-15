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
                Masuk ke Akun Anda
            </h3>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Email
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="w-full p-2.5 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        placeholder="Masukkan email" required>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Password
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password"
                            class="w-full p-2.5 pr-12 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Masukkan password" required>
                        <div class="absolute inset-y-0 right-2.5 flex items-center">
                            <button type="button" onclick="togglePasswordVisibility()" tabindex="-1">
                                <svg id="eyeIcon" class="w-5 h-5 text-gray-500 dark:text-gray-300"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.522 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7s-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Ingat saya dan Lupa password -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                        <input id="remember" type="checkbox" name="remember"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                        <span class="ml-2">Ingat saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-blue-600 hover:underline dark:text-blue-400"
                            href="{{ route('password.request') }}">
                            Lupa Password?
                        </a>
                    @endif
                </div>

                <!-- Tombol Masuk -->
                <button type="submit"
                    class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-semibold rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Masuk
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById("password");
        const eyeIcon = document.getElementById("eyeIcon");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.classList.remove("text-gray-500", "dark:text-gray-300");
            eyeIcon.classList.add("text-blue-600");
        } else {
            passwordInput.type = "password";
            eyeIcon.classList.remove("text-blue-600");
            eyeIcon.classList.add("text-gray-500", "dark:text-gray-300");
        }
    }
</script>

