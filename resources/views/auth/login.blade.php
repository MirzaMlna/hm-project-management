<x-guest-layout>
    <div class="flex flex-col md:flex-row bg-white rounded-xl overflow-hidden shadow-lg">
        {{-- Kanan: Form Login --}}
        <div class="w-full p-8 bg-white">
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            <div class="mb-4">
                <h2 class="text-2xl font-semibold">Selamat Datang!</h2>
                <p class="mt-2 text-sm text-gray-800">Silakan login untuk melanjutkan ke sistem manajemen proyek.</p>
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                        :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Kata Sandi')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                        autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between mt-4">
                    <label for="remember_me" class="inline-flex items-center text-sm text-gray-600">
                        <input id="remember_me" type="checkbox"
                            class="rounded border-gray-300 text-sky-600 focus:ring-sky-500" name="remember">
                        <span class="ml-2">Ingat saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-sky-600 hover:underline">Lupa kata
                            sandi?</a>
                    @endif
                </div>

                <div class="mt-6">
                    <x-primary-button
                        class="w-full justify-center bg-sky-600 hover:bg-sky-700 focus:ring-sky-500 text-white font-semibold py-2">
                        {{ __('Masuk') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
