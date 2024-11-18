<x-app-layout>
    @section('css')
    <!-- Gridjs css -->
    <link rel="stylesheet" href="{{ asset('assets/libs/gridjs/theme/mermaid.min') }}.css">
    @endsection
    <main class="flex-grow p-4 mx-auto w-full">
        <h2 class="text-center text-2xl text-brown-600 font-semibold mb-8">Pemesanan</h2>
        <div class="card">

            @if (Auth::user() && Auth::user()->role == 'admin' && !Request::is('riwayat/*'))
            <!-- Button to trigger modal -->
            <div class="flex justify-end items-center mb-6 absolute top-8 right-7 z-10">
                <button type="button" onclick="openModal()"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Kirim Laporan
                </button>
            </div>

            <!-- Modal -->
            <div id="reportModal"
                class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-20 transition-opacity duration-300">
                <div
                    class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white transform transition-transform duration-300">
                    <div class="mt-3">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Pilih Waktu Pengiriman Laporan</h3>
                        <form action="{{ route('pemesanan.sendLaporanOwner') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="tanggal">
                                    Tanggal Pemesanan
                                </label>
                                <input type="date" name="tanggal" id="tanggal"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="waktu">
                                    Waktu Pengiriman
                                </label>
                                <select name="waktu" id="waktu"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    required>
                                    <option value="">Pilih Waktu</option>
                                    <option value="05:00:00">Pagi (05:00)</option>
                                    <option value="18:00:00">Sore (18:00)</option>
                                </select>
                            </div>
                            <div class="flex items-center justify-end mt-6">
                                <button type="button" onclick="closeModal()"
                                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Kirim
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @endif
            <div class="card-body">
                <div id="table-gridjs"></div>
            </div>
        </div>
    </main>
    @section('js')
    <!-- Gridjs js -->
    <script src="{{ asset('assets/libs/gridjs/gridjs.umd.js') }}"></script>

    <script>
        function openModal() {
                const modal = document.getElementById('reportModal');
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.querySelector('.transform').classList.add('scale-100');
                    modal.classList.add('opacity-100');
                }, 20);
            }

            function closeModal() {
                const modal = document.getElementById('reportModal');
                modal.querySelector('.transform').classList.remove('scale-100');
                modal.classList.remove('opacity-100');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
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

            function formatRupiah(number) {
                if (!number || isNaN(number)) return 'Rp 0';
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0, // Menghilangkan angka desimal
                    maximumFractionDigits: 0 // Menghilangkan angka desimal
                }).format(number);
            }


            async function handleDelete(pemesananId) {
                try {
                    const result = await Swal.fire({
                        title: 'Konfirmasi Hapus',
                        text: 'Apakah Anda yakin ingin menghapus pesanan ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    });

                    if (result.isConfirmed) {
                        // Kirim request delete ke server
                        const response = await fetch(`/hapus/${pemesananId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                            },
                        });

                        if (response.ok) {
                            // Tampilkan pesan sukses
                            await Swal.fire(
                                'Terhapus!',
                                'Pesanan berhasil dihapus.',
                                'success'
                            );
                            // Refresh tabel
                            location.reload();
                        } else {
                            throw new Error('Gagal menghapus pesanan');
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire(
                        'Error!',
                        'Terjadi kesalahan saat menghapus pesanan.',
                        'error'
                    );
                }
            }


            document.getElementById("table-gridjs") &&
                new gridjs.Grid({
                    columns: [{
                            name: "ID",
                            width: "80px",
                            data: (row) => row.id
                        },
                        {
                            name: "Nama Pemesan",
                            width: "150px",
                            data: (row) => row.namaUsaha
                        },
                        {
                            name: "Produk",
                            width: "150px",
                            data: (row) => row.namaProduk
                        },
                        {
                            name: "Total Pesan",
                            width: "150px",
                            data: (row) => row.jumlahPemesanan
                        },
                        {
                            name: "Harga",
                            width: "130px",
                            data: (row) => gridjs.html(
                                formatRupiah(row.harga)
                            )
                        },
                        {
                            name: "Total Harga",
                            width: "150px",
                            data: (row) => gridjs.html(
                                formatRupiah(row.harga * row.jumlahPemesanan)
                            )
                        },
                        {
                            name: "Tanggal Pemesanan",
                            width: "250px",
                            data: (row) =>
                                gridjs.html(
                                    (row.tanggalPengiriman !== " ") ?
                                    formatDate(row.tanggalPengiriman) : 'Ambil Ke Toko'
                                )
                        },
                        {
                            name: "Status Pembayaran",
                            width: "200px",
                            data: (row) => gridjs.html(
                                `<span
                      class="badge rounded p-1 bg-${row.statusPembayaran === 0 ? 'warning' : 'success'}">${row.statusPembayaran == 1 ? 'Sudah Bayar' : 'Belum Bayar'}</span>`
                            )
                        },
                        {
                            name: "Status Pengiriman",
                            width: "200px",
                            data: (row) => gridjs.html(
                                `<span
                      class="badge rounded p-1 bg-${row.statusPengiriman === 0 ? 'warning' : 'success'}">${row.statusPengiriman == 1 ? 'Sudah Dikirim' : 'Belum Dikirim'}</span>`
                            )
                        },
                        {
                            name: "Action",
                            width: "300px",
                            data: (row) => {
                                let buttons = '';
                                const isAdmin = {{ Auth::user()->role === 'admin' ? 'true' : 'false' }};

                                if (isAdmin) {
                                    if (row.statusPembayaran == 0) {
                                        buttons +=
                                            `<a href="/update/bayar/${row.pemesanan_id}/1" class="badge rounded p-1 bg-green-600 text-white">Bayar</a> `;
                                    }

                                    if (row.statusPengiriman == 0) {
                                        buttons +=
                                            `<a href="/update/kirim/${row.pemesanan_id}/1" class="badge rounded p-1 bg-blue-600 text-white">kirim</a> `;
                                    }

                                    buttons += `<button onclick="handleDelete(${row.pemesanan_id})"
                                    class="badge rounded p-1 bg-red-600 text-white">Hapus</button>`;

                                }

                                // Tombol Details selalu ditampilkan
                                buttons +=
                                    `<a href='/details/${row.pemesanan_id}' class="badge rounded p-1 bg-stone-600 text-white">Details</a>`;


                                return gridjs.html(buttons);
                            }
                        },
                    ],
                    pagination: {
                        limit: 10
                    },
                    sort: !0,
                    search: !0,
                    server: {
                        url: `/get/riwayat/pesanan/{{ Auth::user()->id }}`,
                        then: data => {
                            return data.data.map((item, index) => ({
                                ...item,
                                id: index + 1
                            }))
                        },
                        handle: (res) => {
                            if (!res.ok) {
                                throw Error("Gagal mengambil data");
                            }
                            return res.json();
                        },
                    }
                }).render(document.getElementById("table-gridjs"));
    </script>
    @endsection
</x-app-layout>