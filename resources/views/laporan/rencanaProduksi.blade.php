<x-app-layout>
  @section('css')
  <!-- Gridjs css -->
  <link rel="stylesheet" href="{{ asset('assets/libs/gridjs/theme/mermaid.min.css') }}">
  @endsection

  <main class="flex-grow p-4 mx-auto w-full">
    <div class="mb-8">
      <h2 class="text-center text-2xl text-brown-600 font-semibold mb-4">Laporan Rencana Produksi</h2>
      <!-- Summary Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
          <h3 class="text-lg font-semibold text-gray-700">Total Rencana</h3>
          <p class="text-2xl font-bold text-blue-600">{{ $totalRencana ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
          <h3 class="text-lg font-semibold text-gray-700">Dalam Proses</h3>
          <p class="text-2xl font-bold text-yellow-600">{{ $dalamProses ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
          <h3 class="text-lg font-semibold text-gray-700">Selesai</h3>
          <p class="text-2xl font-bold text-green-600">{{ $selesai ?? 0 }}</p>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div id="table-gridjs"></div>
      </div>
    </div>

    <!-- Production Update Modal -->
    <div id="productionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold">Update Hasil Produksi</h3>
          <button class="closeModal text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <form id="productionUpdateForm" class="space-y-4">
          @csrf
          @method('PUT')
          <input type="hidden" id="production_id" name="production_id">

          <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
            <p id="modal_product_name" class="text-gray-900 font-semibold"></p>
          </div>

          <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Rencana Produksi</label>
            <p id="modal_planned_quantity" class="text-gray-900 font-semibold"></p>
          </div>

          <div class="space-y-2">
            <label for="hasil_produksi" class="block text-sm font-medium text-gray-700">Hasil
              Produksi</label>
            <input type="number" id="hasil_produksi" name="hasil_produksi" required min="0"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brown-500 focus:ring-brown-500">
          </div>

          <div class="flex justify-end space-x-3 mt-6">
            <button type="button"
              class="closeModal px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
              Batal
            </button>
            <button type="submit"
              class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700">
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
    const IS_BAKER = {{ json_encode(Auth::user()->role == 'baker' ? true : false) }};
    // Format functions
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

            function getStatusBadge(status) {
                const badges = {
                    0: '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-800">Menunggu</span>',
                    1: '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-200 text-blue-800">Dalam Proses</span>',
                    2: '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-200 text-green-800">Selesai</span>'
                };
                return badges[status] || badges[0];
            }

            // Action button
            function getActionButton(row) {
              if (!IS_BAKER) return '';
              
                const data = {
                    id: row.id,
                    nama_produk: row.nama_produk,
                    jumlahProduksi: row.jumlahProduksi,
                    hasilProduksi: row.hasilProduksi,
                    idProduksi:row.idProduksi
                };

                return gridjs.html(`
                  <button 
                    onclick='openProductionModal(${JSON.stringify(data).replace(/'/g, "&#39;")});'
                    class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                  >
                    Produksi
                  </button>
            `);
            }

            // Modal functions
            function openProductionModal(data) {
                document.getElementById('production_id').value = data.idProduksi;
                document.getElementById('modal_product_name').textContent = data.nama_produk || '-';
                document.getElementById('modal_planned_quantity').textContent = data.jumlahProduksi;
                document.getElementById('hasil_produksi').value = data.hasilProduksi || 0;

                const modal = document.getElementById('productionModal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeModal() {
                const modal = document.getElementById('productionModal');
                modal.classList.remove('flex');
                modal.classList.add('hidden');
                document.getElementById('productionUpdateForm').reset();
            }

            // Initialize Grid
            new gridjs.Grid({
                columns: [{
                        name: "No",
                        width: "70px",
                        data: (row) => row.id
                    },
                    {
                        name: "Produk",
                        width: "150px",
                        data: (row) => row.nama_produk || '-'
                    },
                    {
                        name: "Rencana Produksi",
                        width: "130px",
                        data: (row) => row.jumlahProduksi
                    },
                    {
                        name: "Hasil Produksi",
                        width: "130px",
                        data: (row) => row.hasilProduksi || 0
                    },
                    {
                        name: "Status",
                        width: "120px",
                        data: (row) => gridjs.html(getStatusBadge(row.statusProduksi))
                    },
                    {
                    name: "Tanggal Dibuat",
                    width: "180px",
                    data: (row) => formatDate(row.created_at)
                    },
                    {
                        name: "Aksi",
                        width: "100px",
                        data: (row) => getActionButton(row)
                    }
                ],
                pagination: {
                    limit: 10
                },
                sort: true,
                search: true,
                server: {
                    url: '/api/laporan/produksi',
                    then: data => data.data.map((item, index) => ({
                        ...item,
                        id: index + 1,
                        idProduksi:item.id
                    })),
                    handle: (res) => {
                        if (!res.ok) {
                            throw Error("Gagal mengambil data");
                        }
                        return res.json();
                    }
                }
            }).render(document.getElementById("table-gridjs"));

            // Event Listeners
            document.querySelectorAll('.closeModal').forEach(button => {
                button.addEventListener('click', closeModal);
            });

            document.getElementById('productionModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal();
                }
            });

            document.getElementById('productionUpdateForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = {
                    id: document.getElementById('production_id').value,
                    hasilProduksi: document.getElementById('hasil_produksi').value,
                    _token: document.querySelector('input[name="_token"]').value,
                    _method: 'PUT'
                };

                fetch(`/api/laporan/produksi/${formData.id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': formData._token
                        },
                        body: JSON.stringify(formData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Data produksi berhasil diupdate',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                closeModal();
                                location.reload();
                            });
                        } else {
                            throw new Error(data.message || 'Terjadi kesalahan');
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: error.message || 'Terjadi kesalahan saat mengupdate data'
                        });
                    });
            });
  </script>
  @endsection
</x-app-layout>