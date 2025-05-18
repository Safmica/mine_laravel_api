<nav class="bg-transparent p-4">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <div class="text-black text-2xl font-bold">
            <a href="/index" class="flex flex-row items-center space-x-2">
                <img src="{{ asset('assets/mine_icon.webp') }}" alt="Logo" class="w-10 h-10">
                <p>MINE</p>
            </a>
        </div>        
        <div class="space-x-4" id="user-menu">
        </div>
    </div>
</nav>

<script>
$(document).ready(function() {
    const userMenu = $('#user-menu');

    userMenu.html(`
        <div class="text-gray-500 px-4 py-2 font-semibold rounded-xl bg-gray-100">
            Loading...
        </div>
    `);

    $.ajax({
        url: '/api/me',
        method: 'GET',
        xhrFields: {
            withCredentials: true
        },
        success: function(user) {
            userMenu.html(`
                <div class="relative inline-block text-left user-dropdown">
                    <button 
                        id="user-dropdown-button"
                        class="text-black px-4 py-2 font-semibold rounded-xl bg-gray-100 hover:bg-gray-200 transition"
                    >
                        ${user.name}
                    </button>
                    <div 
                        id="dropdown-menu"
                        class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg z-50"
                        style="display: none;"
                    >
                        <button 
                            id="logout-btn"
                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                        >
                            Logout
                        </button>
                    </div>
                </div>
            `);

            $('#user-dropdown-button').on('click', function(e) {
                e.stopPropagation();
                const menu = $('#dropdown-menu');
                if (menu.is(':visible')) {
                    menu.hide();
                } else {
                    menu.show();
                }
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('.user-dropdown').length) {
                    $('#dropdown-menu').hide();
                }
            });

            $('#logout-btn').on('click', function() {
                $.ajax({
                    url: '/api/logout',
                    method: 'POST',
                    xhrFields: {
                        withCredentials: true
                    },
                    headers: {
                        'Accept': 'application/json'
                    },
                    success: function() {
                        window.location.href = '/';
                    },
                    error: function(err) {
                        console.error('Logout failed:', err);
                    }
                });
            });
        },
        error: function() {
            userMenu.html(`
                <a href="/login" class="text-black px-4 py-2 font-semibold rounded-xl hover:bg-gray-100 transition">Login</a>
                <a href="/signup" class="text-white px-4 py-2 rounded-xl font-semibold bg-cos-yellow">Sign Up</a>
            `);
        }
    });
});
</script>
