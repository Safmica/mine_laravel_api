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
                <div class="grid gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3" id="courses-info"></div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const pathSegments = window.location.pathname.split('/');
            const courseId = pathSegments[2];
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
                            <h1 class="text-2xl font-bold"> ${user.name} > </h1>
                            <a href="#" class="text-2xl font-boldl"></a>
                        </div>
                    `);
                    $.ajax({
                        url: `/api/courses/${courseId}`,
                        method: 'GET',
                        xhrFields: {
                            withCredentials: true
                        },
                        success: function(course) {
                            $('#user-info a')
                                .text(course.title + ' >')
                                .attr('href', `/index`);

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
                url: '/api/courses',
                method: 'GET',
                xhrFields: {
                    withCredentials: true
                },
                success: function(courses) {
                    const container = $('#courses-info');
                    container.html('');

                    if (courses.length === 0) {
                        container.html(`
                            <div class="flex justify-center items-center h-96 w-full col-span-full pt-10">
                                <img src="/assets/404_course.png" alt="Logo" class="opacity-20 w-[400px]">
                            </div>
                        `);
                    } else {
                        courses.forEach(course => {
                            container.append(`
                                <div class="bg-white rounded-lg shadow-lg p-4 flex flex-row items-center h-12">
                                    <a href="/courses/${course.id}/meetings" class="w-full group cursor-pointer relative">
                                        <h3 class="text-lg font-semibold text-center mb-2 truncate w-full">${course.title}</h3>
                                        <span class="absolute top-full left-1/2 transform -translate-x-1/2 mb-2 w-max px-2 py-1 text-sm text-white bg-black rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            ${course.description}
                                        </span>
                                    </a>
                                    <div id="modal-edit-${course.id}" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
                                        <div class="bg-white p-4 rounded-lg shadow-lg w-[400px]">
                                            <h2 class="text-lg font-bold mb-2">Edit Course</h2>
                                            <input type="text" id="edit-title-${course.id}" value="${course.title}" class="border p-2 rounded w-full mb-2" />
                                            <textarea id="edit-description-${course.id}" class="border p-2 rounded w-full mb-2">${course.description}</textarea>
                                            <div class="flex justify-end gap-2">
                                                <button class="bg-gray-300 text-black px-4 py-2 rounded" onclick="document.getElementById('modal-edit-${course.id}').classList.add('hidden')">Cancel</button>
                                                <button class="bg-blue-500 text-white px-4 py-2 rounded" onclick="updateCourse(${course.id})">Save</button>
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
                                                    <button onclick="document.getElementById('modal-edit-${course.id}').classList.remove('hidden')" 
                                                            class="w-full text-left px-4 py-2 text-black">
                                                        Edit
                                                    </button>
                                                </li>   
                                                <li class="hover:bg-gray-100">
                                                    <button onclick="deleteCourse(${course.id})" class="w-full text-left px-4 py-2 text-red-500">
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

            $('#add-course-btn').on('click', function() {
                $('#add-course-modal').removeClass('hidden');
            });

            $('#cancel-add').on('click', function() {
                $('#add-course-modal').addClass('hidden');
            });

            $('#add-course-form').on('submit', function(e) {
                e.preventDefault();

                const formData = {
                    title: $(this).find('input[name="title"]').val(),
                    description: $(this).find('textarea[name="description"]').val()
                };

                $.ajax({
                    url: '/api/courses',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(formData),
                    xhrFields: {
                        withCredentials: true
                    },
                    success: function(res) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Course berhasil ditambahkan.',
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
                            text: 'Terjadi kesalahan saat menambahkan course.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });

        function updateCourse(id) {
            const title = $(`#edit-title-${id}`).val();
            const description = $(`#edit-description-${id}`).val();

            $.ajax({
                url: `api/courses/${id}`,
                method: 'PUT',
                xhrFields: {
                    withCredentials: true
                },
                contentType: 'application/json',
                data: JSON.stringify({
                    title: title,
                    description: description
                }),
                success: function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: 'Course updated successfully',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => location.reload());
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to update course',
                    });
                }
            });
        }

        function deleteCourse(id) {
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
                        url: `api/courses/${id}`,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Course has been deleted.',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => location.reload());
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed!',
                                text: 'Failed to delete course.',
                            });
                        }
                    });
                }
            });
        }
    </script>
</body>

</html>
