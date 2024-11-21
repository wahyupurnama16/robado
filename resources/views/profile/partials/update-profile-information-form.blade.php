<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile ') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Memperbarui informasi profil dan alamat email akun Anda.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="nama" :value="__('Nama')" />
            <x-text-input id="nama" class="block mt-1 w-full" type="text" name="nama" :value="old('nama', $user->nama)"
                required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('nama')" class="mt-2" />
        </div>


        <div class="mt-4">
            <x-input-label for="jenisPerusahaan" :value="__('Jenis Perusahaan')" />
            <select id="jenisPerusahaan" name="jenisPerusahaan"
                class="bg-gray-50 border mt-2 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option selected disabled>Pilih Jenis Usaha</option>
                <option value="akomodasi" {{ $user->jenisPerusahaan == 'akomodasi' ? 'selected' : '' }}>Akomodasi
                </option>
                <option value="restoran" {{ $user->jenisPerusahaan == 'restoran' ? 'selected' : '' }}>Restoran</option>
                <option value="individu" {{ $user->jenisPerusahaan == 'individu' ? 'selected' : '' }}>Individu</option>
            </select>
        </div>

        <div class="mt-4">
            <x-input-label for="alamatPerusahaan" :value="__('Alamat Perusahaan')" />
            <x-text-input id="alamatPerusahaan" class="block mt-1 w-full" type="text" name="alamat"
                :value="old('alamatPerusahaan', $user->alamat)" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('alamatPerusahaan')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="NoWa" :value="__('Nomor Wa')" />
            <x-text-input id="NoWa" class="block mt-1 w-full" type="text" name="noWa" :value="old('noWa', $user->noWa)"
                required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('NoWa')" class="mt-2" />
        </div>


        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>