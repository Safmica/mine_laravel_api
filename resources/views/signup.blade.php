@extends('layouts.home')

@section('title', 'Sign Up')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-white px-4">
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-md">
        <a href="/" class="flex justify-start">
            <button class="text-cos-yellow font-bold pb-5 rounded">
                < Home
            </button>
        </a>
        <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Sign Up to MINE</h2>
        
        <form id="signupForm" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-600 mb-2">Name</label>
                <input type="text" name="name" required placeholder="your name"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#fcbf49]">
            </div>

            <div>
                <label class="block text-gray-600 mb-2">Email</label>
                <input type="email" name="email" required placeholder="youremail@gmail.com"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#fcbf49]">
            </div>

            <div>
                <label class="block text-gray-600 mb-2">Password</label>
                <input type="password" name="password" required placeholder="min. 6 char"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#fcbf49]">
            </div>

            <div id="errorContainer" class="bg-red-100 text-red-700 p-2 rounded mb-4 hidden"></div>

            <button type="submit"
                class="w-full bg-[#fcbf49] text-white py-2 rounded-lg font-semibold hover:bg-yellow-500 transition">
                Sign Up
            </button>
        </form>

        <p class="text-center text-gray-600 mt-4">
            Sudah punya akun? 
            <a href="/login" class="text-[#fcbf49] font-semibold hover:underline">Login</a>
        </p>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>

<script>
$(document).ready(function () {
    $('#signupForm').on('submit', function (e) {
        e.preventDefault();

        const formData = {
            name: $('input[name="name"]').val(),
            email: $('input[name="email"]').val(),
            password: $('input[name="password"]').val(),
        };

        $.ajax({
            url: '/api/signup',
            type: 'POST',
            data: formData,
            success: function (response) {
                window.location.href = '/login';
            },
            error: function (xhr) {
                let message = 'Signup gagal.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    message = Object.values(errors).flat().join('<br>');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                $('#errorContainer').html(message).removeClass('hidden');
            }
        });
    });
});
</script>
