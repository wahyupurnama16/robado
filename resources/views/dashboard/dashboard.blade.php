<x-app-layout>

  @section('css')
  <!-- Gridjs css -->
  <link rel="stylesheet" href="{{ asset('assets/libs/gridjs/theme/mermaid.min') }}.css">
  @endsection
  <main class="flex-grow p-6">
    <section class="mb-8">
      <div class="relative h-60 rounded-lg overflow-hidden shadow-lg">
        <img alt="Bakery banner" loading="lazy" decoding="async" data-nimg="fill" sizes="100vw"
          src="https://robado-bakery.vercel.app/_next/image?url=https%3A%2F%2Fimages.unsplash.com%2Fphoto-1523294587484-bae6cc870010%3Fq%3D80%26w%3D2802%26auto%3Dformat%26fit%3Dcrop%26ixlib%3Drb-4.0.3%26ixid%3DM3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%253D%253D&w=1920&q=75"
          style="position: absolute; height: 100%; width: 100%; inset: 0px; object-fit: cover; color: transparent;">
        <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center text-white">
          <h2 class="text-4xl font-bold text-center mb-2 text-white">Halo {{ Auth::user()->role }} ,Robado
            Bakery</h2>
          <p class="text-lg mb-4 text-center max-w-md">Pesan, Datang, &amp; Nikmati Roti Anda</p>
          {{-- <button
            class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-2 px-6 rounded-full transition-colors text-lg">Berlangganan</button>
          --}}
        </div>
      </div>
    </section>

    <section>
      @if (Auth::user()->role == 'admin')
      <h2 class="text-3xl font-bold mb-6 text-center">{{ date('d M
        Y') }}</h2>
      <div class="grid grid-cols-3 mb-9">
        <a href="#"
          class="grid grid-cols-3 max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">

          <h5 class="mb-2 col-span-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
            Jumlah Pesanan

          </h5>
          <div class="flex items-center justify-center">
            <p class="text-lg font-bold inline-block  text-gray-700 dark:text-gray-400">
              {{ $pesanan }} Pesanan</p>
          </div>
        </a>

        <a href="#"
          class="grid grid-cols-3 max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">

          <h5 class="mb-2 col-span-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
            Jumlah Belum
            Terkirim
          </h5>
          <div class="flex items-center justify-center">
            <p class="text-lg font-bold inline-block  text-gray-700 dark:text-gray-400">
              {{ $belumTerkirim }} Pesanan</p>
          </div>
        </a>
        <a href="#"
          class="grid grid-cols-3 max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">

          <h5 class="mb-2 col-span-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
            Jumlah sudah
            Terkirim
          </h5>
          <div class="flex items-center justify-center">
            <p class="text-lg font-bold inline-block  text-gray-700 dark:text-gray-400">
              {{ $terkirim }} Pesanan</p>
          </div>
        </a>
      </div>
      @endif
    </section>
    @if (Auth::user()->role !== 'admin')
    <section>


      <h2 class="text-3xl font-bold mb-6 text-center">
        {{ Auth::user()->status == 2
        ? 'Ketersediaan Roti di Toko'
        : "Mohon
        Konfirmasi Langganan Ke Admin" }}
      </h2>

      <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-2">

        @if (Auth::user()->status == 2)
        @foreach ($produks as $produk)
        <div class="product rounded-lg shadow-md overflow-hidden bg-white" data-product-id="{{ $produk->id }}"
          data-price="{{ $produk->hargaProduk }}">
          <div class="flex p-4">
            <img alt="Roti Tawar" loading="lazy" width="80" height="80" decoding="async" class="rounded-md object-cover"
              src="https://robado-bakery.vercel.app/_next/image?url=https%3A%2F%2Fimages.unsplash.com%2Fphoto-1621930599436-32ba90132e3e%3Fq%3D80%26w%3D2940%26auto%3Dformat%26fit%3Dcrop%26ixlib%3Drb-4.0.3%26ixid%3DM3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%253D%253D&w=96&q=75"
              style="color: transparent;">
            <div class="ml-4 flex-grow">
              <h3 class="product-name text-xl font-semibold mb-1">{{ $produk->namaProduk }}
              </h3>
              <p class="text-amber-600 font-bold text-lg" data-price="{{ $produk->hargaProduk }}">Rp
                {{ number_format($produk->hargaProduk) }}</p>
              <p class="text-sm text-gray-600">Tersedia: {{ $produk->jumlahProduk }} pcs</p>
            </div>
            <div class="flex flex-col items-end justify-between">
              <div class="flex items-center space-x-2 mb-2">
                <button
                  class="decrease-btn p-1 rounded-full bg-amber-100 text-amber-800 hover:bg-amber-200 transition-colors"
                  aria-label="Decrease quantity">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-minus w-4 h-4">
                    <path d="M5 12h14"></path>
                  </svg>
                </button>
                <span class="qty-display w-8 text-center font-medium">0</span>
                <button
                  class="increase-btn p-1 rounded-full bg-amber-100 text-amber-800 hover:bg-amber-200 transition-colors"
                  aria-label="Increase quantity">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-plus w-4 h-4">
                    <path d="M5 12h14"></path>
                    <path d="M12 5v14"></path>
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
        @endforeach
        @endif

      </div>
    </section>
    @else
    <div class="card-body">
      <div id="table-gridjs"></div>
    </div>
    @endif
  </main>

  @section('js')
  <!-- Gridjs js -->
  <script src="{{ asset('assets/libs/gridjs/gridjs.umd.js') }}"></script>
  <script>
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
                                `<span class="badge rounded p-1 bg-${row.statusPembayaran === 0 ? 'warning' : 'success'}">${row.statusPembayaran == 1 ?
      'Sudah Bayar' : 'Belum Bayar'}</span>`
                            )
                        },
                        {
                            name: "Status Pengiriman",
                            width: "200px",
                            data: (row) => gridjs.html(
                                `<span class="badge rounded p-1 bg-${row.statusPengiriman === 0 ? 'warning' : 'success'}">${row.statusPengiriman == 1 ?
      'Sudah Dikirim' : 'Belum Dikirim'}</span>`
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
                        url: `/get/riwayat/dashboard/{{ Auth::user()->id }}`,
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
  <script>
    $(document).ready(function() {
                // Fungsi untuk sinkronisasi keranjang di sessionStorage dengan server
                function syncCartWithServer() {
                    const cart = getCart();
                    console.log(cart);
                    if (cart.length > 0) {
                        $.ajax({
                            url: '/sync-cart',
                            type: 'POST',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                cart: cart
                            },
                            success: function(response) {
                                console.log('Cart data synced with server', response);
                            },
                            error: function(error) {
                                console.error('Failed to sync cart data with server', error);
                            }
                        });
                    }
                }

                const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};

                // Simpan cart ke sessionStorage
                function saveCart(cart) {
                    sessionStorage.setItem('cart', JSON.stringify(cart));
                }

                // Memperbarui tampilan quantity
                function updateDisplay($product, qty) {
                    $product.find('.qty-display').text(qty); // Update tampilan quantity
                }

                // Mengambil cart dari sessionStorage
                function getCart() {
                    return JSON.parse(sessionStorage.getItem('cart')) || [];
                }

                // Memuat produk dari sessionStorage atau database
                function loadProducts() {
                    if (isLoggedIn) {
                        syncCartWithServer();
                        $.ajax({
                            url: '/getCart/{{ Auth::user()->id }}',
                            type: 'GET',
                            success: function(cart) {
                                cart.forEach(item => {
                                    renderProduct(item);
                                });
                                console.log('dtabase' + cart); // Untuk debugging
                            }
                        });
                    } else {
                        const cart = getCart();
                        cart.forEach(item => renderProduct(item));
                    }
                }

                // Render produk di halaman
                function renderProduct(item) {
                    const $product = $(`.product[data-product-id="${item.id}"]`);
                    if ($product.length) {
                        updateDisplay($product, item.qty); // Update tampilan qty
                    } else {
                        const newProductHTML = `
                      <div class="bg-white rounded-lg mt-2 shadow-sm p-4 flex justify-between items-center product" data-product-id="${item.id}">
                          <div>
                              <span class="font-medium text-gray-800 product-name">${item.produk.namaProduk}</span>
                              <p class="text-sm text-gray-500">Rp. ${item.produk.hargaProduk} / pcs</p>
                          </div>
                          <div class="flex items-center space-x-2">
                              <button class="decrease-btn p-1 hover:bg-gray-100 rounded-full transition-colors duration-200">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-minus w-4 h-4">
                                      <path d="M5 12h14"></path>
                                  </svg>
                              </button>
                              <span class="w-8 text-center font-medium qty-display">${item.jumlahPemesanan}</span>
                              <button class="increase-btn p-1 hover:bg-gray-100 rounded-full transition-colors duration-200">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus w-4 h-4">
                                      <path d="M5 12h14"></path>
                                      <path d="M12 5v14"></path>
                                  </svg>
                              </button>
                              <span class="text-sm text-gray-500">Pcs</span>
                          </div>
                      </div>`;
                        $('.order-list').append(newProductHTML);
                    }
                }

                // Fungsi untuk menangani event klik tombol increase dan decrease
                $(document).on('click', '.increase-btn, .decrease-btn', function() {
                    const $button = $(this);
                    const $product = $button.closest('.product');
                    const productId = $product.data('product-id');
                    const productName = $product.find('.product-name').text();
                    const productPrice = $product.data(
                    'price');; // Ganti dengan harga produk yang sesungguhnya // Ganti dengan harga produk yang sesungguhnya

                    let cart = getCart();
                    console.log(cart);
                    let productIndex = cart.findIndex(item => item.id === productId);
                    let qty = (productIndex !== -1) ? parseInt(cart[productIndex].qty) :
                        0; // Pastikan qty minimal 1 jika produk baru

                    // Update quantity berdasarkan tombol yang diklik
                    if ($button.hasClass('increase-btn')) {
                        qty = qty + 1;
                    } else if ($button.hasClass('decrease-btn') && qty >= 1) {
                        qty -= 1;
                    }
                    // Pastikan qty diupdate dan tampilan diupdate
                    updateDisplay($product, qty);

                    // Jika user login, update cart di server
                    if (isLoggedIn) {
                        $.ajax({
                            url: '/create-cart',
                            type: 'POST',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                id: productId,
                                qty: qty,
                                id_user: "{{ auth::user()->id }}"
                            },
                            success: function(res) {
                                // console.log(res); // Untuk debugging
                            }
                        });
                    }
                    // Jika user tidak login, update cart di sessionStorage
                    if (qty > 0) {
                        const productData = {
                            id: productId,
                            name: productName,
                            price: productPrice,
                            qty: qty
                        };
                        if (productIndex !== -1) {
                            cart[productIndex] = productData; // Update data produk yang ada
                        } else {
                            cart.push(productData); // Tambahkan produk baru
                        }
                    } else if (productIndex !== -1) {
                        cart.splice(productIndex, 1); // Hapus produk jika qty = 0
                    }
                    saveCart(cart); // Simpan kembali cart ke sessionStorage
                });

                // Panggil fungsi loadProducts saat halaman dimuat
                loadProducts();
            });
  </script>
  @endsection
</x-app-layout>