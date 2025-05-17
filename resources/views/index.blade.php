<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title')</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white">
    <div id="navbar-container" class="hidden">
        <x-navbar />
    </div>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: '/api/me',
                method: 'GET',
                xhrFields: {
                    withCredentials: true
                },
                success: function() {
                    $('#navbar-container').show();
                },
                error: function() {
                    window.location.href = '/';
                }
            });
        });
    </script>
    
</body>
</html>
