<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livewire Example</title>
    
    <!-- Livewire Styles -->
    @livewireStyles

    <!-- Optional: Include custom styles directly -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* You can add your custom styles here */
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        input {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 8px 12px;
            border: none;
            background-color: #4CAF50;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <!-- Navbar or Header (optional) -->
    <nav>
        <ul>
            <li><a href="/">Home</a></li>
            <li><a href="/about">About</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <!-- This is where Livewire components will be inserted -->
        @yield('content')
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts

</body>
</html>
