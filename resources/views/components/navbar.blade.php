<nav class="bg-transparent p-4">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <div class="text-black text-2xl font-bold">
            <a href="/" class="flex flex-row items-center space-x-2">
                <img src="{{ asset('assets/mine_icon.webp') }}" alt="Logo" class="w-10 h-10">
                <p>MINE</p>
            </a>
        </div>        
        <div class="space-x-4" id="user-menu">
        </div>
    </div>
</nav>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const userMenu = document.getElementById("user-menu");

    userMenu.innerHTML = `
        <div class="text-gray-500 px-4 py-2 font-semibold rounded-xl bg-gray-100">
            Loading...
        </div>
    `;

    fetch('/api/me', {
        method: 'GET',
        credentials: 'include'
    })
    .then(res => {
        if (!res.ok) throw new Error("Unauthorized");
        return res.json();
    })
    .then(user => {
        userMenu.innerHTML = `
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
        `;

        const dropdownButton = document.getElementById('user-dropdown-button');
        const dropdownMenu = document.getElementById('dropdown-menu');
        
        dropdownButton.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownMenu.style.display = dropdownMenu.style.display === 'none' ? 'block' : 'none';
        });

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.user-dropdown')) {
                dropdownMenu.style.display = 'none';
            }
        });

        document.getElementById('logout-btn').addEventListener('click', () => {
            fetch('/api/logout', {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Accept': 'application/json'
                }
            }).then(() => {
                location.reload();
            }).catch(err => {
                console.error('Logout failed:', err);
            });
        });
    })
    .catch(() => {
        userMenu.innerHTML = `
            <a href="/login" class="text-black px-4 py-2 font-semibold rounded-xl hover:bg-gray-100 transition">Login</a>
            <a href="/signup" class="text-white px-4 py-2 rounded-xl font-semibold bg-cos-yellow">Sign Up</a>
        `;
    });
});
</script>
