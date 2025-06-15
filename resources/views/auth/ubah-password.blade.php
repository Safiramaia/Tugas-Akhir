<x-app-layout :title="'Ubah Password'">
    <h2 class="text-center text-2xl font-semibold text-gray-800 mt-4 mb-6">Ubah Password</h2>

    <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-6 md:p-10 max-w-3xl mx-auto">
        <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Password Lama <span class="text-red-600">*</span>
                </label>
                <div class="relative">
                    <input type="password" name="current_password" id="current_password"
                        class="w-full p-2.5 pr-12 border border-gray-200 bg-gray-100 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600
                        dark:bg-gray-700 dark:text-white">
                    <div class="absolute inset-y-0 right-2.5 flex items-center">
                        <button type="button" onclick="togglePasswordVisibility('current_password', 'eyeCurrent')" tabindex="-1">
                            <svg id="eyeCurrent" class="w-5 h-5 text-gray-500 dark:text-gray-300"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.522 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7s-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>
                {{-- Error dari error bag updatePassword --}}
                @if ($errors->updatePassword->has('current_password'))
                    <p class="text-red-600 text-sm mt-2">{{ $errors->updatePassword->first('current_password') }}</p>
                @endif
            </div>

            <div>
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Password Baru <span class="text-red-600">*</span>
                </label> 
                <div class="relative">
                    <input type="password" name="password" id="password"
                        class="w-full p-2.5 pr-12 border border-gray-200 bg-gray-100 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600
                        dark:bg-gray-700 dark:text-white">
                    <div class="absolute inset-y-0 right-2.5 flex items-center">
                        <button type="button" onclick="togglePasswordVisibility('password', 'eyeNew')" tabindex="-1">
                            <svg id="eyeNew" class="w-5 h-5 text-gray-500 dark:text-gray-300"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.522 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7s-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Minimal berisi 8 karakter.</p>
                @if ($errors->updatePassword->has('password'))
                    <p class="text-red-600 text-sm mt-2">{{ $errors->updatePassword->first('password') }}</p>
                @endif
            </div>

            <div>
                <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Konfirmasi Password Baru <span class="text-red-600">*</span>
                </label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full p-2.5 pr-12 border border-gray-200 bg-gray-100 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600
                        dark:bg-gray-700 dark:text-white">
                    <div class="absolute inset-y-0 right-2.5 flex items-center">
                        <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'eyeConfirm')" tabindex="-1">
                            <svg id="eyeConfirm" class="w-5 h-5 text-gray-500 dark:text-gray-300"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.522 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7s-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>
                @if ($errors->updatePassword->has('password_confirmation'))
                    <p class="text-red-600 text-sm mt-2">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                @endif
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-200">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

<script>
    function togglePasswordVisibility(fieldId, eyeId) {
        const input = document.getElementById(fieldId);
        const icon = document.getElementById(eyeId);
        
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("text-gray-500", "dark:text-gray-300");
            icon.classList.add("text-blue-600");
        } else {
            input.type = "password";
            icon.classList.remove("text-blue-600");
            icon.classList.add("text-gray-500", "dark:text-gray-300");
        }
    }
</script>

