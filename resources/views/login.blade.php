@extends('layouts.home')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-white px-4">
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-md">
        <a href="/" class="flex justify-start">
            <button class="text-cos-yellow font-bold pb-5 rounded">&lt; Home</button>
        </a>

        <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Login to MINE</h2>

        <div id="error-box" class="hidden bg-red-100 text-red-700 p-2 rounded mb-4"></div>

        <form id="login-form" class="space-y-4">
            <div>
                <label class="block text-gray-600 mb-2">Email</label>
                <input type="email" name="email" required placeholder="youremail@gmail.com"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#fcbf49]">
            </div>

            <div>
                <label class="block text-gray-600 mb-2">Password</label>
                <input type="password" name="password" required placeholder="your password"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#fcbf49]">
            </div>

            <button type="submit"
                class="w-full bg-[#fcbf49] text-white py-2 rounded-lg font-semibold hover:bg-yellow-500 transition">
                Login
            </button>
        </form>

        <p class="text-center text-gray-600 mt-4">
            Belum punya akun? 
            <a href="/signup" class="text-[#fcbf49] font-semibold hover:underline">Sign Up</a>
        </p>
    </div>
</div>
<script>
$(document).ready(function () {
    $('#login-form').on('submit', function (e) {
        e.preventDefault();

        const formData = {
            email: $('input[name="email"]').val(),
            password: $('input[name="password"]').val()
        };

        $.ajax({
            url: '/api/login',
            type: 'POST',
            data: formData,
            success: function (response) {
                $.cookie('auth_token', response.token, { path: '/', expires: 1 });

                window.location.href = "/index";
            },
            error: function (xhr) {
                let errorMsg = 'Login gagal';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                $('#error-box').text(errorMsg).removeClass('hidden');
            }
        });
    });
});
</script>
@endsection
