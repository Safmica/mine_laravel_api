<div id="sidebar" class="sidebar ml-2 bg-cos-yellow text-black p-4 rounded-xl h-screen overflow-auto w-64 box-border">
    <h2 class="text-2xl font-bold mb-4">Courses List</h2>
    <div id="courses-list" class="mt-2">
        <p>Loading courses...</p>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $.ajax({
        url: '/api/courses',
        method: 'GET',
        xhrFields: {
            withCredentials: true
        },
        success: function(courses) {
            const container = $('#courses-list');
            container.empty();

            if (courses.length === 0) {
                container.html('<p>Tidak ada course tersedia.</p>');
                return;
            }

            const ul = $('<ul></ul>').addClass('space-y-2');

            courses.forEach(course => {
                const li = $('<li></li>');
                const a = $('<a></a>')
                    .attr('href', `/meetings/course/${course.id}`) 
                    .addClass('block px-3 py-2 rounded-lg text-black font-medium hover:bg-white hover:text-yellow-600 transition duration-200 break-words')
                    .text(course.title);

                li.append(a);
                ul.append(li);
            });

            container.append(ul);
        },
        error: function() {
            $('#courses-list').html('<p>Gagal memuat data courses.</p>');
        }
    });
});
</script>
