<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title')</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white">
    <div id="container" class="hidden">
        <x-navbar />
        <div class="flex flex-row">
            <x-sidebar />
            <div class="bg-cos-yellow min-h-screen w-full p-6 mx-4 rounded-2xl space-y-6">
                <div class="text-2xl font-bold" id="user-info"></div>
                <div class="grid gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3" id="file-info"></div>
            </div>
        </div>
        <button id="add-file-btn"
            class="fixed bottom-6 right-6 bg-cos-yellow hover:bg-cos-light-yellow text-white font-bold py-3 px-5 rounded-full shadow-lg text-lg">
            +
        </button>

        <div id="add-file-modal"
            class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <h2 class="text-xl font-bold mb-4">Tambah File Baru</h2>
                <form id="add-file-form" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium">File</label>
                        <input type="file" name="file" required class="w-full border p-2 rounded mt-1" />
                    </div>
                    <div class="flex justify-end space-x-2 pt-4">
                        <button type="button" id="cancel-add"
                            class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded">Batal</button>
                        <button type="submit"
                            class="bg-cos-yellow hover:bg-cos-light-yellow text-white px-4 py-2 rounded">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const pathSegments = window.location.pathname.split('/');
        const courseId = pathSegments[2];
        const meetingId = pathSegments[4];
        $(document).ready(function() {
            $.ajax({
                url: '/api/me',
                method: 'GET',
                xhrFields: {
                    withCredentials: true
                },
                success: function(user) {
                    $('#container').show();
                    $('#user-info').html(`
                        <div class="flex flex-row">
                            <a href="/index" class="text-2xl font-bold" id="user-nav">${user.name} > </a>
                            <a href="#" class="text-2xl font-bold" id="coourse-nav"></a>
                            <a href="#" class="text-2xl font-bold" id="meeting-nav"></a>
                        </div>
                    `);
                    $.ajax({
                        url: `/api/courses/${courseId}`,
                        method: 'GET',
                        xhrFields: {
                            withCredentials: true
                        },
                        success: function(course) {
                            $('#user-info #coourse-nav')
                                .text(course.title + ' >')
                                .attr('href', `/courses/${courseId}/meetings`);
                            $.ajax({
                                url: `/api/courses/${courseId}/meetings/${meetingId}`,
                                method: 'GET',
                                xhrFields: {
                                    withCredentials: true
                                },
                                success: function(meeting) {
                                    $('#user-info #meeting-nav')
                                        .text(meeting.meeting_name + ' >')
                                        .attr('href',
                                            `/courses/${courseId}/meetings/${meetingId}/files`
                                            );
                                },
                                error: function() {}
                            });
                        },
                        error: function() {
                            window.location.href = '/';
                        }
                    });
                },
                error: function() {
                    window.location.href = '/';
                }
            });

            $.ajax({
                url: `/api/courses/${courseId}/meetings/${meetingId}/files`,
                method: 'GET',
                xhrFields: {
                    withCredentials: true
                },
                success: function(files) {
                    const container = $('#file-info');
                    container.html('');

                    if (files.length === 0) {
                        container.html(`
                            <div class="flex justify-center items-center h-96 w-full col-span-full pt-10">
                                <img src="/assets/404_file.png" alt="Logo" class="opacity-20 w-[400px]">
                            </div>
                        `);
                    } else {
                        files.forEach(file => {
                            container.append(`
                                <div class="bg-white rounded-lg shadow-lg p-4 flex flex-row items-center h-12">
                                    <a href="/storage/${file.filepath}" class="w-full group cursor-pointer relative">
                                        <h3 class="text-lg font-semibold text-center mb-2 truncate w-full">${file.filename}</h3>
                                    </a>
                                    <div id="modal-edit-${file.id}" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
                                        <div class="bg-white p-4 rounded-lg shadow-lg w-[400px]">
                                            <h2 class="text-lg font-bold mb-2">Edit File</h2>
                                            <input type="text" id="edit-file-name-${file.id}" value="${file.filename}" class="border p-2 rounded w-full mb-2" />
                                            <div class="flex justify-end gap-2">
                                                <button class="bg-gray-300 text-black px-4 py-2 rounded" onclick="document.getElementById('modal-edit-${file.id}').classList.add('hidden')">Cancel</button>
                                                <button class="bg-cos-yellow text-white px-4 py-2 rounded" onclick="updateFile(${file.id})">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <button class="dropdown-toggle text-gray-500 hover:text-black focus:outline-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12h12M6 6h12M6 18h12" />
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu absolute right-0 mt-2 w-32 bg-white rounded-lg shadow-md hidden z-50">
                                            <ul>
                                                <li class="hover:bg-gray-100">
                                                    <button onclick="document.getElementById('modal-edit-${file.id}').classList.remove('hidden')" 
                                                            class="w-full text-left px-4 py-2 text-black">
                                                        Edit
                                                    </button>
                                                </li>   
                                                <li class="hover:bg-gray-100">
                                                    <button onclick="deleteFile(${file.id})" class="w-full text-left px-4 py-2 text-red-500">
                                                        Delete
                                                    </button>
                                                </li>       
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            `);
                        });
                    }
                }
            });

            $(document).on('click', '.dropdown-toggle', function(e) {
                e.stopPropagation();
                $('.dropdown-menu').not($(this).next()).addClass('hidden');
                $(this).next('.dropdown-menu').toggleClass('hidden');
            });

            $(document).on('click', function() {
                $('.dropdown-menu').addClass('hidden');
            });

            $('#add-file-btn').on('click', function() {
                $('#add-file-modal').removeClass('hidden');
            });

            $('#cancel-add').on('click', function() {
                $('#add-file-modal').addClass('hidden');
            });

            $('#add-file-form').on('submit', function(e) {
                e.preventDefault();

                const form = $(this)[0];
                const formData = new FormData(form);

                $.ajax({
                    url: `/api/courses/${courseId}/meetings/${meetingId}/files`,
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'File berhasil ditambahkan.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(err) {
                        console.error(err);
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menambahkan File.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });

        function updateFile(id) {
            const filename = $(`#edit-file-name-${id}`).val();

            $.ajax({
                url: `/api/courses/${courseId}/meetings/${meetingId}/files/${id}`,
                method: 'PUT',
                xhrFields: {
                    withCredentials: true
                },
                contentType: 'application/json',
                data: JSON.stringify({
                    filename: filename,
                }),
                success: function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: 'File updated successfully',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => location.reload());
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to update file',
                    });
                }
            });
        }

        function deleteFile(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/api/courses/${courseId}/meetings/${meetingId}/files/${id}`,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'File has been deleted.',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => location.reload());
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed!',
                                text: 'Failed to delete file.',
                            });
                        }
                    });
                }
            });
        }
    </script>
</body>

</html>
