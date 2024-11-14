<x-app-layout>
  @section('css')
  <!-- Gridjs css -->
  <link rel="stylesheet" href="{{ asset('assets/libs/gridjs/theme/mermaid.min.css') }}">
  @endsection
  <main class="flex-grow p-4 mx-auto w-full">
    <div class="mb-8">
      <!-- Add Production Plan Button -->
      <div class="flex justify-end mb-4">
        <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
          id="btnAddProduction">
          Tambah Rencana Produksi
        </button>
      </div>

      <h2 class="text-center text-2xl text-brown-600 font-semibold mb-4">Laporan Pemesanan Harian</h2>
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Rangkuman Pesanan</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          @foreach ($summaryByProduct as $product)
          <div class="bg-gray-50 p-4 rounded-lg">
            <h4 class="font-semibold text-gray-700">{{ $product->namaProduk }}</h4>
            <p class="text-gray-600">Total Pesanan: {{ $product->total_pesanan }}</p>
            <p class="text-gray-600">Total Nilai: Rp. {{ number_format($product->total_nilai) }}</p>
          </div>
          @endforeach
        </div>
      </div>
    </div>


    <div class="card">
      <div class="card-body">
        <div id="table-gridjs"></div>
      </div>
    </div>

    <!-- Production Plan Modal -->
    <div id="productionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-full max-w-xl">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold">Tambah Rencana Produksi</h3>
          <button class="closeModal text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <form id="productionForm" class="space-y-4">
          @csrf
          @foreach ($products as $product)
          <div class="grid grid-cols-2 gap-2">
            <div>
              <input type="hidden" name="produk[]" value="{{ $product->id }}">
              <input type="text" required readonly
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brown-500 focus:ring-brown-500"
                placeholder="" value="{{ $product->namaProduk }}">
            </div>

            <div>
              <input type="number" id="jumlahProduksi" required min="1" name="jumlahProduksi[]"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brown-500 focus:ring-brown-500"
                placeholder="Masukkan jumlah produksi">
            </div>
          </div>
          @endforeach
          <div class="flex justify-end space-x-3 mt-6">
            <button type="button"
              class="closeModal px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
              Batal
            </button>
            <button type="submit"
              class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-brown-700">
              Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>

  @section('js')
  <!-- Gridjs js -->
  <script src="{{ asset('assets/libs/gridjs/gridjs.umd.js') }}"></script>

  <script>
    function formatRupiah(number) {
                if (!number || isNaN(number)) return 'Rp 0';
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(number);
            }

            function formatDate(dateString) {
                if (!dateString) return '-';
                try {
                    const date = new Date(dateString);
                    return date.toLocaleString('id-ID', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                } catch (e) {
                    return '-';
                }
            }

            document.getElementById("table-gridjs") &&
                new gridjs.Grid({
                    columns: [{
                            name: "No",
                            width: "70px",
                            data: (row) => row.id
                        },
                        {
                            name: "Nama Pemesan",
                            width: "150px",
                            data: (row) => row.nama || 'Pelanggan Umum'
                        },
                        {
                            name: "Produk",
                            width: "150px",
                            data: (row) => row.namaProduk
                        },
                        {
                            name: "Jumlah",
                            width: "100px",
                            data: (row) => row.jumlahPemesanan
                        },
                        {
                            name: "Harga Satuan",
                            width: "130px",
                            data: (row) => gridjs.html(formatRupiah(row.harga))
                        },
                        {
                            name: "Total Harga",
                            width: "150px",
                            data: (row) => gridjs.html(formatRupiah(row.harga * row.jumlahPemesanan))
                        },
                        {
                            name: "Waktu Pengiriman",
                            width: "200px",
                            data: (row) => formatDate(row.tanggalPengiriman + ' ' + row.jamPengiriman)
                        }
                    ],
                    pagination: {
                        limit: 10
                    },
                    sort: true,
                    search: true,
                    server: {
                        url: '/api/laporan/pemesanan/daily',
                        then: data => data.data.map((item, index) => ({
                            ...item,
                            id: index + 1
                        })),
                        handle: (res) => {
                            if (!res.ok) {
                                throw Error("Gagal mengambil data");
                            }
                            return res.json();
                        }
                    }
                }).render(document.getElementById("table-gridjs"));
  </script>
  @endsection
</x-app-layout>