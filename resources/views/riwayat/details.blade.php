<x-app-layout>

  <main class="flex-grow p-4 mx-auto w-full">
    <h2 class="text-center text-2xl text-brown-600 font-semibold mb-8">Detail Pemesanan</h2>
    <div class="card">

      <div class="card-body">


        <a href="{{ route('pemesanan.riwayat') }}"
          class="text-white bg-stone-600  hover:bg-dark-800 focus:ring-4 focus:ring-dark-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2   focus:outline-none dark:focus:ring-blue-800">Kembali</a>

        @if (Auth::user()->role == 'admin')

        <a href="/update/bayar/{{ $pemesanan->id }}/{{ $pemesanan->statusPembayaran == 1 ?  0: 1}}" class="text-white bg-yellow-500 hover:bg-yellow-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5
        me-2 focus:outline-none dark:focus:ring-blue-800">Belum Dibayar</a>

        <a href="/update/kirim/{{ $pemesanan->id }}/{{ $pemesanan->statusPengiriman == 1 ? 0 : 1}}" class="text-white bg-yellow-500 hover:bg-yellow-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5
                me-2 focus:outline-none dark:focus:ring-blue-800">Belum DiKirim</a>
        @endif

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-2 mt-5">
          <div>
            <h3 class="text-lg font-semibold mb-3">Informasi Pemesan</h3>
            <table class="table-auto">
              <tbody>
                <tr>
                  <td>Nama</td>
                  <td>:</td>
                  <td>{{ $pemesanan->id_user ? $pemesanan->user->nama : $pemesanan->nama }}</td>
                </tr>
                <tr>
                  <td>Jenis Perusahaan</td>
                  <td>:</td>
                  <td>{{ $pemesanan->id_user ? $pemesanan->user->jenisPerusahaan : '' }}</td>
                </tr>
                <tr>
                  <td>Email Perusahaan</td>
                  <td>:</td>
                  <td>{{ $pemesanan->id_user ? $pemesanan->user->email : '' }}</td>
                </tr>
                <tr>
                  <td>Whatsapp</td>
                  <td>:</td>
                  <td>{{ $pemesanan->id_user ? $pemesanan->user->noWa : $pemesanan->noWa }}</td>
                </tr>
                <tr>
                  <td>Alamat</td>
                  <td>:</td>
                  <td>{{ $pemesanan->id_user ? $pemesanan->user->alamat : '' }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div>
            <h3 class="text-lg font-semibold mb-3">Informasi Produk</h3>
            @if ($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                <li class="text-red-500">{{ $error }}</li>
                @endforeach
              </ul>
            </div>
            @endif
            <form action="{{ route('pemesanan.updatePesananDetails', $pemesanan->id) }}" method="POST">
              @csrf
              <table class="table-auto">
                <tbody>
                  <tr>
                    <td>Produk</td>
                    <td>: &nbsp;</td>
                    <td> <input disabled
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        value="{{ $pemesanan->produk->namaProduk }}" /></td>
                  </tr>
                  <tr>
                    <td>Harga Produk</td>
                    <td>: &nbsp;</td>
                    <td> <input disabled
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        value="{{ $pemesanan->harga }}" /></td>
                  </tr>
                  <tr>
                    <td>Jumlah Pemesanan</td>
                    <td>: &nbsp;</td>
                    <td> <input name="jumlahPesanan" type="number" {{ (Auth::user()->role !== 'admin') ? 'disable' : ''
                      }}
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500
                      focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600
                      dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                      value="{{ $pemesanan->jumlahPemesanan }}" /></td>
                  </tr>
                  <tr>
                    <td>Total Harga</td>
                    <td>: &nbsp;</td>
                    <td> <input name="totalHarga" type="number" {{ (Auth::user()->role !== 'admin') ? 'disable' : '' }}
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500
                      focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600
                      dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                      value="{{ $pemesanan->totalHarga }}" /></td>
                  </tr>
                  <tr>
                    <td>Jadwal Pengiriman</td>
                    <td>: &nbsp;</td>
                    <td>
                      <input type="date" name="tanggalPengiriman" {{ (Auth::user()->role !== 'admin') ?
                      'disabled="true"' : ''
                      }}
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500
                      focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600
                      dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                      value="{{ $pemesanan->tanggalPengiriman}}" />
                      <input type="time" name="jamPengiriman" {{ (Auth::user()->role !== 'admin') ? 'disabled="true"' :
                      ''
                      }}
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500
                      focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600
                      dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                      value="{{ $pemesanan->jamPengiriman}}" />
                    </td>
                  </tr>

                  <tr>
                    <td>Status</td>
                    <td>: &nbsp;</td>
                    <td>
                      <span
                        class="badge rounded p-1 text-white bg-{{ $pemesanan->statusPembayaran === 1 ? 'green-600' : 'yellow-600' }}">{{
                        $pemesanan->statusPembayaran === 1 ? 'Sudah Bayar' : 'Belum Bayar' }}</span>
                      <span
                        class="badge rounded p-1 text-white bg-{{ $pemesanan->statusPengiriman === 1 ? 'green-600' : 'yellow-600' }}">{{
                        $pemesanan->statusPengiriman === 1 ? 'Sudah Dikirim' : 'Belum Dikirim' }}</span>
                    </td>
                  </tr>
                </tbody>
              </table>
              @if (Auth::user()->role == 'admin')
              <button type="submit"
                class="text-white mt-3 bg-stone-600  hover:bg-dark-800 focus:ring-4 focus:ring-dark-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2   focus:outline-none dark:focus:ring-blue-800">Submit</button>
              @endif
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>

</x-app-layout>