<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" id="form">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="nama" :value="__('Nama')" />
            <x-text-input id="nama" class="block mt-1 w-full" type="text" name="nama" :value="old('nama')" required
                autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('nama')" class="mt-2" />
        </div>


        <div class="mt-4">
            <x-input-label for="jenisPerusahaan" :value="__('Jenis Perusahaan')" />
            <select id="jenisPerusahaan" name="jenisPerusahaan"
                class="bg-gray-50 border mt-2 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option selected disabled>Pilih Jenis Usaha</option>
                <option value="akomodasi">Akomodasi</option>
                <option value="restoran">Restoran</option>
                <option value="individu">Individu</option>
            </select>
        </div>

        <div class="mt-4">
            <x-input-label for="alamatPerusahaan" :value="__('Alamat Perusahaan')" />
            <x-text-input id="alamatPerusahaan" class="block mt-1 w-full" type="text" name="alamatPerusahaan"
                :value="old('alamatPerusahaan')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('alamatPerusahaan')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="NoWa" :value="__('Nomor Wa')" />
            <x-text-input id="NoWa" class="block mt-1 w-full" type="text" name="NoWa" :value="old('NoWa')" required
                autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('NoWa')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    @section('js')
    <script>
        $(document).ready(function() {
                // Tambahkan div untuk menampilkan pesan error
                $('<div id="password-error" class="text-red-500 text-sm mt-1"></div>').insertAfter('#password');
                $('<div id="password-error-confirmasi" class="text-red-500 text-sm mt-1"></div>').insertAfter(
                    '#password_confirmation');

                // Fungsi untuk validasi password
                function validatePassword() {
                    var password = $('#password').val();
                    var confirmPassword = $('#password_confirmation').val();
                    var errorMessages = [];

                    // Reset border input
                    $('#password, #password_confirmation').removeClass('border-red-500').addClass('border-gray-300');

                    // Validasi panjang password
                    if (password.length < 8) {
                        errorMessages.push('Password harus minimal 8 karakter');
                        $('#password').removeClass('border-gray-300').addClass('border-red-500');
                    }


                    // Tampilkan pesan error
                    if (errorMessages.length > 0) {
                        $('#password-error').html(errorMessages.join('<br>'));
                        return false;
                    } else if (password !== confirmPassword && confirmPassword !== '') {
                        $('#password-error-confirmasi').html('Password tidak sama');
                        $('#password_confirmation').removeClass('border-gray-300').addClass('border-red-500');
                        return false;
                    } else {
                        $('#password-error-confirmasi').html('');
                        $('#password-error').html('');
                        return true;
                    }
                }

                // Validasi saat input password berubah
                $('#password, #password_confirmation').on('keyup', function() {
                    validatePassword();
                });

                // Validasi saat form disubmit
                $('#form').on('submit', function(e) {
                    if (!validatePassword()) {
                        e.preventDefault(); // Mencegah form disubmit jika validasi gagal
                        return false;
                    }
                });

            });
    </script>
    @endsection
</x-guest-layout>