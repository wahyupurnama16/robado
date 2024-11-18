<x-app-layout>

    <main class="flex-grow p-6">
        <section class="mb-8">
            <div class="relative h-60 rounded-lg overflow-hidden shadow-lg">
                <img alt="Bakery banner" loading="lazy" decoding="async" data-nimg="fill" sizes="100vw"
                    src="https://robado-bakery.vercel.app/_next/image?url=https%3A%2F%2Fimages.unsplash.com%2Fphoto-1523294587484-bae6cc870010%3Fq%3D80%26w%3D2802%26auto%3Dformat%26fit%3Dcrop%26ixlib%3Drb-4.0.3%26ixid%3DM3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%253D%253D&w=1920&q=75"
                    style="position: absolute; height: 100%; width: 100%; inset: 0px; object-fit: cover; color: transparent;">
                <div
                    class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center text-white">
                    <h2 class="text-4xl font-bold text-center mb-2 text-white">Robado Bakery</h2>
                    <p class="text-lg mb-4 text-center max-w-md">Pesan, Datang, &amp; Nikmati Roti Anda</p><a
                        href="{{ route('register') }}"
                        class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-2 px-6 rounded-full transition-colors text-lg">Berlangganan</a>
                </div>
            </div>
        </section>

        <section>
            <h2 class="text-3xl font-bold mb-6">Ketersediaan Roti di Toko</h2>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-2">
                @foreach ($produks as $produk)
                <div class="product rounded-lg shadow-md overflow-hidden bg-white" data-product-id="{{ $produk->id }}"
                    data-price="{{ $produk->hargaProduk }}">
                    <div class="flex p-4">
                        <img alt="Roti Tawar" loading="lazy" width="80" height="80" decoding="async"
                            class="rounded-md object-cover" src="{{ asset('/storage/produk/'. $produk->gambar) }}"
                            style="color: transparent;">
                        <div class="ml-4 flex-grow">
                            <h3 class="product-name text-xl font-semibold mb-1">{{ $produk->namaProduk }}</h3>
                            <p class="text-amber-600 font-bold text-lg" data-price="{{ $produk->hargaProduk }}">Rp
                                {{ number_format($produk->hargaProduk) }}</p>
                            <p class="text-sm text-gray-600">Tersedia: {{ $produk->jumlahProduk }} pcs</p>
                        </div>
                        <div class="flex flex-col items-end justify-between">
                            <div class="flex items-center space-x-2 mb-2">
                                <button
                                    class="decrease-btn p-1 rounded-full bg-amber-100 text-amber-800 hover:bg-amber-200 transition-colors"
                                    aria-label="Decrease quantity">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-minus w-4 h-4">
                                        <path d="M5 12h14"></path>
                                    </svg>
                                </button>
                                <span class="qty-display w-8 text-center font-medium">0</span>
                                <button
                                    class="increase-btn p-1 rounded-full bg-amber-100 text-amber-800 hover:bg-amber-200 transition-colors"
                                    aria-label="Increase quantity">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-plus w-4 h-4">
                                        <path d="M5 12h14"></path>
                                        <path d="M12 5v14"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
    </main>
    @section('js')
    <script>
        $(document).ready(function() {
          const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
          
          // Simpan cart ke sessionStorage
          function saveCart(cart) {
              sessionStorage.setItem('cart', JSON.stringify(cart));
          }
  
          // Memperbarui tampilan quantity
          function updateDisplay($product, qty) {
              $product.find('.qty-display').text(qty);  // Update tampilan quantity
          }
  
          // Mengambil cart dari sessionStorage
          function getCart() {
              return JSON.parse(sessionStorage.getItem('cart')) || [];
          }
  
          // Memuat produk dari sessionStorage atau database
          function loadProducts() {
              if (isLoggedIn) {

              } else {
                  const cart = getCart();
                  if(cart.length>0){
                      $('#notif').append(`<div class="absolute top-5 left-4">
                        <div class="bg-red-500 rounded-full w-3 h-3"></div>
                        </div>`);
                    }
                //   cart.forEach(item => renderProduct(item));
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
              const productPrice = $product.data('price');; // Ganti dengan harga produk yang sesungguhnya
  
              let cart = getCart();
              let productIndex = cart.findIndex(item => item.id === productId);
              let qty = (productIndex !== -1) ? parseInt(cart[productIndex].qty) : 0;  // Pastikan qty minimal 1 jika produk baru
  
              // Update quantity berdasarkan tombol yang diklik
              if ($button.hasClass('increase-btn')) {
                  qty = qty + 1;
              } else if ($button.hasClass('decrease-btn') && qty >= 1) {
                  qty -= 1;
              }
              // Pastikan qty diupdate dan tampilan diupdate
              updateDisplay($product, qty); 
  
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