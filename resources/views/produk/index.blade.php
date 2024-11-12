<x-app-layout>
    @section('css')
    <!-- GridJS Theme -->
    <link rel="stylesheet" href="{{ asset('assets/libs/gridjs/theme/mermaid.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.32/sweetalert2.min.css">
    @endsection
    <main class="flex-grow p-4 mx-auto w-full">
        <div class="card">
            <div class="card-body">
                <div class="">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-semibold">Data Produksi</h1>
                        <button id="btnAdd"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Tambah Produk
                        </button>
                    </div>

                    <!-- Grid Table -->
                    <div id="table-gridjs"></div>

                    <!-- Modal Form -->
                    <div id="formModal"
                        class="modal fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
                        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                            <div class="mt-3">
                                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4" id="modalTitle">Tambah
                                    Produk
                                </h3>
                                <form id="produkForm" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" id="produkId" name="id">
                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Produk</label>
                                        <input type="text" id="namaProduk" name="namaProduk"
                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Gambar Produk</label>
                                        <input type="file" id="gambar" name="gambar"
                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                            accept="image/*">
                                        <div id="previewContainer" class="mt-2 hidden">
                                            <img id="preview" src="" alt="Preview"
                                                class="w-full h-32 object-cover rounded">
                                        </div>
                                        <input type="hidden" id="oldImage" name="oldImage">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah</label>
                                        <input type="number" id="jumlahProduk" name="jumlahProduk"
                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Harga</label>
                                        <input type="number" id="hargaProduk" name="hargaProduk"
                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    </div>
                                    <div class="flex justify-end gap-2">
                                        <button type="button"
                                            class="btnCancel bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @section('js')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- GridJS -->
    <script src="{{ asset('assets/libs/gridjs/gridjs.umd.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.32/sweetalert2.min.js"></script>

    <script>
        $(document).ready(function() {
            let grid;
            let modalMode = 'add';

            // Format currency
            function formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(number);
            }

            // Preview image
            function readURL(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        $('#preview').attr('src', e.target.result);
                        $('#previewContainer').removeClass('hidden');
                    }
                    
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Handle image input change
            $('#gambar').change(function() {
                readURL(this);
            });

            // Open modal
            function openModal(mode, data = null) {
                modalMode = mode;
                $('#modalTitle').text(mode === 'add' ? 'Tambah Produk' : 'Edit Produk');
                
                if (mode === 'edit' && data) {
                    $('#produkId').val(data.id);
                    $('#namaProduk').val(data.namaProduk);
                    $('#jumlahProduk').val(data.jumlahProduk);
                    $('#hargaProduk').val(data.hargaProduk);
                    $('#oldImage').val(data.gambar);

                    // Show existing image
                    if (data.gambar) {
                        $('#preview').attr('src', `/storage/produk/${data.gambar}`);
                        $('#previewContainer').removeClass('hidden');
                    } else {
                        $('#previewContainer').addClass('hidden');
                    }
                } else {
                    $('#produkForm')[0].reset();
                    $('#previewContainer').addClass('hidden');
                    $('#preview').attr('src', '');
                    $('#oldImage').val('');
                }
                
                $('#formModal').removeClass('hidden');
            }

            // Close modal
            function closeModal() {
                $('#formModal').addClass('hidden');
                $('#produkForm')[0].reset();
                $('#previewContainer').addClass('hidden');
                $('#preview').attr('src', '');
            }

            // Initialize Grid
            grid = new gridjs.Grid({
                columns: [
                    { id: 'id', name: 'ID' },
                    { id: 'namaProduk', name: 'Nama Produk' },
                    { 
                        id: 'gambar', 
                        name: 'Gambar',
                        formatter: (cell) => {
                            if (cell) {
                                return gridjs.html(`<img src="/storage/produk/${cell}" class="w-16 h-16 object-cover rounded">`);
                            }
                            return '-';
                        }
                    },
                    { id: 'jumlahProduk', name: 'Jumlah' },
                    { 
                        id: 'hargaProduk', 
                        name: 'Harga',
                        formatter: (cell) => formatRupiah(cell)
                    },
                    {
                        id: 'actions',
                        name: 'Actions',
                        formatter: (_, row) => {
                            const data = {
                                id: row.cells[0].data,
                                namaProduk: row.cells[1].data,
                                gambar: row.cells[2].data,
                                jumlahProduk: row.cells[3].data,
                                hargaProduk: row.cells[4].data.toString().replace(/[^0-9]/g, '')
                            };
                            
                            return gridjs.html(`
                                <button class="btnEdit bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded mr-2" 
                                        data-product='${JSON.stringify(data)}'>
                                    Edit
                                </button>
                                <button class="btnDelete bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded"
                                        data-id="${data.id}">
                                    Hapus
                                </button>
                            `);
                        }
                    }
                ],
                server: {
                    url: '{{ route("produksi.data") }}',
                    then: data => data.data
                },
                search: true,
                pagination: {
                    limit: 10
                },
                sort: true,
                language: {
                    search: {
                        placeholder: 'Cari...'
                    },
                    pagination: {
                        previous: 'Sebelumnya',
                        next: 'Selanjutnya',
                        showing: 'Menampilkan',
                        results: () => 'Data'
                    }
                }
            }).render(document.getElementById('table-gridjs'));

            // Event Handlers
            $('#btnAdd').click(function() {
                openModal('add');
            });

            $(document).on('click', '.btnEdit', function() {
                const productData = JSON.parse($(this).attr('data-product'));
                openModal('edit', productData);
            });

            $('.btnCancel').click(function() {
                closeModal();
            });

            $(document).on('click', '.btnDelete', function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/produksi/${id}`,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Berhasil!', response.message, 'success');
                                    grid.forceRender();
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Error!', 'Gagal menghapus data', 'error');
                            }
                        });
                    }
                });
            });

            $('#produkForm').submit(function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const id = $('#produkId').val();
                const url = modalMode === 'add' ? '/produksi' : `/produksi/${id}`;
                
                if (modalMode === 'edit') {
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Berhasil!', response.message, 'success');
                            closeModal();
                            grid.forceRender();
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Gagal menyimpan data', 'error');
                    }
                });
            });
        });
    </script>
    @endsection
</x-app-layout>