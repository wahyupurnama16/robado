<x-app-layout>

  <main class="flex-grow p-4 max-w-4xl mx-auto w-full">
    <h2 class="text-center text-2xl text-brown-600 font-semibold mb-8">Cart Pemesanan</h2>
    <div class="grid md:grid-cols-2 gap-8">
      <div class="space-y-4">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Daftar Pesanan</h3>
        <div class="order-list">
        </div>
      </div>
      <form method="POST" id="formSubmit" action="">
        @csrf
        <input type="hidden" id="dataPesanan" name="dataPesanan">
        @if (Auth::user())
        <input type="hidden" name="type" value="berlanggan">
        <div class="space-y-6 bg-white p-6 rounded-lg shadow-sm">
          <h3 class="text-lg font-semibold text-gray-700 mb-4">Informasi Pengiriman</h3>
          <div class="space-y-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
              <span class="text-gray-600 flex items-center"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                  height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                  stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock w-4 h-4 mr-2">
                  <circle cx="12" cy="12" r="10"></circle>
                  <polyline points="12 6 12 12 16 14"></polyline>
                </svg>Tanggal Pengiriman</span>
              <input
                class="border rounded-md p-2 w-full sm:w-auto focus:ring-2 focus:ring-brown-600 focus:border-transparent"
                type="date" value="{{ date('Y-m-d') }}" name="date">
            </div>
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
              <span class="text-gray-600">Jam Pengiriman</span>
              <select name="jam"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block  ">
                <option selected>Jam Pengiriman</option>
                <option value="05">05:00 Wita</option>
                <option value="18">18:00 Wita</option>
              </select>
            </div>
            <div class="flex justify-between items-center py-2 border-t border-gray-200"><span
                class="text-gray-600 flex items-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                  stroke-linejoin="round" class="lucide lucide-map-pin w-4 h-4 mr-2">
                  <path
                    d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0">
                  </path>
                  <circle cx="12" cy="10" r="3"></circle>
                </svg>Alamat Pengiriman</span>
            </div>
            <div>
              <input type="text" id="first_name"
                class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                placeholder="Nama Lokasi" required value="{{ Auth::user()->alamat }}" readonly />
            </div>
            <div class="flex justify-between items-center py-2 border-t border-gray-200"><span
                class="text-gray-600">Total
                Harga</span><span class="text-xl font-bold text-brown-600">Rp.<span id="totalHarga"></span></span></div>
          </div>
        </div>
        @else
        <input type="hidden" name="type" value="tidakBerlangganan">
        <div class="space-y-6 bg-white p-6 rounded-lg shadow-sm">
          <div class="space-y-4">

            <div>
              <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 ">
                Nama</label>
              <input type="text" id="first_name" name="nama"
                class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                placeholder="Nama" required />
            </div>
            <div>
              <label for="wa" class="block mb-2 text-sm font-medium text-gray-900 ">
                Nomor WA</label>
              <input type="text" id="wa" name="noWa"
                class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                placeholder="Nomor Wa" required />
            </div>
            <div class="space-y-4 text-sm md:text-base max-w-2xl mx-auto">
              <p>Mohon maaf jika pemesan belum berlangganan, Robado Bakery Belum bisa melakukan
                pengiriman.</p>
              <p>Pesanan ini hanya berlaku sampai toko tutup hari ini di jam 22:00, dan pemesan wajib
                mengambil serta
                membayar di
                toko Robado Bakery</p>
            </div>
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm w-full max-w-2xl mx-auto">
              <div class="p-4 md:p-6 space-y-4 md:space-y-6">
                <div class="flex items-start space-x-4"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="lucide lucide-map-pin h-6 w-6 flex-shrink-0 mt-1">
                    <path
                      d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0">
                    </path>
                    <circle cx="12" cy="10" r="3"></circle>
                  </svg>
                  <div>
                    <h3 class="font-semibold text-lg">Alamat Toko</h3>
                    <p class="text-sm md:text-base">Jl. Raya Klumpu - Toya Pakeh, Sakti, Kec.
                      Nusa
                      Penida, Kabupaten
                      Klungkung, Bali
                      80771</p>
                  </div>
                </div>
                <div class="bg-gray-100 h-32 md:h-48 rounded-md flex items-center justify-center">
                  <span class="text-gray-500">Google
                    Map</span>
                </div>
              </div>
            </div>
            <div class="flex justify-between items-center py-2 border-t border-gray-200"><span
                class="text-gray-600">Total
                Harga</span><span class="text-xl font-bold text-brown-600">Rp. <span id="totalHarga"></span></span>
            </div>
          </div>
        </div>
        @endif
    </div>
    @if (Auth::user())
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg mt-8 flex items-start"
      style="opacity: 1; will-change: opacity, transform; transform: none;"><svg xmlns="http://www.w3.org/2000/svg"
        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
        stroke-linecap="round" stroke-linejoin="round"
        class="lucide lucide-triangle-alert w-6 h-6 text-yellow-400 mr-3 flex-shrink-0 mt-1">
        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path>
        <path d="M12 9v4"></path>
        <path d="M12 17h.01"></path>
      </svg>
      <div>
        <h3 class="font-bold text-yellow-700">PENTING !!!</h3>
        <p class="text-yellow-600">Pengiriman hanya berlaku jika pesanan maksimal H-12 Jam sebelum waktu
          pengiriman yang
          dipilih.</p>
      </div>
    </div>
    @endif
    <div class="flex justify-between items-center mt-8 bg-white p-4 rounded-lg shadow-sm"><span
        class="text-brown-600 font-medium">Coba Periksa Kembali Pesanan Anda !!!</span>
      <label class="inline-flex items-center cursor-pointer">
        <input type="checkbox" value="" class="sr-only peer">
        <div
          class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300  rounded-full peer  peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all  peer-checked:bg-blue-600">
        </div>
      </label>
    </div>
    <button disabled
      class="inline-flex mt-5 items-center justify-center whitespace-nowrap ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 h-10 px-4 w-full bg-green-500 hover:bg-green-600 text-white py-4 rounded-full text-lg font-semibold transition-colors duration-200 shadow-lg hover:shadow-xl">Pesan
      Sekarang</button>
    </form>
  </main>
  @section('js')
  <script>
    $(document).ready(function() {
                function updateDeliveryOptions() {
                    const now = new Date();
                    const selectedDate = new Date($('input[name="date"]').val());
                    const currentHour = now.getHours();
                    const currentMinutes = now.getMinutes();
                    const deliverySelect = $('select[name="jam"]');
                    // Reset delivery time options
                    deliverySelect.empty();
                    deliverySelect.append('<option selected disabled>Jam Pengiriman</option>');
                    // Fungsi helper untuk menambah hari
                    function addDays(date, days) {
                        const result = new Date(date);
                        result.setDate(result.getDate() + days);
                        return result;
                    }

                    // Format date untuk input
                    function formatDate(date) {
                        return date.toISOString().split('T')[0];
                    }

                    // Jika tanggal yang dipilih adalah hari ini
                    if (selectedDate.toDateString() === now.toDateString()) {
                        console.log('ada');
                        // Kondisi 1: 05:00 - 16:00
                        if (currentHour >= 05 && currentHour <= 16) {
                            const tomorrow = addDays(now, 1);
                            $('input[name="date"]').val(formatDate(tomorrow));
                            deliverySelect.append('<option value="05">05:00 Wita</option>');
                            deliverySelect.append('<option value="18">18:00 Wita</option>');
                        }
                        // Kondisi 2: 16:01 - 22:00
                        else if (currentHour >= 16 && currentHour <= 22) {
                            const tomorrow = addDays(now, 1);
                            $('input[name="date"]').val(formatDate(tomorrow));
                            deliverySelect.append('<option value="18">18:00 Wita</option>');
                        }
                        // Kondisi 3: 22:01 - 05:01 maka muncul 2 hari lagi 
                        else if ((currentHour === 22 && currentMinutes > 0) || currentHour === 23) {
                            const dayAfterTomorrow = addDays(now, 2);

                            $('input[name="date"]').val(formatDate(dayAfterTomorrow));
                            deliverySelect.append('<option value="05">05:00 Wita</option>');
                            deliverySelect.append('<option value="18">18:00 Wita</option>');
                        }
                    } else if (selectedDate > now) {
                        console.log('tidak');
                        const diffInDays = Math.floor((selectedDate - now) / (1000 * 60 * 60 * 24));
                        // Jika besok
                        if (diffInDays === 1) {
                            // Jika sekarang antara 00:00 - 16:00
                            if (currentHour >= 0 && currentHour <= 16) {
                                deliverySelect.append('<option value="05">05:00 Wita</option>');
                                deliverySelect.append('<option value="18">18:00 Wita</option>');
                            }
                            // Jika sekarang antara 16:01 - 22:00
                            else if (currentHour >= 16 && currentHour <= 22) {
                                deliverySelect.append('<option value="18">18:00 Wita</option>');
                            }
                            // Jika sekarang antara 22:01 - 23:59
                            else if ((currentHour === 22 && currentMinutes > 0) || currentHour === 23) {
                                const dayAfterTomorrow = addDays(now, 2);
                                $('input[name="date"]').val(formatDate(dayAfterTomorrow));
                                deliverySelect.append('<option value="05">05:00 Wita</option>');
                                deliverySelect.append('<option value="18">18:00 Wita</option>');
                            }
                        }
                        // Jika lusa atau lebih
                        else if (diffInDays >= 2) {
                            deliverySelect.append('<option value="05">05:00 Wita</option>');
                            deliverySelect.append('<option value="18">18:00 Wita</option>');
                        }
                    }
                }

                updateDeliveryOptions();
                $('input[name="date"]').change(updateDeliveryOptions);

                $('#formSubmit').submit(function(e) {
                    e.preventDefault();
                    const selectedTime = $('select[name="jam"]').val();
                    if (selectedTime === 'Jam Pengiriman') {
                        Swal.fire({
                            title: 'Error',
                            text: 'Silakan pilih jam pengiriman',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                        return false;
                    }

                    // Ambil data form
                    var formData = $(this).serialize();

                    $.ajax({
                        url: '{{ route('pemesanan.store') }}', // Ganti dengan URL endpoint form submission Anda
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            if (response) {
                                // if(response.)
                                sessionStorage.removeItem('cart');
                                Swal.fire({
                                    title: 'Berhasil',
                                    text: 'Pesanan berhasil, {{ Auth::user() ? 'Terima kasih ' . Auth::user()->nama : 'ditunggu kedatanganya di toko.' }}',
                                    icon: 'success',
                                    confirmButtonText: 'Ok'

                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.href = '{{ Auth::user() ?  route('pemesanan.riwayat') : route('dashboard.guest') }}';
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Mohon Hubungi Admin.',
                                    icon: 'error',
                                    confirmButtonText: 'Ok'

                                })
                            }
                        },
                        error: function(xhr, status, error) {
                            // Tangani error jika terjadi
                            Swal.fire({
                                title: 'Error',
                                text: 'Mohon Lengkapi Data dengan Benar',
                                icon: 'error',

                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.href = '{{ route('cart.index') }}';
                                }
                            });
                        }

                    });
                });

                $('.peer').change(function() {
                    $('#dataPesanan').val(sessionStorage.getItem('cart'));
                    if ($(this).is(':checked')) {
                        // Jika checkbox dicentang, aktifkan tombol
                        $('button').prop('disabled', false);
                    } else {
                        // Jika checkbox tidak dicentang, nonaktifkan tombol
                        $('button').prop('disabled', true);
                    }
                });
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
                        $.ajax({
                            url: '/getCart/{{ Auth::user() ? Auth::user()->id : 0 }}',
                            type: 'GET',
                            success: function(cart) {
                                let total = 0;
                                cart.forEach(item => {
                                    renderProduct(item);
                                    total += item.produk.hargaProduk * item.jumlahPemesanan;
                                    $("#totalHarga").html(total.toLocaleString())
                                });
                            }
                        });

                    } else {
                        const cart = getCart();
                        let total = 0;
                        cart.forEach(item => {
                            renderProductGuest(item)
                            total += item.price * item.qty;
                            $("#totalHarga").html(total.toLocaleString())
                        });
                        if (cart.length > 0) {
                            $('#notif').append(`<div class="absolute top-5 left-4">
                          <div class="bg-red-500 rounded-full w-3 h-3"></div>
                        </div>`);
                        }
                    }
                }

                // Render produk di halaman
                function renderProduct(item) {
                    const $product = $(`.product[data-product-id="${item.id}"]`);
                    if ($product.length) {
                        updateDisplay($product, item.qty); // Update tampilan qty
                    } else {
                        const newProductHTML = `
                      <div class="bg-white rounded-lg mt-2 shadow-sm p-4 flex justify-between items-center product" data-product-id="${item.produk.id}">
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

                function renderProductGuest(item) {
                    const $product = $(`.product[data-product-id="${item.id}"]`);
                    if ($product.length) {
                        updateDisplay($product, item.qty); // Update tampilan qty
                    } else {
                        const newProductHTML = `
                  <div class="bg-white rounded-lg mt-2 shadow-sm p-4 flex justify-between items-center product"
                    data-product-id="${item.id}">
                    <div>
                      <span class="font-medium text-gray-800 product-name">${item.name}</span>
                      <p class="text-sm text-gray-500">Rp. ${item.price} / pcs</p>
                    </div>
                    <div class="flex items-center space-x-2">
                      <button class="decrease-btn p-1 hover:bg-gray-100 rounded-full transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          class="lucide lucide-minus w-4 h-4">
                          <path d="M5 12h14"></path>
                        </svg>
                      </button>
                      <span class="w-8 text-center font-medium qty-display">${item.qty}</span>
                      <button class="increase-btn p-1 hover:bg-gray-100 rounded-full transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          class="lucide lucide-plus w-4 h-4">
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
                        'price');; // Ganti dengan harga produk yang sesungguhnya

                    let cart = getCart();
                    let productIndex = cart.findIndex(item => item.id === productId);
                    let qty = (productIndex !== -1) ? parseInt(cart[productIndex].qty) :
                        0; // Pastikan qty minimal 1 jika produk baru

                    // Update quantity berdasarkan tombol yang diklik
                    if ($button.hasClass('increase-btn')) {
                        qty += 1;
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
                                id_user: "{{ Auth::user() ? Auth::user()->id : 0 }}"
                            },
                            success: function(res) {
                                setTimeout(() => {
                                    location.reload();
                                }, 1000); // Untuk debugging
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
                        setTimeout(() => {
                            location.reload();
                        }, 1000); // Untuk debugging
                    }
                    saveCart(cart); // Simpan kembali cart ke sessionStorage
                });

                // Panggil fungsi loadProducts saat halaman dimuat
                loadProducts();
            });
  </script>
  @endsection
</x-app-layout>