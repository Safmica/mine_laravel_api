@if (session('success'))
    <div 
        x-data="{ show: true }" 
        x-show="show"
        x-init="setTimeout(() => show = false, 3000)" 
        class="fixed top-5 left-1/2 -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transition"
    >
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div 
        x-data="{ show: true }" 
        x-show="show"
        x-init="setTimeout(() => show = false, 3000)" 
        class="fixed top-5 left-1/2 -translate-x-1/2 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg transition"
    >
        {{ session('error') }}
    </div>
@endif