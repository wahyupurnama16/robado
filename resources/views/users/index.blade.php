<!-- resources/views/users/index.blade.php -->
<x-app-layout>
  @section('css')
  <link rel="stylesheet" href="{{ asset('assets/libs/gridjs/theme/mermaid.min.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.32/sweetalert2.min.css">
  <style>
    /* Add custom CSS for horizontal scroll */
    .gridjs-wrapper {
      overflow-x: auto;
      min-width: 100%;
    }

    /* Optional: Set minimum width for the table */
    .gridjs-table {
      min-width: 1200px;
      /* Adjust this value based on your needs */
    }
  </style>
  @endsection

  <main class="flex-grow p-4 mx-auto w-full">
    <div class="card">
      <div class="card-body">
        <div class="">
          <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">User Management</h1>
            <button id="btnAdd" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
              Add User
            </button>
          </div>

          <!-- Grid Table -->
          <div id="table-gridjs"></div>

          <!-- Modal Form -->
          <div id="formModal"
            class="modal fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
              <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4" id="modalTitle">Add User</h3>
                <form id="userForm">
                  @csrf
                  <input type="hidden" id="userId" name="id">
                  <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                    <input type="text" id="nama" name="nama" required
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                  </div>
                  <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" id="email" name="email" required
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                  </div>
                  <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Whatsapp</label>
                    <input type="text" id="noWa" name="noWa" required
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                  </div>
                  <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Alamat</label>
                    <input type="text" id="alamat" name="alamat" required
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                  </div>
                  <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" id="password" name="password"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                  </div>
                  <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Role</label>
                    <select id="role" name="role" required
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                      <option value="pelanggan">Pelanggan</option>
                      <option value="owner">Owner</option>
                      <option value="admin">Admin</option>
                      <option value="baker">Baker</option>
                    </select>
                  </div>
                  <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Jenis Perusahaan</label>
                    <select id="jenisPerusahaan" name="jenisPerusahaan"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                      <option selected disabled>Pilih Jenis Usaha</option>
                      <option value="akomodasi">Akomodasi</option>
                      <option value="restoran">Restoran</option>
                      <option value="individu">Individu</option>
                    </select>
                  </div>
                  <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                    <select id="status" name="status" required
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                      <option value="1">Pending</option>
                      <option value="2">Active</option>
                      <option value="0">Banned</option>
                    </select>
                  </div>
                  <div class="flex justify-end gap-2">
                    <button type="button"
                      class="btnCancel bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                      Cancel
                    </button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                      Save
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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="{{ asset('assets/libs/gridjs/gridjs.umd.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.32/sweetalert2.min.js"></script>

  <script>
    // resources/views/users/index.blade.php - Javascript Section
      $(document).ready(function() {
        let grid;
        let modalMode = 'add';
    
        // Format status
        function formatStatus(status) {
            switch(parseInt(status)) {
                case 0:
                    return gridjs.html(`<span class="px-2 py-1 bg-red-200 text-red-800 rounded-full">Banned</span>`);
                case 1:
                    return gridjs.html(`<span class="px-2 py-1 bg-yellow-200 text-yellow-800 rounded-full">Pending</span>`);
                case 2:
                    return gridjs.html(`<span class="px-2 py-1 bg-green-200 text-green-800 rounded-full">Active</span>`);
                default:
                    return 'Unknown';
            }
        }
    
        // Open modal
        function openModal(mode, data = null) {
            modalMode = mode;
            $('#modalTitle').text(mode === 'add' ? 'Add User' : 'Edit User');
            
            if (mode === 'edit' && data) {
                $('#userId').val(data.id);
                $('#nama').val(data.nama);
                $('#noWa').val(data.noWa);
                $('#alamat').val(data.alamat);
                $('#email').val(data.email);
                $('#role').val(data.role);
                $('#jenisPerusahaan').val(data.jenisPerusahaan);
                $('#status').val(data.status);
                // Don't fill password on edit
                $('#password').val('');
            } else {
                $('#userForm')[0].reset();
            }
            
            $('#formModal').removeClass('hidden');
        }
    
        // Close modal
        function closeModal() {
            $('#formModal').addClass('hidden');
            $('#userForm')[0].reset();
        }
    
        // Initialize Grid
        grid = new gridjs.Grid({
            columns: [
                { id: 'id', name: 'ID' },
                { id: 'nama', name: 'Nama' },
                { id: 'jenisPerusahaan', name: 'Jenis Perusahaan' },
                { id: 'email', name: 'Email' },
                { id: 'alamat', name: 'alamat',width: "150px", },
                { id: 'noWa', name: 'noWa' , width: "150px",},
                { id: 'role', name: 'Role', width: "150px", },
                { 
                    id: 'status', 
                    name: 'Status',
                    width: "150px",
                    formatter: (cell) => formatStatus(cell)
                },
                {
                    id: 'actions',
                    name: 'Actions',
                    width: "250px",
                    formatter: (_, row) => {
                        const data = {
                            id: row.cells[0].data,
                            nama: row.cells[1].data,
                            jenisPerusahaan: row.cells[2].data,
                            email: row.cells[3].data,
                            alamat: row.cells[4].data,
                            noWa: row.cells[5].data,
                            role: row.cells[6].data,
                            status: row.cells[7].data
                        };
                        
                        return gridjs.html(`
                            <button class="btnEdit bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded mr-2" 
                                    data-user='${JSON.stringify(data)}'>
                                Edit
                            </button>
                            <button class="btnDelete bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded"
                                    data-id="${data.id}">
                                Delete
                            </button>
                        `);
                    }
                }
            ],
            server: {
                url: '{{ route("users.data") }}',
                then: data => data.data
            },
            search: true,
            pagination: {
                limit: 10
            },
            sort: true,
            language: {
                search: {
                    placeholder: 'Search...'
                },
                pagination: {
                    previous: 'Previous',
                    next: 'Next',
                    showing: 'Showing',
                    results: () => 'Records'
                }
            }
        }).render(document.getElementById('table-gridjs'));
    
        // Event Handlers
        $('#btnAdd').click(function() {
            openModal('add');
        });
    
        $(document).on('click', '.btnEdit', function() {
            const userData = JSON.parse($(this).attr('data-user'));
            openModal('edit', userData);
        });
    
        $('.btnCancel').click(function() {
            closeModal();
        });
    
        $(document).on('click', '.btnDelete', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/users/${id}`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Deleted!', response.message, 'success');
                                grid.forceRender();
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', 'Failed to delete record', 'error');
                        }
                    });
                }
            });
        });
    
        $('#userForm').submit(function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const id = $('#userId').val();
            const url = modalMode === 'add' ? '/users' : `/users/${id}`;
            
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
                  console.log(response);
                    if (response.success) {
                        Swal.fire('Success!', response.message, 'success');
                        closeModal();
                        location.reload();
                    }
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = 'Failed to save record:\n';
                    for (let field in errors) {
                        errorMessage += `\n${errors[field].join('\n')}`;
                    }
                    Swal.fire('Error!', errorMessage, 'error');
                }
            });
        });
    });
  </script>
  @endsection
</x-app-layout>